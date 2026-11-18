@extends('layouts.app')

@section('title', 'Menu Favorit - TERRA Cafe')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <!-- Header Judul -->
    <div class="space-y-1">
        <span class="text-[10px] font-extrabold tracking-[3px] text-[#8C7A6B] uppercase">KOLEKSI PILIHANMU</span>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#2C2623] flex items-center gap-3">
            Menu Favorit Anda ❤️
        </h1>
    </div>

    <!-- Grid Menu Favorit -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($favorites as $fav)
            <div class="bg-white border border-[#EAE5DF] rounded-[24px] p-4 shadow-[0_4px_15px_rgba(44,38,35,0.03)] hover:shadow-[0_8px_25px_rgba(44,38,35,0.07)] hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                
                <div>
                    <!-- Foto Produk & Tombol Hapus/Toggle Favorit -->
                    <div class="h-44 rounded-[16px] overflow-hidden relative group bg-[#F5F2EE] border border-[#F0ECE7]">
                        @if($fav->product->image)
                            <img src="{{ asset('storage/' . $fav->product->image) }}" alt="{{ $fav->product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">
                                <i class="fa-solid fa-image text-2xl"></i>
                            </div>
                        @endif

                        <!-- Tombol Hapus dari Favorit -->
                        <form action="{{ route('customer.favorites.toggle', $fav->product->id) }}" method="POST" class="absolute top-2.5 right-2.5">
                            @csrf
                            <button type="submit" class="w-8 h-8 rounded-lg bg-white/95 shadow-md flex items-center justify-center text-red-500 hover:bg-white hover:scale-110 transition cursor-pointer" title="Hapus dari Favorit">
                                <i class="fa-solid fa-heart text-[10px]"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Informasi Produk -->
                    <div class="pt-3 px-1 space-y-1">
                        <span class="text-[9px] font-extrabold text-[#8C7A6B] uppercase tracking-widest block">
                            {{ $fav->product->category->name ?? 'Menu' }}
                        </span>
                        <h3 class="font-extrabold text-[#2C2623] text-sm leading-snug line-clamp-1">
                            {{ $fav->product->name }}
                        </h3>
                        <p class="text-[11px] text-gray-500 leading-relaxed line-clamp-2 min-h-[30px]">
                            {{ $fav->product->description }}
                        </p>
                    </div>
                </div>

                <!-- Harga dan Tombol Detail/Pesan -->
                <div class="mt-4 pt-3 border-t border-[#F0ECE7] flex items-center justify-between gap-3">
                    <div>
                        <span class="text-[9px] text-gray-400 block font-medium uppercase">Harga</span>
                        <span class="font-extrabold text-[#2C2623] text-sm">
                            Rp {{ number_format($fav->product->price, 0, ',', '.') }}
                        </span>
                    </div>

                    <a href="{{ route('customer.products.show', $fav->product->id) }}" class="inline-flex items-center gap-1 bg-[#2C2623] hover:bg-black text-white px-4 py-2 rounded-xl text-[11px] font-extrabold transition duration-300 shadow-2xs">
                        Lihat Menu
                        <i class="fa-solid fa-arrow-right text-[8px]"></i>
                    </a>
                </div>

            </div>
        @empty
            <div class="col-span-full bg-white border border-[#EAE5DF] rounded-[24px] p-14 text-center shadow-sm space-y-3">
                <i class="fa-solid fa-heart-crack text-4xl text-[#C9B49B]"></i>
                <h4 class="font-extrabold text-[#2C2623] text-base">Belum ada menu favorit</h4>
                <p class="text-xs text-gray-400 max-w-sm mx-auto">
                    Jelajahi katalog menu kami dan ketuk ikon hati pada produk yang Anda sukai untuk menyimpannya di sini.
                </p>
                <div class="pt-2">
                    <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center gap-2 bg-[#2C2623] text-white px-6 py-3 rounded-xl text-xs font-bold hover:bg-black transition shadow-sm">
                        Eksplorasi Menu &rarr;
                    </a>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection
