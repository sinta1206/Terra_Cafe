@extends('layouts.app')

@section('title', 'Instruksi Pembayaran Tunai - TERRA Cafe')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <div class="bg-white border border-[#EAE5DF] rounded-[24px] p-8 shadow-sm space-y-6 text-center">
        
        <!-- Icon Sukses Pesanan Diterima -->
        <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-2xl shadow-inner">
            <i class="fa-solid fa-receipt"></i>
        </div>

        <!-- Header -->
        <div class="space-y-1">
            <span class="text-[10px] font-extrabold tracking-[3px] text-[#8C7A6B] uppercase">Pesanan Berhasil Dibuat</span>
            <h1 class="text-2xl font-extrabold text-[#2C2623]">Bayar Tunai di Kasir</h1>
            <p class="text-xs text-gray-500">Silakan tunjukkan nomor invoice ini kepada kasir saat mengambil pesanan di kafe.</p>
        </div>

        <!-- Info Invoice & Total -->
        <div class="bg-[#FAF8F5] border border-[#E5E0D8] rounded-2xl p-4 flex justify-between items-center text-xs">
            <div class="text-left">
                <span class="text-gray-400 block font-medium">No. Invoice</span>
                <span class="font-extrabold text-[#2C2623]">{{ $order->invoice_number }}</span>
            </div>
            <div class="text-right">
                <span class="text-gray-400 block font-medium">Total Bayar di Kasir</span>
                <span class="font-extrabold text-[#C49A6C] text-sm">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Instruksi Singkat -->
        <div class="text-xs text-gray-500 space-y-1 bg-amber-50 border border-amber-200 p-3 rounded-xl text-left">
            <p class="font-bold text-amber-800"><i class="fa-solid fa-circle-info mr-1"></i> Informasi:</p>
            <p class="text-[11px] text-amber-900 leading-relaxed">
                Pesanan Anda akan segera disiapkan oleh barista/dapur kami. Pembayaran dilakukan secara langsung menggunakan uang tunai ketika Anda tiba di kasir TERRA Cafe.
            </p>
        </div>

        <!-- Tombol Navigasi -->
        <div class="space-y-3 pt-2">
            <a href="{{ route('customer.orders.show', $order->id) }}" class="block w-full py-3.5 bg-[#4A5342] hover:bg-[#3B4235] text-white rounded-[20px] text-xs font-extrabold transition shadow-sm">
                Lihat Detail Pesanan &rarr;
            </a>

            <a href="{{ route('customer.dashboard') }}" class="block w-full py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-[20px] text-xs font-bold transition">
                Kembali ke Beranda
            </a>
        </div>

    </div>

</div>
@endsection