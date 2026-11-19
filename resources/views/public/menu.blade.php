@extends('layouts.app')

@section('title', 'Eksplorasi Rasa - TERRA Cafe')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    
    <!-- Header Title -->
    <div class="space-y-1">
        <span class="text-[10px] font-extrabold tracking-[3px] text-[#8C7A6B] uppercase">KATALOG KAMI</span>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#2C2623]">Eksplorasi Rasa</h1>
        <p class="text-xs text-gray-500 font-medium">Dari biji kopi pilihan hingga hidangan manis, temukan rasa yang cocok untuk menemani hari-harimu.</p>
    </div>

    <!-- Filter Kategori -->
    <div class="flex space-x-2 overflow-x-auto pb-2 scrollbar-none">
        <a href="{{ route('public.menu', ['category' => 'all']) }}" 
           class="px-5 py-2.5 rounded-full text-xs font-bold transition duration-300 border {{ ($selectedCategory ?? 'all') == 'all' ? 'bg-[#2C2623] text-white border-[#2C2623]' : 'bg-white text-gray-700 border-[#EAE5DF] hover:border-[#2C2623]' }}">
            Semua
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('public.menu', ['category' => $cat->slug]) }}" 
               class="px-5 py-2.5 rounded-full text-xs font-bold transition duration-300 border whitespace-nowrap {{ ($selectedCategory ?? '') == $cat->slug ? 'bg-[#2C2623] text-white border-[#2C2623]' : 'bg-white text-gray-700 border-[#EAE5DF] hover:border-[#2C2623]' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    <!-- Kotak Kontainer Utama -->
    <div class="bg-white border border-[#EAE5DF] rounded-[24px] p-6 sm:p-8 shadow-[0_4px_15px_rgba(44,38,35,0.03)] space-y-6">
        
        <!-- Grid Produk di Dalam Kontainer -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @forelse($products as $product)
                @php
                    $cleanImage = $product->image
                        ? preg_replace('#^(public/|storage/)#', '', ltrim($product->image, '/'))
                        : null;
                @endphp
                <div class="bg-[#FAF8F5] border border-[#EAE5DF] rounded-[20px] p-4 transition-all duration-300 hover:shadow-sm flex flex-col justify-between relative">
                    
                    <div>
                        <!-- Foto Produk & Tombol Favorit -->
                        <div class="h-44 bg-[#F5F2EE] border border-[#F0ECE7] rounded-[16px] mb-4 overflow-hidden flex items-center justify-center relative">
                            @if($cleanImage)
                                <img src="{{ asset('storage/' . $cleanImage) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <i class="fa-solid fa-mug-hot text-[#C9B49B] text-xl"></i>
                            @endif

                            <!-- Tombol Favorit (Hanya tampil & berfungsi jika user role customer yang login) -->
                            @auth
                                @if(auth()->user()->role === 'customer')
                                    @php
                                        $isFavorite = auth()->user()->favorites()->where('product_id', $product->id)->exists();
                                    @endphp
                                    <form action="{{ route('customer.favorites.toggle', $product->id) }}" method="POST" class="absolute top-3 right-3">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 rounded-full bg-white/80 backdrop-blur-xs border border-[#E5E0D8] flex items-center justify-center text-xs transition duration-300 hover:bg-white cursor-pointer shadow-xs" title="Kelola Favorit">
                                            <i class="fa-solid fa-heart {{ $isFavorite ? 'text-red-500' : 'text-gray-400' }}"></i>
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                        
                        <span class="text-[10px] text-[#8C7A6B] font-extrabold uppercase tracking-widest block">{{ $product->category->name ?? 'Menu' }}</span>
                        <h3 class="font-extrabold text-[#2C2623] text-sm md:text-base mt-1 line-clamp-1">{{ $product->name }}</h3>
                        <p class="text-xs font-extrabold text-[#2C2623] mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>

                    <!-- Tombol Aksi (Dinamis) -->
                    <div class="mt-5">
                        @auth
                            @if(auth()->user()->role === 'customer')
                                <a href="{{ route('customer.products.show', $product->id) }}" class="block w-full py-2.5 bg-white hover:bg-[#2C2623] text-[#2C2623] hover:text-white text-center rounded-xl text-xs font-extrabold transition duration-300 border border-[#E5E0D8]">
                                    Lihat Detail
                                </a>
                            @else
                                <a href="{{ route('public.product.detail', $product->id) }}" class="block w-full py-2.5 bg-white hover:bg-[#2C2623] text-[#2C2623] hover:text-white text-center rounded-xl text-xs font-extrabold transition duration-300 border border-[#E5E0D8]">
                                    Lihat Detail
                                </a>
                            @endif
                        @else
                            <a href="{{ route('public.product.detail', $product->id) }}" class="block w-full py-2.5 bg-white hover:bg-[#2C2623] text-[#2C2623] hover:text-white text-center rounded-xl text-xs font-extrabold transition duration-300 border border-[#E5E0D8]">
                                Lihat Detail
                            </a>
                        @endauth
                    </div>
                </div>
            @empty
                <div class="col-span-4 py-12 text-center space-y-3">
                    <div class="w-14 h-14 rounded-2xl bg-[#FAF8F5] border border-[#E5E0D8] flex items-center justify-center mx-auto text-[#C9B49B] text-xl">
                        <i class="fa-solid fa-mug-saucer"></i>
                    </div>
                    <p class="text-xs text-gray-500 font-medium">Belum ada menu yang tersedia pada kategori ini.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="pt-6 border-t border-[#F0ECE7] flex justify-center">
                {{ $products->links() }}
            </div>
        @endif

    </div>

</div>
@endsection