<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Notification;

class PaymentController extends Controller
{
    private function initMidtrans(): void
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');

        if (app()->environment('local')) {
            Config::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 0,
            ];
        }
    }

    public function showQris($orderId)
    {
        $order = Order::with(['payment', 'user', 'items.product'])->findOrFail($orderId);

        // Jika sudah dibayar / sedang diproses / selesai, arahkan ke detail pesanan
        if (in_array($order->status, ['paid', 'processing', 'completed'])) {
            return redirect()->route('customer.orders.show', $order->id);
        }

        // Jika sudah dibatalkan, arahkan ke detail pesanan tanpa membuka pembayaran baru
        if ($order->status === 'cancelled') {
            return redirect()->route('customer.orders.show', $order->id)
                ->with('error', 'Pesanan ini telah dibatalkan dan tidak dapat dibayar lagi.');
        }

        // Batas waktu pembayaran 15 menit dari order dibuat (dalam milidetik untuk JS)
        $deadlineTimestamp = $order->created_at->copy()->addMinutes(15)->timestamp * 1000;

        // Jika waktu 15 menit sudah lewat -> batalkan pesanan & expire pembayaran, lalu hentikan
        if (now()->greaterThan($order->created_at->copy()->addMinutes(15))) {
            $order->update(['status' => 'cancelled']);
            optional($order->payment)->update(['status' => 'expired']);

            return redirect()->route('customer.orders.show', $order->id)
                ->with('error', 'Waktu pembayaran 15 menit telah habis. Pesanan otomatis dibatalkan.');
        }

        $this->initMidtrans();

        $midtransOrderId = $order->invoice_number . '-' . $order->id . '-' . time();
        $grossAmount = max(1, (int) round($order->total_amount));

        $cleanPhone = preg_replace('/[^0-9]/', '', $order->user->phone ?? '');
        if (strlen($cleanPhone) < 8) {
            $cleanPhone = '081234567890';
        }

        $params = [
            'transaction_details' => [
                'order_id'     => $midtransOrderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $order->user->name ?: 'Customer',
                'email'      => filter_var($order->user->email ?? '', FILTER_VALIDATE_EMAIL)
                    ? $order->user->email
                    : 'customer@terracafe.com',
                'phone'      => $cleanPhone,
            ],
        ];

        $serverKey = config('services.midtrans.server_key');
        $snapUrl = config('services.midtrans.is_production')
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $http = Http::withBasicAuth($serverKey, '')
            ->acceptJson()
            ->asJson();

        if (app()->environment('local')) {
            $http = $http->withoutVerifying();
        }

        $response = $http->post($snapUrl, $params);

        if ($response->failed()) {
            dd([
                'http_status'   => $response->status(),
                'midtrans_body' => $response->json() ?? $response->body(),
                'params'        => $params,
            ]);
        }

        $snapToken = $response->json('token');

        if (!$snapToken) {
            dd([
                'message'       => 'Token tidak ditemukan pada respons Midtrans',
                'midtrans_body' => $response->json() ?? $response->body(),
            ]);
        }

        optional($order->payment)->update([
            'payment_gateway'  => 'midtrans',
            'transaction_id'   => $midtransOrderId,
            'response_payload' => [
                'snap_token' => $snapToken,
            ],
        ]);

        return view('customer.payment-qris', compact('order', 'snapToken', 'deadlineTimestamp'));
    }

    public function notification(Request $request)
    {
        $this->initMidtrans();

        $notif = new Notification();
        $transactionStatus = $notif->transaction_status;
        $fraudStatus = $notif->fraud_status;
        $midtransOrderId = $notif->order_id;

        $payment = Payment::where('transaction_id', $midtransOrderId)->first();
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $order = Order::find($payment->order_id);

        $payment->update([
            'response_payload' => array_merge(
                is_array($payment->response_payload) ? $payment->response_payload : [],
                ['last_notification' => $request->all()]
            ),
        ]);

        if (
            $transactionStatus === 'settlement' ||
            ($transactionStatus === 'capture' && $fraudStatus === 'accept')
        ) {
            $payment->update(['status' => 'success']);

            // Jangan timpa jika pesanan sudah terlanjur dibatalkan/selesai
            if ($order && !in_array($order->status, ['cancelled', 'completed'])) {
                $order->update(['status' => 'processing']);
            }
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $payment->update(['status' => $transactionStatus === 'expire' ? 'expired' : 'failed']);
            $order?->update(['status' => 'cancelled']);
        }

        return response()->json(['message' => 'OK']);
    }

    public function verifyStatus($orderId)
    {
        $order = Order::with('payment')
            ->where('user_id', auth()->id())
            ->findOrFail($orderId);

        $payment = $order->payment;

        if (!$payment || !$payment->transaction_id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data transaksi tidak ditemukan.',
            ], 404);
        }

        $serverKey = config('services.midtrans.server_key');
        $statusUrl = config('services.midtrans.is_production')
            ? "https://api.midtrans.com/v2/{$payment->transaction_id}/status"
            : "https://api.sandbox.midtrans.com/v2/{$payment->transaction_id}/status";

        $http = Http::withBasicAuth($serverKey, '')
            ->acceptJson();

        if (app()->environment('local')) {
            $http = $http->withoutVerifying();
        }

        $response = $http->get($statusUrl);

        if ($response->failed()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal mengecek status transaksi ke Midtrans.',
            ], 500);
        }

        $data = $response->json();
        $transactionStatus = $data['transaction_status'] ?? null;
        $fraudStatus = $data['fraud_status'] ?? null;

        $payment->update([
            'response_payload' => array_merge(
                is_array($payment->response_payload) ? $payment->response_payload : [],
                ['status_check' => $data]
            ),
        ]);

        if (
            $transactionStatus === 'settlement' ||
            ($transactionStatus === 'capture' && $fraudStatus === 'accept')
        ) {
            $payment->update(['status' => 'success']);

            if (!in_array($order->status, ['cancelled', 'completed'])) {
                $order->update(['status' => 'processing']);
            }

            return response()->json([
                'status'       => 'processing',
                'redirect_url' => route('customer.orders.show', $order->id),
            ]);
        }

        if (in_array($transactionStatus, ['expire', 'cancel', 'deny'])) {
            $payment->update(['status' => $transactionStatus === 'expire' ? 'expired' : 'failed']);
            $order->update(['status' => 'cancelled']);

            return response()->json([
                'status'       => 'cancelled',
                'redirect_url' => route('customer.orders.show', $order->id),
            ]);
        }

        return response()->json([
            'status'       => $transactionStatus ?? 'pending',
            'redirect_url' => route('customer.orders.show', $order->id),
        ]);
    }
}
