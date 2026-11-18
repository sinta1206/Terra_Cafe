@extends('layouts.app')

@section('title', $product->name . ' - TERRA Cafe')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <!-- Tombol Kembali -->
    <div class="mb-6">
        <a href="{{ route('public.menu') }}" class="text-xs font-bold text-gray-600 hover:text-black flex items-center space-x-1">
            <span>&larr; Kembali ke Menu</span>
        </a>
    </div>

    <div class="bg-white border border-gray-100 rounded-3xl p-6 sm:p-10 shadow-sm grid grid-cols-1 md:grid-cols-2 gap-10">

        <!-- Gambar Produk -->
        <div class="h-72 sm:h-96 bg-gray-100 rounded-2xl overflow-hidden flex items-center justify-center text-gray-400">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
                <span>Foto Produk</span>
            @endif
        </div>

        <!-- Informasi Produk & Opsi -->
        <div class="flex flex-col justify-between">
            <div>
                <span class="text-xs uppercase tracking-wider text-gray-500 font-semibold">{{ $product->category->name ?? 'Menu' }}</span>
                <h1 class="text-3xl font-bold text-[#2C2623] mt-1">{{ $product->name }}</h1>
                <p class="text-xl font-bold text-[#2C2623] mt-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-600 mt-4 leading-relaxed">{{ $product->description }}</p>
            </div>

            <!-- Simulasi Pilihan (Opsional untuk dilihat tamu) -->
            <div class="mt-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Pilihan Suhu</label>
                    <div class="flex space-x-3">
                        <div class="flex-1 border border-gray-200 rounded-xl p-2.5 text-center text-sm font-medium bg-gray-50 text-gray-700">Dingin (Ice)</div>
                        <div class="flex-1 border border-gray-200 rounded-xl p-2.5 text-center text-sm font-medium bg-gray-50 text-gray-700">Panas (Hot)</div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Ukuran Gelas</label>
                    <div class="flex space-x-3">
                        <div class="flex-1 border border-gray-200 rounded-xl p-2.5 text-center text-sm font-medium bg-gray-50 text-gray-700">Regular</div>
                        <div class="flex-1 border border-gray-200 rounded-xl p-2.5 text-center text-sm font-medium bg-gray-50 text-gray-700">Large (+Rp 3.000)</div>
                    </div>
                </div>
            </div>

            <!-- Box Peringatan Wajib Login untuk Memesan -->
            <div class="mt-8 pt-6 border-t border-gray-100">
                <div class="bg-amber-50 border border-amber-200 p-4 rounded-2xl mb-4 text-center">
                    <p class="text-xs text-amber-800 font-medium">Ingin memesan menu ini? Silakan masuk atau daftar akun terlebih dahulu.</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('login') }}" class="flex-1 py-3 bg-[#2C2623] text-white rounded-xl text-center font-medium text-sm hover:bg-black transition shadow-sm">
                        Masuk untuk Memesan
                    </a>
                    <a href="{{ route('register') }}" class="py-3 px-5 bg-gray-100 text-gray-800 rounded-xl text-center font-medium text-sm hover:bg-gray-200 transition">
                        Daftar
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection