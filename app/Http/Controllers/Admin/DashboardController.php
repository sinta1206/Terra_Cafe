<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Ringkasan Utama (sesuai data pesanan & produk di database)
        $totalOrdersToday = Order::whereDate('created_at', today())->count();
        $totalAllOrders = Order::count();
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        $completedOrdersCount = Order::where('status', 'completed')->count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');

        // Produk dengan stok menipis (misalnya di bawah atau sama dengan 5)
        $lowStockProducts = Product::where('stock', '<=', 5)->orderBy('stock', 'asc')->get();
        $lowStockCount = $lowStockProducts->count();

        // Daftar pesanan terbaru untuk tabel ringkasan beranda
        $latestOrders = Order::with('user', 'payment')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalOrdersToday',
            'totalAllOrders',
            'pendingOrdersCount',
            'completedOrdersCount',
            'totalRevenue',
            'lowStockProducts',
            'lowStockCount',
            'latestOrders'
        ));
    }
}
