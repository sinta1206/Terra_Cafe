<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        
        $categories = Category::all();
        
        // Load produk beserta relasi category dan favorites milik user yang sedang login
        $products = Product::with(['category', 'favorites' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->where('is_active', true)->take(12)->get();

        // Ambil data keranjang untuk widget sticky di kanan
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

        return view('customer.dashboard', compact('user', 'categories', 'products', 'cart'));
    }
}