<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Ringkasan Utama
        $totalOrdersToday = Order::whereDate('created_at', today())->count();
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');

        // Produk dengan stok menipis (misalnya di bawah 5 atau sesuai desain "Chicken Katsu Rice 3 tersisa")
        $lowStockProducts = Product::where('stock', '<=', 5)->get();

        // Daftar pesanan terbaru untuk tabel ringkasan beranda
        $latestOrders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalOrdersToday',
            'pendingOrdersCount',
            'totalRevenue',
            'lowStockProducts',
            'latestOrders'
        ));
    }
}
