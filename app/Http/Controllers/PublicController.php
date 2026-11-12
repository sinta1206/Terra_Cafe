<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class PublicController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_active', true)->take(6)->get();
        $categories = Category::all();

        return view('public.index', compact('featuredProducts', 'categories'));
    }

    public function menu(Request $request)
    {
        $query = Product::where('is_active', true);

        // Fitur filter berdasarkan kategori jika dipilih
        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $products = $query->paginate(8);
        $categories = Category::all();

        return view('public.menu', compact('products', 'categories'));
    }
}
