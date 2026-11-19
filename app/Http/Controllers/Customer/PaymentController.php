<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    private function initMidtrans(): void
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');

        // Khusus pengujian di localhost jika sertifikat SSL lokal bermasalah
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

        // Jika sudah dibayar, langsung arahkan ke detail pesanan
        if ($order->status === 'paid') {
            return redirect()->route('customer.orders.show', $order->id);
        }

        // Batas waktu pembayaran 15 menit dari order dibuat (dalam milidetik untuk JS)
        $deadlineTimestamp = $order->created_at->copy()->addMinutes(15)->timestamp * 1000;

        // Jika waktu 15 menit sudah lewat
        if (now()->greaterThan($order->created_at->copy()->addMinutes(15))) {
            $order->update(['status' => 'cancelled']);
            optional($order->payment)->update(['status' => 'expired']);
        }

        $this->initMidtrans();

        // Gunakan order_id unik per percobaan bayar agar tidak ditolak Midtrans
        $midtransOrderId = $order->invoice_number . '-' . $order->id . '-' . time();

        // 1. Periksa & amankan nilai gross_amount
        $grossAmount = max(1, (int) round($order->total_amount));

        // 2. Periksa & bersihkan format nomor phone dari relasi user
        $cleanPhone = preg_replace('/[^0-9]/', '', $order->user->phone ?? '');
        if (strlen($cleanPhone) < 8) {
            $cleanPhone = '081234567890';
        }
        $params = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => (int) round($order->total_amount),
            ],
            'customer_details' => [
                'first_name' => $order->user->name ?? 'Customer',
                'email' => $order->user->email ?? 'customer@terracafe.com',
                'phone' => $order->user->phone ?? '081234567890',
            ],
        ];

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

        // --- MULAI GANTI DARI SINI (gantikan Snap::getSnapToken($params)) ---
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
                'http_status' => $response->status(),
                'midtrans_body' => $response->json() ?? $response->body(),
                'params' => $params,
            ]);
        }

        $snapToken = $response->json('token');

        if (!$snapToken) {
            dd([
                'message' => 'Token tidak ditemukan pada respons Midtrans',
                'midtrans_body' => $response->json() ?? $response->body(),
            ]);
        }
        // --- SAMPAI SINI ---

        // Simpan ke kolom yang sudah ada di tabel payments
        optional($order->payment)->update([
            'payment_gateway'  => 'midtrans',
            'transaction_id'   => $midtransOrderId,
            'response_payload' => [
                'snap_token' => $snapToken,
            ],
        ]);

        return view('customer.payment-qris', compact('order', 'snapToken', 'deadlineTimestamp'));
        // Simpan ke kolom yang sudah ada di tabel payments
        optional($order->payment)->update([
            'payment_gateway'  => 'midtrans',
            'transaction_id'   => $midtransOrderId,
            'response_payload' => [
                'snap_token' => $snapToken,
            ],
        ]);

        return view('customer.payment-qris', compact('order', 'snapToken', 'deadlineTimestamp'));
    }

    // Endpoint webhook / notifikasi dari Midtrans
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

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                $payment->update(['status' => 'success']);
                $order?->update(['status' => 'paid']);
            }
        } elseif ($transactionStatus == 'settlement') {
            $payment->update(['status' => 'success']);
            $order?->update(['status' => 'paid']);
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $payment->update(['status' => $transactionStatus === 'expire' ? 'expired' : 'failed']);
            $order?->update(['status' => 'cancelled']);
        }

        return response()->json(['message' => 'OK']);
    }
}
