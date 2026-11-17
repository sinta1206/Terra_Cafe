@extends('layouts.app')

@section('title', 'Beranda - TERRA Cafe')

@section('content')
<!-- Hero Section -->
<div class="bg-white py-16 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between">
        <div class="md:w-1/2 mb-8 md:mb-0">
            <span class="text-xs uppercase tracking-widest bg-gray-100 px-3 py-1 rounded-full font-semibold text-gray-600">Ruang Nyaman & Kopi Pilihan</span>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mt-4 leading-tight">Nikmati Suasana & Cita Rasa Terbaik di Terra Cafe</h1>
            <p class="text-gray-600 mt-4 text-base leading-relaxed">Tempat bersantai terbaik untuk menikmati racikan kopi segar dan hidangan lezat bersama orang terdekat.</p>
            <div class="mt-6 flex space-x-4">
                <a href="{{ route('public.menu') }}" class="px-6 py-3 bg-gray-900 text-white rounded-xl font-medium hover:bg-gray-800 transition shadow-sm">Lihat Menu</a>
                <a href="{{ route('login') }}" class="px-6 py-3 bg-gray-100 text-gray-800 rounded-xl font-medium hover:bg-gray-200 transition">Masuk Akun</a>
            </div>
        </div>
        <div class="md:w-1/2 flex justify-center">
            <div class="w-full h-80 bg-gray-200 rounded-2xl flex items-center justify-center text-gray-400 font-medium">
                [Ilustrasi / Foto Cafe Terra]
            </div>
        </div>
    </div>
</div>

<!-- Menu Unggulan Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Menu Andalan Kami</h2>
        <a href="{{ route('public.menu') }}" class="text-sm font-medium text-gray-600 hover:text-black">Lihat Semua Menu &rarr;</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        @forelse($featuredProducts as $product)
            <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="h-48 bg-gray-100 rounded-xl mb-4 overflow-hidden flex items-center justify-center text-gray-400">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <span>No Image</span>
                        @endif
                    </div>
                    <span class="text-xs text-gray-500 font-medium">{{ $product->category->name ?? 'Menu' }}</span>
                    <h3 class="font-bold text-gray-900 text-base mt-1">{{ $product->name }}</h3>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <span class="font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    <a href="{{ route('login') }}" class="px-3 py-1.5 bg-gray-900 text-white text-xs rounded-lg font-medium hover:bg-black">Pesan</a>
                </div>
            </div>
        @empty
            <p class="text-gray-500 col-span-4 text-center py-8">Belum ada produk unggulan yang tersedia.</p>
        @endforelse
    </div>
</div>
@endsection
