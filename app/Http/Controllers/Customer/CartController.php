<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Voucher;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with('items.product')->where('user_id', auth()->id())->firstOrCreate(['user_id' => auth()->id()]);
        return view('customer.cart', compact('cart'));
    }

    public function add(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'temperature' => 'nullable|string', // Pilihan Suhu (Dingin/Panas)
            'size' => 'nullable|string',         // Ukuran Gelas (Regular/Large)
            'note' => 'nullable|string',         // Catatan untuk barista
        ]);

        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

        // Simpan atau gabungkan item ke keranjang
        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $productId,
            'quantity' => $request->quantity,
            'note' => 'Suhu: ' . ($request->temperature ?? '-') . ', Ukuran: ' . ($request->size ?? 'Regular') . '. Catatan: ' . $request->note,
        ]);

        return redirect()->route('customer.cart')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function updateQuantity(Request $request, $itemId)
    {
        $item = CartItem::findOrFail($itemId);
        $item->quantity = $request->quantity;
        $item->save();

        return response()->json(['success' => true, 'message' => 'Jumlah produk diperbarui.']);
    }

    public function removeItem($itemId)
    {
        $item = CartItem::findOrFail($itemId);
        $item->delete();

        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}
