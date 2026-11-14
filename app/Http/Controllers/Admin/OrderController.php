<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user', 'items.product');

        // Filter berdasarkan pencarian nomor pesanan / customer
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        // Filter berdasarkan status pesanan (Semua status / pending / processing / completed)
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

    // Aksi tombol "Terima Pesanan" atau "Tandai Selesai" dari halaman Kelola Pesanan
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,paid,processing,completed,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status pesanan #' . $order->invoice_number . ' berhasil diperbarui.');
    }
}
