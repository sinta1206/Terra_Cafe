@extends('layouts.app')

@section('title', $product->name . ' - TERRA Cafe')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-8 shadow-sm grid grid-cols-1 md:grid-cols-2 gap-8">

        <!-- Gambar Produk -->
        <div class="h-72 sm:h-80 bg-gray-100 rounded-2xl overflow-hidden flex items-center justify-center text-gray-400">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
                <span>Foto Produk</span>
            @endif
        </div>

        <!-- Informasi & Form Pemesanan -->
        <div class="flex flex-col justify-between">
            <div>
                <span class="text-xs uppercase tracking-wider text-gray-500 font-semibold">{{ $product->category->name ?? 'Menu' }}</span>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">{{ $product->name }}</h1>
                <p class="text-xl font-bold text-gray-900 mt-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-600 mt-4 leading-relaxed">{{ $product->description }}</p>
            </div>

            <!-- Form Tambah ke Keranjang -->
            <form action="{{ route('customer.cart.add', $product->id) }}" method="POST" class="mt-6 space-y-4">
                @csrf

                <!-- Pilihan Suhu (jika kategori minuman) -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Suhu</label>
                    <div class="flex space-x-3">
                        <label class="flex-1 border border-gray-200 rounded-xl p-2.5 text-center text-sm font-medium cursor-pointer hover:border-black">
                            <input type="radio" name="temperature" value="Dingin" class="mr-1" checked> Dingin (Ice)
                        </label>
                        <label class="flex-1 border border-gray-200 rounded-xl p-2.5 text-center text-sm font-medium cursor-pointer hover:border-black">
                            <input type="radio" name="temperature" value="Panas" class="mr-1"> Panas (Hot)
                        </label>
                    </div>
                </div>

                <!-- Pilihan Ukuran -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Ukuran Gelas</label>
                    <div class="flex space-x-3">
                        <label class="flex-1 border border-gray-200 rounded-xl p-2.5 text-center text-sm font-medium cursor-pointer hover:border-black">
                            <input type="radio" name="size" value="Regular" class="mr-1" checked> Regular
                        </label>
                        <label class="flex-1 border border-gray-200 rounded-xl p-2.5 text-center text-sm font-medium cursor-pointer hover:border-black">
                            <input type="radio" name="size" value="Large" class="mr-1"> Large (+Rp 3.000)
                        </label>
                    </div>
                </div>

                <!-- Kuantitas -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Jumlah</label>
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
                </div>

                <!-- Catatan untuk Barista -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Catatan untuk Barista (Opsional)</label>
                    <input type="text" name="note" placeholder="Misal: Kurangi manis, es dikit..."
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="w-full py-3 bg-gray-900 text-white rounded-xl font-medium hover:bg-black transition shadow-sm">
                    <i class="fa-solid fa-cart-plus mr-2"></i> Tambah ke Keranjang
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
