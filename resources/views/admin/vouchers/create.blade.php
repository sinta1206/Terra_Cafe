@extends('layouts.admin')

@section('title', 'Buat Voucher - TERRA Cafe')
@section('header-title', 'Buat Voucher Baru')

@section('content')
<div class="max-w-xl bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
    <form action="{{ route('admin.vouchers.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Kode Voucher</label>
            <input type="text" name="code" required placeholder="Contoh: HEMAT50" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Persentase Diskon (%)</label>
            <input type="number" name="value" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Minimum Belanja (Rp)</label>
            <input type="number" name="min_purchase" value="0" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
        </div>
        <button type="submit" class="w-full py-3 bg-gray-900 text-white rounded-xl font-medium hover:bg-black transition">Simpan Voucher</button>
    </form>
</div>
@endsection
