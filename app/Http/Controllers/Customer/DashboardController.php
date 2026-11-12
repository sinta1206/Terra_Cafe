<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $recentOrders = Order::where('user_id', $user->id)->latest()->take(3)->get();
        $popularProducts = Product::where('is_active', true)->take(4)->get();

        return view('customer.dashboard', compact('recentOrders', 'popularProducts'));
    }
}
