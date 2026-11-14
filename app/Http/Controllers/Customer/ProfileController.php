<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman utama profil.
     */
    public function index()
    {
        $user = auth()->user();
        return view('customer.profile.index', compact('user'));
    }

    /**
     * Menampilkan halaman/form untuk mengedit profil.
     */
    public function edit()
    {
        $user = auth()->user();
        return view('customer.profile.edit', compact('user'));
    }

    /**
     * Menyimpan perubahan data profil yang dikirim dari form edit.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:15'],
            'password' => ['nullable', 'string', 'min:4', 'confirmed'], // Opsional jika ingin ganti password
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;

        // Jika kolom password diisi, update password baru (minimal 4 karakter sesuai demo)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('customer.profile.index')->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
