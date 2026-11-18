@extends('layouts.app')

@section('title', 'Pembayaran QRIS - TERRA Cafe')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <div class="bg-white border border-[#EAE5DF] rounded-[24px] p-8 shadow-sm space-y-6 text-center">
        
        <!-- Header -->
        <div class="space-y-1">
            <span class="text-[10px] font-extrabold tracking-[3px] text-[#8C7A6B] uppercase">Selesaikan Pembayaran</span>
            <h1 class="text-2xl font-extrabold text-[#2C2623]">Scan QRIS</h1>
            <p class="text-xs text-gray-500">Gunakan aplikasi e-wallet atau M-Banking apa pun untuk memindai kode di bawah ini.</p>
        </div>

        <!-- Info Invoice & Total -->
        <div class="bg-[#FAF8F5] border border-[#E5E0D8] rounded-2xl p-4 flex justify-between items-center text-xs">
            <div class="text-left">
                <span class="text-gray-400 block font-medium">No. Invoice</span>
                <span class="font-extrabold text-[#2C2623]">{{ $order->invoice_number }}</span>
            </div>
            <div class="text-right">
                <span class="text-gray-400 block font-medium">Total Tagihan</span>
                <span class="font-extrabold text-[#C49A6C] text-sm">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Kotak Gambar QRIS (Simulasi QR Code) -->
        <div class="bg-white border-2 border-dashed border-[#E5E0D8] rounded-2xl p-6 inline-block mx-auto space-y-3">
            <div class="w-48 h-48 bg-[#F5F2EE] rounded-xl flex items-center justify-center mx-auto text-[#2C2623] font-bold text-xs shadow-inner">
                <div class="space-y-2 text-center">
                    <i class="fa-solid fa-qrcode text-6xl text-[#2C2623]"></i>
                    <p class="text-[10px] text-gray-500">QRIS TERRA CAFE</p>
                </div>
            </div>
            <span class="text-[10px] text-gray-400 block">NMID: ID1029384756789</span>
        </div>

        <!-- Instruksi Singkat -->
        <div class="text-xs text-gray-500 space-y-1 bg-amber-50 border border-amber-200 p-3 rounded-xl text-left">
            <p class="font-bold text-amber-800"><i class="fa-solid fa-circle-info mr-1"></i> Petunjuk Pembayaran:</p>
            <ol class="list-decimal list-inside space-y-0.5 text-[11px] text-amber-900">
                <li>Buka aplikasi BCA, Mandiri, GoPay, OVO, Dana, atau ShopeePay.</li>
                <li>Pilih menu <b>Scan / Bayar</b>.</li>
                <li>Arahkan kamera ke QR Code di atas dan pastikan nama merchant tertulis <b>TERRA Cafe</b>.</li>
                <li>Selesaikan transaksi sesuai total tagihan.</li>
            </ol>
        </div>

        <!-- Tombol Aksi Konfirmasi -->
        <div class="space-y-3 pt-2">
            <form action="{{ route('customer.payment.qris.confirm', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-3.5 bg-[#4A5342] hover:bg-[#3B4235] text-white rounded-[20px] text-xs font-extrabold transition shadow-sm cursor-pointer">
                    Saya Sudah Membayar &rarr;
                </button>
            </form>

            <a href="{{ route('customer.orders.index') }}" class="block w-full py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-[20px] text-xs font-bold transition">
                Cek Daftar Pesanan
            </a>
        </div>

    </div>

</div>
@endsection
