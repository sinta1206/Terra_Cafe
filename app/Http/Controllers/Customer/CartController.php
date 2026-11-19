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
        $result = $this->storeCartItem($request, (int) $productId);

        if (!$result['ok']) {
            return redirect()->back()->withInput()->with('error', $result['message']);
        }

        return redirect()->route('customer.cart')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function buyNow(Request $request, $productId)
    {
        $result = $this->storeCartItem($request, (int) $productId);

        if (!$result['ok']) {
            return redirect()->back()->withInput()->with('error', $result['message']);
        }

        return redirect()->route('customer.checkout');
    }

    private function storeCartItem(Request $request, int $productId): array
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'temperature' => 'nullable|string',
            'size' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $product = Product::findOrFail($productId);

        if (!$product->is_active || $product->stock <= 0) {
            return [
                'ok' => false,
                'message' => 'Maaf, produk ini sedang habis atau tidak aktif.',
            ];
        }

        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
        $cart->load('items');

        $existingQty = $cart->items->where('product_id', $product->id)->sum('quantity');
        $totalRequestedQty = $existingQty + (int) $validated['quantity'];

        if ($totalRequestedQty > $product->stock) {
            return [
                'ok' => false,
                'message' => "Stok '{$product->name}' hanya tersisa {$product->stock} item (di keranjang sudah ada {$existingQty}).",
            ];
        }

        $finalPrice = $product->price;
        $sizeInput = (string) ($validated['size'] ?? '');

        if (stripos($sizeInput, 'large') !== false || stripos($sizeInput, 'besar') !== false) {
            $finalPrice += 3000;
        }

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => (int) $validated['quantity'],
            'price' => $finalPrice,
            'note' => 'Suhu: ' . ($validated['temperature'] ?? '-') . ', Ukuran: ' . ($validated['size'] ?? 'Regular') . '. Catatan: ' . (($validated['note'] ?? '') ?: '-'),
        ]);

        return ['ok' => true];
    }

    public function updateQuantity(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer',
        ]);

        $item = CartItem::with('cart.items', 'product')->findOrFail($itemId);

        if ($request->quantity <= 0) {
            $item->delete();
            return redirect()->route('customer.cart')->with('success', 'Produk berhasil dihapus dari keranjang.');
        }

        $product = Product::findOrFail($item->product_id);

        if (!$product->is_active || $product->stock <= 0) {
            return redirect()->route('customer.cart')->with('error', "Produk '{$product->name}' sedang habis.");
        }

        $otherItemsQty = $item->cart->items
            ->where('product_id', $product->id)
            ->where('id', '!=', $item->id)
            ->sum('quantity');

        if ($otherItemsQty + $request->quantity > $product->stock) {
            return redirect()->route('customer.cart')->with(
                'error',
                "Jumlah melebihi stok tersedia untuk '{$product->name}' (maksimal stok: {$product->stock})."
            );
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