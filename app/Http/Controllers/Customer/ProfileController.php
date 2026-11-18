<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Voucher;
use App\Models\Order;
use App\Models\Favorite;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman utama profil.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Menggunakan try-catch agar aman jika struktur kolom voucher berbeda
        try {
            $activeVouchersCount = Voucher::count();
        } catch (\Exception $e) {
            $activeVouchersCount = 0;
        }

        return view('customer.profile.index', compact('user', 'activeVouchersCount'));
    }

    /**
     * Menampilkan halaman/form untuk mengedit profil.
     */
    public function edit()
    {
        $user = auth()->user();
        
        $ordersCount = Order::where('user_id', $user->id)->count();
        $favoritesCount = Favorite::where('user_id', $user->id)->count();

        return view('customer.profile.edit', compact('user', 'ordersCount', 'favoritesCount'));
    }

    /**
     * Menyimpan perubahan data profil yang dikirim dari form edit.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:15'],
            'address' => ['nullable', 'string', 'max:500'],
            'password' => ['nullable', 'string', 'min:4', 'confirmed'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;

        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('customer.profile.index')->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
