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

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:vouchers,code|max:50',
            'value' => 'required|numeric|min:0|max:100',
            'min_purchase' => 'required|numeric|min:0',
        ], [
            'code.unique' => 'Kode voucher ini sudah ada!',
            'code.required' => 'Kode voucher wajib diisi.',
            'value.required' => 'Besar diskon wajib diisi.',
            'min_purchase.required' => 'Minimum belanja wajib diisi.',
        ]);

        Voucher::create([
            'code' => strtoupper($request->code),
            'name' => 'Diskon Promo ' . strtoupper($request->code),
            'type' => 'percent',
            'value' => $request->value,
            'min_purchase' => $request->min_purchase,
            'expired_at' => now()->addMonth(),
        ]);

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher baru berhasil dibuat.');
    }

    public function toggleStatus($id)
    {
        $voucher = Voucher::findOrFail($id);
        return redirect()->back()->with('success', 'Status voucher diperbarui.');
    }

    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher berhasil dihapus.');
    }
}
