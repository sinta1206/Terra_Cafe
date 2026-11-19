@extends('layouts.app')

@section('title', $product->name . ' - TERRA Cafe')

@section('content')
@php
    $cleanImage = $product->image
        ? preg_replace('#^(public/|storage/)#', '', ltrim($product->image, '/'))
        : null;
@endphp
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <!-- Tombol Kembali -->
    <div class="mb-6">
        <a href="{{ route('public.menu') }}" class="text-xs font-bold text-gray-600 hover:text-black flex items-center space-x-1">
            <span>&larr; Kembali ke Menu</span>
        </a>
    </div>

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-xs font-medium">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white border border-gray-100 rounded-3xl p-6 sm:p-10 shadow-sm grid grid-cols-1 md:grid-cols-2 gap-10">

        <!-- Gambar Produk -->
        <div class="h-72 sm:h-96 bg-gray-100 rounded-2xl overflow-hidden flex items-center justify-center text-gray-400">
            @if($cleanImage)
                <img src="{{ asset('storage/' . $cleanImage) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
                <span>Foto Produk</span>
            @endif
        </div>

        <!-- Informasi Produk & Opsi -->
        <div class="flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between gap-2">
                    <span class="text-xs uppercase tracking-wider text-gray-500 font-semibold">{{ $product->category->name ?? 'Menu' }}</span>
                    <span class="text-xs font-semibold {{ $product->stock > 0 ? 'text-emerald-700' : 'text-red-600' }}">
                        {{ $product->stock > 0 ? 'Stok: ' . $product->stock : 'Habis' }}
                    </span>
                </div>
                <h1 class="text-3xl font-bold text-[#2C2623] mt-1">{{ $product->name }}</h1>
                <p class="text-xl font-bold text-[#2C2623] mt-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-600 mt-4 leading-relaxed">{{ $product->description }}</p>
            </div>

            @auth
                @if(auth()->user()->role === 'customer')
                    <form id="productOrderForm" action="{{ route('customer.cart.add', $product->id) }}" method="POST" class="mt-6 space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Pilihan Suhu</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="temperature" value="Ice" class="peer sr-only" {{ old('temperature', 'Ice') === 'Ice' ? 'checked' : '' }}>
                                    <div class="border border-gray-200 peer-checked:border-[#2C2623] peer-checked:bg-[#2C2623] peer-checked:text-white rounded-xl p-2.5 text-center text-sm font-medium bg-gray-50 text-gray-700 transition">
                                        Dingin (Ice)
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="temperature" value="Hot" class="peer sr-only" {{ old('temperature') === 'Hot' ? 'checked' : '' }}>
                                    <div class="border border-gray-200 peer-checked:border-[#2C2623] peer-checked:bg-[#2C2623] peer-checked:text-white rounded-xl p-2.5 text-center text-sm font-medium bg-gray-50 text-gray-700 transition">
                                        Panas (Hot)
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Ukuran Gelas</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="size" value="Regular" class="peer sr-only" {{ old('size', 'Regular') === 'Regular' ? 'checked' : '' }}>
                                    <div class="border border-gray-200 peer-checked:border-[#2C2623] peer-checked:bg-[#2C2623] peer-checked:text-white rounded-xl p-2.5 text-center text-sm font-medium bg-gray-50 text-gray-700 transition">
                                        Regular
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="size" value="Large" class="peer sr-only" {{ old('size') === 'Large' ? 'checked' : '' }}>
                                    <div class="border border-gray-200 peer-checked:border-[#2C2623] peer-checked:bg-[#2C2623] peer-checked:text-white rounded-xl p-2.5 text-center text-sm font-medium bg-gray-50 text-gray-700 transition">
                                        Large (+Rp 3.000)
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Jumlah</label>
                                <input type="number" name="quantity" min="1" max="{{ max(1, $product->stock) }}" value="{{ old('quantity', 1) }}" required class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm text-[#2C2623] focus:outline-none focus:border-[#2C2623]">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Catatan (Opsional)</label>
                                <input type="text" name="note" value="{{ old('note') }}" placeholder="Contoh: less sugar" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm text-[#2C2623] focus:outline-none focus:border-[#2C2623]">
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row gap-3">
                            <button type="submit"
                                    formaction="{{ route('customer.cart.add', $product->id) }}"
                                    class="flex-1 py-3 px-4 bg-gray-100 hover:bg-gray-200 text-[#2C2623] rounded-xl text-center font-semibold text-sm transition {{ (!$product->is_active || $product->stock <= 0) ? 'opacity-50 pointer-events-none' : '' }}">
                                + Keranjang
                            </button>
                            <button type="submit"
                                    formaction="{{ route('customer.products.buy-now', $product->id) }}"
                                    class="flex-1 py-3 px-4 bg-[#2C2623] hover:bg-black text-white rounded-xl text-center font-semibold text-sm transition shadow-sm {{ (!$product->is_active || $product->stock <= 0) ? 'opacity-50 pointer-events-none' : '' }}">
                                Checkout Langsung
                            </button>
                        </div>
                    </form>
                @else
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <div class="bg-gray-50 border border-gray-200 p-4 rounded-2xl text-center">
                            <p class="text-xs text-gray-600 font-medium">Pemesanan menu hanya tersedia untuk akun customer.</p>
                        </div>
                    </div>
                @endif
            @else
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
            @endauth

        </div>

    </div>
</div>
@endsection