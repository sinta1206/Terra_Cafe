@extends('layouts.app')

@section('title', 'Eksplorasi Rasa - TERRA Cafe')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header Title -->
    <div class="text-center max-w-2xl mx-auto mb-12">
        <h1 class="text-4xl font-extrabold text-[#2C2623]">Eksplorasi Rasa</h1>
        <p class="text-gray-600 mt-2 text-sm">Dari biji kopi pilihan hingga hidangan manis, temukan rasa yang cocok untuk menemani hari-harimu.</p>
    </div>

    <!-- Filter Kategori -->
    <div class="flex justify-center space-x-2 mb-10 overflow-x-auto pb-2">
        <a href="{{ route('public.menu', ['category' => 'all']) }}" class="px-5 py-2 rounded-full text-xs font-semibold {{ ($selectedCategory ?? 'all') == 'all' ? 'bg-[#2C2623] text-white' : 'bg-white text-gray-700 border border-gray-200' }}">Semua</a>
        @foreach($categories as $cat)
            <a href="{{ route('public.menu', ['category' => $cat->slug]) }}" class="px-5 py-2 rounded-full text-xs font-semibold {{ ($selectedCategory ?? '') == $cat->slug ? 'bg-[#2C2623] text-white' : 'bg-white text-gray-700 border border-gray-200' }}">{{ $cat->name }}</a>
        @endforeach
    </div>

    <!-- Grid Produk -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="bg-white border border-gray-100 rounded-3xl p-4 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="h-48 bg-gray-100 rounded-2xl mb-4 overflow-hidden flex items-center justify-center text-gray-400">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <span>No Image</span>
                        @endif
                    </div>
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">{{ $product->category->name ?? 'Menu' }}</span>
                    <h3 class="font-bold text-[#2C2623] text-base mt-0.5">{{ $product->name }}</h3>
                    <p class="text-xs font-extrabold text-[#2C2623] mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
                <div class="mt-6">
                    <a href="{{ route('public.product.detail', $product->id) }}" class="block w-full py-2.5 bg-[#FAF8F5] text-[#2C2623] text-center rounded-xl text-xs font-bold hover:bg-[#f0ebe1] transition border border-gray-200/60">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <p class="text-gray-500 col-span-4 text-center py-12">Belum ada menu yang tersedia pada kategori ini.</p>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-10">
        {{ $products->links() }}
    </div>
</div>
@endsection