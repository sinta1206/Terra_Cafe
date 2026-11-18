<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class PublicController extends Controller
{

    public function index()
    {

        $featuredProducts = Product::where('is_active', true)->take(4)->get();
        
        $categories = Category::all();

        return view('public.index', compact('featuredProducts', 'categories'));
    }


    public function menu(Request $request)
    {
        $query = Product::where('is_active', true);


        if ($request->has('category') && $request->category !== 'all') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }


        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(8);
        $categories = Category::all();
        $selectedCategory = $request->get('category', 'all');

        return view('public.menu', compact('products', 'categories', 'selectedCategory'));
    }

    // TAMBAHKAN METHOD INI UNTUK DETAIL PRODUK PUBLIK
    public function showProduct($id)
    {
        $product = Product::with('category')
            ->where('is_active', true)
            ->findOrFail($id);

        return view('public.product-detail', compact('product'));
    }

    public function about()
    {
        return view('public.about');
    }


    public function location()
    {
        return view('public.location');
    }
}
