<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:4', 'confirmed'], // Wajib cocok dengan password_confirmation
            'phone'    => ['required', 'string', 'max:15'],
        ]);

        // Simpan data user baru sebagai customer
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'customer',
            'phone'    => $request->phone,
        ]);

        // Otomatis buatkan keranjang belanja kosong untuk customer baru[cite: 2]
        Cart::create([
            'user_id' => $user->id,
        ]);

        // Tidak login otomatis; arahkan ke halaman login dulu
        return redirect()->route('login')
            ->with('success', 'Akun berhasil dibuat! Silakan masuk menggunakan email dan password Anda.');
    }
}
