<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Mengambil pesanan terbaru milik customer yang sedang login
        $recentOrders = Order::where('user_id', $user->id)
                             ->latest()
                             ->take(3)
                             ->get();

        // Mengambil kategori untuk filter menu cepat di dashboard
        $categories = Category::all();

        // Mengambil produk populer / terlaris atau produk aktif
        $popularProducts = Product::where('is_active', true)
                                  ->take(6)
                                  ->get();

        return view('customer.dashboard', compact('user', 'recentOrders', 'categories', 'popularProducts'));
    }
}
