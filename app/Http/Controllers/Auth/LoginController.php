<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman form login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Menangani proses autentikasi login.
     */
    public function login(Request $request)
    {
        // Validasi input sesuai dengan desain form login (email dan minimal 4 karakter password)
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:4'],
        ]);

        // Cek data login menggunakan Auth::attempt
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Pengecekan role setelah berhasil login
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/customer/dashboard');
        }

        // Jika gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Menangani proses logout pengguna.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
