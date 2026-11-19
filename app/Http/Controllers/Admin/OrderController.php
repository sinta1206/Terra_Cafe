<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user', 'items.product', 'payment');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

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

    // Aksi tombol ubah status proses pesanan (pending/paid/processing/completed/cancelled)
    public function updateStatus(Request $request, $id)
    {
        $order = Order::with('payment')->findOrFail($id);

        // Kunci: jika pesanan sudah cancelled, admin tidak boleh mengubahnya lagi
        if ($order->status === 'cancelled') {
            return redirect()->back()
                ->with('error', 'Pesanan #' . $order->invoice_number . ' sudah dibatalkan dan tidak dapat diubah lagi.');
        }

        $request->validate([
            'status' => 'required|in:pending,paid,processing,completed,cancelled',
        ]);

        // Jika admin menandai pembayaran tunai lunas bersamaan atau pesanan diproses dari pending (cash)
        if ($request->status === 'completed' && optional($order->payment)->payment_gateway === 'cash' && optional($order->payment)->status === 'pending') {
            $order->payment->update(['status' => 'success']);
        }

        $order->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Status pesanan #' . $order->invoice_number . ' berhasil diperbarui.');
    }

    // Aksi khusus kasir untuk mengonfirmasi pembayaran tunai menjadi lunas
    public function updatePaymentStatus(Request $request, $id)
    {
        $order = Order::with('payment')->findOrFail($id);

        if ($order->status === 'cancelled') {
            return redirect()->back()
                ->with('error', 'Pesanan sudah dibatalkan, status pembayaran tidak dapat diubah.');
        }

        if ($order->payment) {
            $order->payment->update(['status' => 'success']);
        }

        // Jika pesanan masih pending setelah bayar di kasir, otomatis masuk ke processing
        if ($order->status === 'pending') {
            $order->update(['status' => 'processing']);
        }

        return redirect()->back()
            ->with('success', 'Pembayaran untuk invoice #' . $order->invoice_number . ' berhasil dikonfirmasi Lunas.');
    }
}
