<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\Payment;

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
            'order_type'     => 'required|in:delivery,take_away,dine_in',
            'address'        => 'required_if:order_type,delivery|nullable|string',
            'phone'          => 'required|string|max:15',
            'payment_method' => 'required|in:midtrans,qris,cash',
        ]);

        $user = auth()->user();
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->count() === 0) {
            return redirect()->route('customer.cart');
        }

        try {
            $order = DB::transaction(function () use ($request, $user, $cart) {
                // Kunci dan cek stok semua produk di keranjang terlebih dahulu
                $lockedProducts = [];
                foreach ($cart->items as $item) {
                    $product = Product::lockForUpdate()->find($item->product_id);

                    if (!$product || !$product->is_active || $product->stock < $item->quantity) {
                        $available = $product ? $product->stock : 0;
                        $productName = $item->product->name ?? ($product->name ?? 'Produk');
                        throw new \RuntimeException(
                            "Stok produk '{$productName}' tidak mencukupi (tersisa: {$available})."
                        );
                    }

                    $lockedProducts[$item->id] = $product;
                }

                // Hitung Subtotal dengan mempertimbangkan harga final item (termasuk Large +3000)
                $subtotal = $cart->items->sum(function ($item) {
                    $basePrice = $item->price > 0
                        ? $item->price
                        : (($item->product->price ?? 0) + (str_contains((string) $item->note, 'Large') ? 3000 : 0));

                    return $basePrice * $item->quantity;
                });

                // Biaya pengiriman: Rp 0 jika take_away atau dine_in, Rp 10.000 jika delivery
                $shippingFee = ($request->order_type === 'take_away' || $request->order_type === 'dine_in') ? 0 : 10000;

                // Ambil diskon dari session yang sudah diterapkan di keranjang
                $discount = (float) session('voucher_discount', 0);
                $voucherCode = session('applied_voucher');
                $voucherId = null;

                if ($voucherCode) {
                    $voucher = Voucher::where('code', $voucherCode)->first();
                    if ($voucher) {
                        $voucherId = $voucher->id;
                    }
                }

                $totalAmount = max(0, $subtotal - $discount) + $shippingFee;

                // Buat Pesanan Baru
                $order = Order::create([
                    'invoice_number' => 'SJ' . rand(100000, 999999),
                    'user_id'        => $user->id,
                    'voucher_id'     => $voucherId,
                    'subtotal'       => $subtotal,
                    'discount'       => $discount,
                    'total_amount'   => $totalAmount,
                    'status'         => 'pending',
                ]);

                foreach ($cart->items as $item) {
                    $itemFinalPrice = $item->price > 0
                        ? $item->price
                        : (($item->product->price ?? 0) + (str_contains((string) $item->note, 'Large') ? 3000 : 0));

                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $item->product_id,
                        'quantity'   => $item->quantity,
                        'price'      => $itemFinalPrice,
                        'note'       => $item->note,
                    ]);

                    // Kurangi stok produk yang sudah dikunci
                    $lockedProducts[$item->id]->decrement('stock', $item->quantity);
                }

                // Catat Pembayaran
                Payment::create([
                    'order_id'        => $order->id,
                    'payment_gateway' => $request->payment_method,
                    'amount'          => $totalAmount,
                    'status'          => 'pending',
                ]);

                // Kosongkan keranjang & bersihkan session voucher jika ada
                $cart->items()->delete();
                session()->forget(['voucher_discount', 'applied_voucher']);

                return $order;
            });
        } catch (\RuntimeException $e) {
            return redirect()->route('customer.cart')->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memproses pesanan.');
        }

        if (in_array($request->payment_method, ['midtrans', 'qris'])) {
            return redirect()->route('customer.payment.qris', $order->id);
        }

        return redirect()->route('customer.orders.show', $order->id)
            ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran tunai di kasir.');
    }
}
