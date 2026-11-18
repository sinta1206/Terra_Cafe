@extends('layouts.admin')

@section('title', 'Kelola Voucher - TERRA Cafe')
@section('header-title', 'Kelola Voucher & Promo')

@section('content')
<div class="space-y-6">

    <!-- HEADER PROMOSI -->
    <div>
        <span class="text-[10px] font-bold tracking-widest text-gray-400 uppercase block">PROMOSI</span>
        <h1 class="text-2xl font-bold text-[#2C2623]">Kelola voucher</h1>
        <p class="text-xs text-gray-500 mt-0.5">Buat kode diskon untuk disimulasikan di checkout.</p>
    </div>

    <!-- CARD FORM BUAT VOUCHER -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-sm">
        <form action="{{ route('admin.vouchers.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Kode Voucher -->
                <div>
                    <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-1">KODE VOUCHER</label>
                    <input type="text" name="code" value="{{ old('code') }}" placeholder="CONTOH: HEMAT15" required class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:bg-white focus:ring-1 focus:ring-gray-300 transition uppercase">
                    @error('code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Diskon (%) -->
                <div>
                    <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-1">DISKON (%)</label>
                    <input type="number" name="value" value="{{ old('value') }}" placeholder="15" min="1" max="100" required class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:bg-white focus:ring-1 focus:ring-gray-300 transition">
                    @error('value')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <!-- Minimum Belanja -->
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-1">MINIMUM BELANJA (RP)</label>
                    <input type="number" name="min_purchase" value="{{ old('min_purchase') }}" placeholder="50000" min="0" required class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:bg-white focus:ring-1 focus:ring-gray-300 transition">
                    @error('min_purchase')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tombol Buat Voucher -->
                <div>
                    <button type="submit" class="w-full py-2.5 bg-[#2C2623] hover:bg-black text-white rounded-xl text-xs font-medium transition shadow-sm flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                        <span>Buat voucher</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- TABEL DAFTAR VOUCHER -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
        <div class="p-6 pb-4 border-b border-gray-100">
            <h3 class="font-bold text-[#2C2623] text-sm">Daftar voucher</h3>
        </div>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/50 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                    <th class="p-4 pl-6">Kode</th>
                    <th class="p-4">Nama Promo</th>
                    <th class="p-4">Diskon</th>
                    <th class="p-4">Minimum</th>
                    <th class="p-4 pr-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-xs text-gray-700">
                @forelse($vouchers as $voucher)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-4 pl-6 font-semibold text-[#2C2623]">{{ $voucher->code }}</td>
                        <td class="p-4 text-gray-700">{{ $voucher->name }}</td>
                        <td class="p-4 text-gray-900 font-bold">{{ $voucher->value }}%</td>
                        <td class="p-4 text-gray-500">Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }}</td>
                        <td class="p-4 pr-6 text-right space-x-2">
                            <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus voucher ini?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl font-medium text-[11px] border border-gray-200/60 transition">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-gray-400">
                            <i class="fa-solid fa-ticket text-3xl mb-2 block"></i>
                            Belum ada voucher yang dibuat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    @if($vouchers->hasPages())
        <div class="mt-4">
            {{ $vouchers->withQueryString()->links() }}
        </div>
    @endif

</div>
@endsection
