<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\CartItem;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::where('user_id', auth()->id());

        // Filter tab status (Semua, Diproses, Selesai)
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10);
        return view('customer.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('items.product', 'payment', 'voucher')->where('user_id', auth()->id())->findOrFail($id);
        return view('customer.order-detail', compact('order'));
    }

    // Fitur "Pesan lagi" untuk memasukkan ulang item pesanan lama ke keranjang
    public function reorder($id)
    {
        $order = Order::with('items')->where('user_id', auth()->id())->findOrFail($id);
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

        foreach ($order->items as $item) {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'note' => $item->note,
            ]);
        }

        return redirect()->route('customer.cart')->with('success', 'Menu pesanan sebelumnya berhasil dimasukkan ke keranjang.');
    }
}
