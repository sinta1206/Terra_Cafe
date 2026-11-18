@extends('layouts.app')

@section('title', $product->name . ' - TERRA Cafe')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <!-- Tombol Kembali -->
    <div>
        <a href="{{ route('customer.dashboard') }}" class="text-xs font-bold text-[#8C7A6B] hover:text-[#2C2623] flex items-center space-x-1 transition">
            <span>&larr; Kembali ke Beranda</span>
        </a>
    </div>

    <!-- Kotak Kontainer Utama -->
    <div class="bg-white border border-[#EAE5DF] rounded-[24px] p-6 sm:p-8 shadow-[0_4px_15px_rgba(44,38,35,0.03)] grid grid-cols-1 md:grid-cols-2 gap-8 items-start">

        <!-- Gambar Produk -->
        <div class="h-72 sm:h-80 bg-[#F5F2EE] border border-[#F0ECE7] rounded-[20px] overflow-hidden flex items-center justify-center text-gray-400">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
                <i class="fa-solid fa-mug-hot text-[#C9B49B] text-2xl"></i>
            @endif
        </div>

        <!-- Informasi & Form Pemesanan -->
        <div class="flex flex-col justify-between space-y-6">
            <div>
                <span class="text-[10px] uppercase tracking-[2px] text-[#8C7A6B] font-extrabold block">{{ $product->category->name ?? 'Menu' }}</span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#2C2623] mt-1">{{ $product->name }}</h1>
                <p class="text-xl font-extrabold text-[#2C2623] mt-1.5">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-500 mt-3 leading-relaxed">{{ $product->description }}</p>
            </div>

            <!-- Form Tambah ke Keranjang -->
            <form action="{{ route('customer.cart.add', $product->id) }}" method="POST" class="space-y-4 pt-2 border-t border-[#F0ECE7]">
                @csrf

                @php
                    // Deteksi apakah kategori produk adalah minuman/kopi
                    $categoryName = strtolower($product->category->name ?? '');
                    $isDrink = str_contains($categoryName, 'coffee') || str_contains($categoryName, 'kopi') || str_contains($categoryName, 'minuman') || str_contains($categoryName, 'beverage') || str_contains($categoryName, 'non-coffee');
                @endphp

                @if($isDrink)
                    <!-- Pilihan Suhu (Hanya untuk Minuman) -->
                    <div>
                        <label class="block text-xs font-extrabold text-[#2C2623] uppercase mb-1.5">Suhu</label>
                        <div class="flex space-x-3">
                            <label class="flex-1 border border-[#E5E0D8] bg-[#FAF8F5] rounded-xl p-2.5 text-center text-xs font-bold text-[#2C2623] cursor-pointer hover:border-[#2C2623] transition">
                                <input type="radio" name="temperature" value="Dingin" class="mr-1 accent-[#2C2623]" checked> Dingin (Ice)
                            </label>
                            <label class="flex-1 border border-[#E5E0D8] bg-[#FAF8F5] rounded-xl p-2.5 text-center text-xs font-bold text-[#2C2623] cursor-pointer hover:border-[#2C2623] transition">
                                <input type="radio" name="temperature" value="Panas" class="mr-1 accent-[#2C2623]"> Panas (Hot)
                            </label>
                        </div>
                    </div>

                    <!-- Pilihan Ukuran (Hanya untuk Minuman) -->
                    <div>
                        <label class="block text-xs font-extrabold text-[#2C2623] uppercase mb-1.5">Ukuran Gelas</label>
                        <div class="flex space-x-3">
                            <label class="flex-1 border border-[#E5E0D8] bg-[#FAF8F5] rounded-xl p-2.5 text-center text-xs font-bold text-[#2C2623] cursor-pointer hover:border-[#2C2623] transition">
                                <input type="radio" name="size" value="Regular" class="mr-1 accent-[#2C2623]" checked> Regular
                            </label>
                            <label class="flex-1 border border-[#E5E0D8] bg-[#FAF8F5] rounded-xl p-2.5 text-center text-xs font-bold text-[#2C2623] cursor-pointer hover:border-[#2C2623] transition">
                                <input type="radio" name="size" value="Large" class="mr-1 accent-[#2C2623]"> Large (+Rp 3.000)
                            </label>
                        </div>
                    </div>
                @endif

                <!-- Kuantitas -->
                <div>
                    <label class="block text-xs font-extrabold text-[#2C2623] uppercase mb-1.5">Jumlah</label>
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock ?? 99 }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-[#EAE5DF] bg-[#FAF8F5] text-xs font-bold text-[#2C2623] focus:outline-none focus:border-[#2C2623]">
                </div>

                <!-- Catatan untuk Barista / Dapur -->
                <div>
                    <label class="block text-xs font-extrabold text-[#2C2623] uppercase mb-1.5">Catatan untuk Barista / Dapur (Opsional)</label>
                    <input type="text" name="note" placeholder="Misal: Kurangi manis, es dikit..."
                        class="w-full px-4 py-2.5 rounded-xl border border-[#EAE5DF] bg-[#FAF8F5] text-xs font-medium text-[#2C2623] placeholder-gray-400 focus:outline-none focus:border-[#2C2623]">
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="w-full py-3.5 bg-[#2C2623] text-white rounded-[20px] text-xs font-extrabold hover:bg-black transition duration-300 shadow-sm cursor-pointer mt-2">
                    <i class="fa-solid fa-cart-plus mr-2"></i> Tambah ke Keranjang
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
