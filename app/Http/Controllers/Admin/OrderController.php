<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user', 'items.product', 'payment');

        // Filter berdasarkan pencarian nomor pesanan / customer
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter berdasarkan status pesanan
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('user', 'items.product', 'payment', 'voucher')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    // Aksi tombol ubah status proses pesanan (pending/processing/completed/cancelled)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        try {
            $order = DB::transaction(function () use ($request, $id) {
                $order = Order::with('items')->lockForUpdate()->findOrFail($id);
                $oldStatus = $order->status;
                $newStatus = $request->status;

                if ($oldStatus === $newStatus) {
                    return $order;
                }

                // Jika dibatalkan (dari status selain cancelled), kembalikan stok produk
                if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                    foreach ($order->items as $item) {
                        $product = Product::lockForUpdate()->find($item->product_id);
                        if ($product) {
                            $product->increment('stock', $item->quantity);
                            if ($product->stock > 0 && !$product->is_active) {
                                $product->update(['is_active' => true]);
                            }
                        }
                    }
                }

                // Jika sebelumnya cancelled lalu diaktifkan kembali, kurangi stok lagi
                if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                    foreach ($order->items as $item) {
                        $product = Product::lockForUpdate()->find($item->product_id);
                        if (!$product || $product->stock < $item->quantity) {
                            $name = $product->name ?? 'Produk';
                            $stock = $product->stock ?? 0;
                            throw new \RuntimeException(
                                "Stok '{$name}' tidak cukup untuk mengaktifkan kembali pesanan (sisa: {$stock})."
                            );
                        }

                        $product->decrement('stock', $item->quantity);
                        $product->refresh();
                        if ($product->stock <= 0) {
                            $product->update(['is_active' => false]);
                        }
                    }
                }

                $order->update(['status' => $newStatus]);

                return $order;
            });
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Status pesanan #' . $order->invoice_number . ' berhasil diperbarui.');
    }

    // Aksi khusus kasir untuk mengonfirmasi pembayaran tunai menjadi lunas
    public function updatePaymentStatus(Request $request, $id)
    {
        $order = Order::with('payment')->findOrFail($id);

        if ($order->payment) {
            $order->payment->update(['status' => 'success']);
        }

        return redirect()->back()->with('success', 'Pembayaran untuk invoice #' . $order->invoice_number . ' berhasil dikonfirmasi Lunas.');
    }
}