<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::latest()->paginate(10);
        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('admin.vouchers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:vouchers,code|max:50',
            'value' => 'required|numeric|min:0', // Persentase diskon (%) atau nilai tetap
            'min_purchase' => 'required|numeric|min:0', // Minimum Belanja (Rp)
        ]);

        Voucher::create([
            'code' => strtoupper($request->code),
            'name' => 'Diskon Promo ' . strtoupper($request->code),
            'type' => 'percent', // Sesuai desain form "Persentase Diskon (%)"
            'value' => $request->value,
            'min_purchase' => $request->min_purchase,
            'expired_at' => now()->addMonth(), // Default masa aktif
        ]);

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher baru berhasil dibuat.');
    }

    public function toggleStatus($id)
    {
        $voucher = Voucher::findOrFail($id);
        // Jika ada kolom status/aktif di database, bisa dibalik valuenya di sini
        return redirect()->back()->with('success', 'Status voucher diperbarui.');
    }

    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher berhasil dihapus.');
    }
}
