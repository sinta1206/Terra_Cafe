@extends('layouts.app')

@section('title', 'Pesanan Saya - TERRA Cafe')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <!-- Header Judul -->
    <div class="space-y-1">
        <span class="text-[10px] font-extrabold tracking-[3px] text-[#8C7A6B] uppercase">STATUS & RIWAYAT</span>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#2C2623] flex items-center gap-3">
            Pesanan Anda 📦
        </h1>
    </div>

    @if($orders->count() > 0)
        <!-- Grid Tata Letak Ala Halaman Favorit -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($orders as $order)
                <div class="bg-white border border-[#EAE5DF] rounded-[24px] p-5 shadow-[0_4px_15px_rgba(44,38,35,0.03)] hover:shadow-[0_8px_25px_rgba(44,38,35,0.07)] hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                    
                    <div class="space-y-3">
                        <!-- Header Card: Badge Status & Tanggal -->
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-1 text-[10px] font-extrabold rounded-lg uppercase tracking-wider
                                @if($order->status == 'completed') bg-green-50 text-green-700 border border-green-200/60
                                @elseif($order->status == 'pending') bg-amber-50 text-amber-700 border border-amber-200/60
                                @elseif($order->status == 'processing') bg-blue-50 text-blue-700 border border-blue-200/60
                                @else bg-gray-50 text-gray-700 border border-gray-200/60 @endif">
                                {{ ucfirst($order->status) }}
                            </span>

                            <span class="text-[11px] text-gray-400 font-medium">
                                <i class="fa-regular fa-clock mr-1"></i>{{ $order->created_at->format('d M Y') }}
                            </span>
                        </div>

                        <!-- Info Nomor Invoice & Total -->
                        <div class="space-y-1 pt-1">
                            <span class="text-[9px] font-extrabold text-[#8C7A6B] uppercase tracking-widest block">
                                Invoice: {{ $order->invoice_number }}
                            </span>
                            <h3 class="font-extrabold text-[#2C2623] text-lg">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </h3>
                        </div>

                        <p class="text-[11px] text-gray-500 leading-relaxed">
                            Pesanan kafe Anda telah tercatat di sistem kami.
                        </p>
                    </div>

                    <!-- Tombol Detail -->
                    <div class="mt-6 pt-4 border-t border-[#F0ECE7]">
                        <a href="{{ route('customer.orders.show', $order->id) }}" class="w-full inline-flex items-center justify-center gap-1.5 bg-[#FAF8F5] hover:bg-[#2C2623] hover:text-white text-[#2C2623] py-2.5 rounded-xl text-xs font-extrabold transition duration-300 border border-[#E5E0D8] shadow-2xs">
                            <span>Lihat Detail Pesanan</span>
                            <i class="fa-solid fa-arrow-right text-[8px]"></i>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white border border-[#EAE5DF] rounded-[28px] p-16 text-center shadow-sm space-y-3">
            <i class="fa-solid fa-receipt text-4xl text-[#C9B49B]"></i>
            <h4 class="font-extrabold text-[#2C2623] text-base">Belum ada riwayat pesanan</h4>
            <p class="text-xs text-gray-400 max-w-sm mx-auto">
                Semua transaksi dan riwayat pemesanan kopi atau makanan Anda akan muncul di sini.
            </p>
            <div class="pt-3">
                <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center gap-2 bg-[#2C2623] text-white px-6 py-3 rounded-xl text-xs font-bold hover:bg-black transition shadow-sm">
                    Mulai Belanja &rarr;
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
