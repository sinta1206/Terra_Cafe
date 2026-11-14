<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Voucher;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::with('product.category')->where('user_id', auth()->id())->get();
        return view('customer.favorites', compact('favorites'));
    }

    public function toggle($productId)
    {
        $userId = auth()->id();
        $favorite = Favorite::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($favorite) {
            $favorite->delete();
            return back()->with('success', 'Produk dihapus dari daftar favorit.');
        } else {
            Favorite::create(['user_id' => $userId, 'product_id' => $productId]);
            return back()->with('success', 'Produk ditambahkan ke daftar favorit.');
        }
    }
}
