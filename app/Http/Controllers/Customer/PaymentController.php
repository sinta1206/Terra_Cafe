<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function showQris($orderId)
    {
        $order = Order::with('payment')->findOrFail($orderId);
        return view('customer.payment-qris', compact('order'));
    }

    public function confirmQris($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['status' => 'paid']); // Ubah status pesanan menjadi dibayar/menunggu konfirmasi admin

        $payment = Payment::where('order_id', $order->id)->first();
        if ($payment) {
            $payment->update(['status' => 'success']);
        }

        return redirect()->route('customer.orders.index')->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }
}
