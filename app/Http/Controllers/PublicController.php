<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class PublicController extends Controller
{
    /**
     * Menampilkan halaman utama (Landing Page / Beranda Publik)
     * Memuat produk unggulan/favorit, kategori, dan informasi operasional kafe.
     */
    public function index()
    {
        // Menampilkan produk unggulan/menu andalan (misal: 4 produk teratas atau berstatus aktif)
        $featuredProducts = Product::where('is_active', true)->take(4)->get();

        // Menampilkan semua kategori untuk navigasi cepat di beranda
        $categories = Category::all();

        return view('public.index', compact('featuredProducts', 'categories'));
    }

    /**
     * Menampilkan halaman katalog menu lengkap untuk publik
     * Dilengkapi fitur filter berdasarkan kategori (Kopi, Non-Kopi, Makanan, Camilan)
     */
    public function menu(Request $request)
    {
        $query = Product::where('is_active', true);

        // Filter menu berdasarkan kategori jika dipilih oleh pengguna
        if ($request->has('category') && $request->category !== 'all') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Fitur pencarian nama produk jika diinputkan
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(8);
        $categories = Category::all();
        $selectedCategory = $request->get('category', 'all');

        return view('public.menu', compact('products', 'categories', 'selectedCategory'));
    }

    /**
     * Menampilkan halaman "Tentang Kami" (About Us)
     */
    public function about()
    {
        return view('public.about');
    }

    /**
     * Menampilkan halaman "Lokasi" / Informasi Cafe
     */
    public function location()
    {
        return view('public.location');
    }
}
