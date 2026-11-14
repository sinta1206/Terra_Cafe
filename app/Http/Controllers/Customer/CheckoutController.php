<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Voucher;
use App\Models\Payment;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::with('items.product')->where('user_id', auth()->id())->first();
        if (!$cart || $cart->items->count() === 0) {
            return redirect()->route('customer.cart')->with('error', 'Keranjang Anda kosong.');
        }

        return view('customer.checkout', compact('cart'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'order_type' => 'required|in:delivery,take_away,dine_in',
            'address' => 'required_if:order_type,delivery|nullable|string',
            'phone' => 'required|string|max:15',
            'payment_method' => 'required|in:qris,cash',
        ]);

        $user = auth()->user();
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->count() === 0) {
            return redirect()->route('customer.cart');
        }

        // Hitung Subtotal
        $subtotal = $cart->items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // Biaya pengiriman (misalnya Rp 10.000 jika delivery, 0 jika take away/dine in)
        $shippingFee = $request->order_type === 'delivery' ? 10000 : 0;

        $discount = 0;
        $voucherId = null;

        if ($request->filled('voucher_code')) {
            $voucher = Voucher::where('code', $request->voucher_code)->first();
            if ($voucher && $subtotal >= $voucher->min_purchase) {
                $voucherId = $voucher->id;
                $discount = $voucher->type === 'percent'
                    ? ($subtotal * $voucher->value / 100)
                    : $voucher->value;
            }
        }

        $totalAmount = max(0, $subtotal - $discount) + $shippingFee;

        // Buat Pesanan Baru
        $order = Order::create([
            'invoice_number' => 'SJ' . rand(100000, 999999),
            'user_id' => $user->id,
            'voucher_id' => $voucherId,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
                'note' => $item->note,
            ]);
        }

        // Catat Pembayaran
        Payment::create([
            'order_id' => $order->id,
            'payment_gateway' => $request->payment_method,
            'amount' => $totalAmount,
            'status' => 'pending',
        ]);

        // Kosongkan keranjang
        $cart->items()->delete();

        if ($request->payment_method === 'qris') {
            return redirect()->route('customer.payment.qris', $order->id);
        }

        return redirect()->route('customer.orders.show', $order->id)->with('success', 'Pesanan berhasil dibuat!');
    }
}
