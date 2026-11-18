<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
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
            'temperature' => 'nullable|string',
            'size' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $product = Product::findOrFail($productId);

        $finalPrice = $product->price;
        $sizeInput = (string) $request->input('size', '');

        if (stripos($sizeInput, 'large') !== false || stripos($sizeInput, 'besar') !== false) {
            $finalPrice += 3000;
        }

        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $productId,
            'quantity' => $request->quantity,
            'price' => $finalPrice,
            'note' => 'Suhu: ' . ($request->temperature ?? '-') . ', Ukuran: ' . ($request->size ?? 'Regular') . '. Catatan: ' . ($request->note ?: '-'),
        ]);

        return redirect()->route('customer.cart')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function updateQuantity(Request $request, $itemId)
    {
        $item = CartItem::findOrFail($itemId);
        
        if ($request->quantity <= 0) {
            $item->delete();
            return redirect()->route('customer.cart')->with('success', 'Produk berhasil dihapus dari keranjang.');
        }

        $item->quantity = $request->quantity;
        $item->save();

        return redirect()->route('customer.cart')->with('success', 'Jumlah produk diperbarui.');
    }

    public function removeItem($itemId)
    {
        $item = CartItem::findOrFail($itemId);
        $item->delete();

        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function applyVoucher(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $voucher = Voucher::where('code', strtoupper($request->code))
                          ->where(function($query) {
                              $query->whereNull('expired_at')
                                    ->orWhere('expired_at', '>', now());
                          })
                          ->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Kode voucher tidak valid atau sudah kedaluwarsa.']);
        }

        $cart = Cart::with('items.product')->where('user_id', auth()->id())->first();
        
        $subtotal = $cart->items->sum(function($i) {
            $isLarge = str_contains($i->note, 'Large') || str_contains($i->note, 'Besar');
            $price = $i->price > 0 ? $i->price : ($i->product->price + ($isLarge ? 3000 : 0));
            return $price * $i->quantity;
        });

        if ($subtotal < $voucher->min_purchase) {
            return response()->json([
                'success' => false, 
                'message' => 'Minimum belanja untuk voucher ini adalah Rp ' . number_format($voucher->min_purchase, 0, ',', '.')
            ]);
        }

        $discount = $voucher->type === 'percent' ? ($subtotal * $voucher->value) / 100 : $voucher->value;

        session([
            'applied_voucher' => $voucher->code,
            'voucher_discount' => $discount
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil diterapkan!',
            'discount' => $discount,
            'voucher_code' => $voucher->code
        ]);
    }
}