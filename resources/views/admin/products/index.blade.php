@extends('layouts.admin')

@section('title', 'Kelola Produk - TERRA Cafe')
@section('header-title', 'Kelola Produk Menu')

@section('content')
<div class="space-y-6">

    <!-- HEADER & TOMBOL TAMBAH -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <span class="text-[10px] font-bold tracking-widest text-gray-400 uppercase block">KATALOG</span>
            <h1 class="text-2xl font-bold text-[#2C2623]">Kelola produk</h1>
            <p class="text-xs text-gray-500 mt-0.5">Tambah, edit, aktif/nonaktifkan, dan hapus menu.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center space-x-2 bg-[#2C2623] hover:bg-black text-white px-4 py-2.5 rounded-xl text-xs font-medium transition shadow-sm w-fit">
            <i class="fa-solid fa-plus text-[10px]"></i>
            <span>Tambah produk</span>
        </a>
    </div>

    <!-- BOX PENCARIAN & FILTER KATEGORI -->
    <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-sm">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-col md:flex-row items-center justify-between gap-3">
            
            <!-- Input Cari Produk -->
            <div class="relative w-full md:w-80">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                    <i class="fa-solid fa-search text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:bg-white focus:ring-1 focus:ring-gray-300 transition">
            </div>

            <!-- Filter Kategori -->
            <div class="w-full md:w-auto flex items-center space-x-2">
                <select name="category_id" onchange="this.form.submit()" class="w-full md:w-48 px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:bg-white transition cursor-pointer">
                    <option value="">Semua kategori</option>
                    @foreach($categories ?? [] as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @if(request('search') || request('category_id'))
                    <a href="{{ route('admin.products.index') }}" class="text-[11px] text-gray-500 hover:text-red-600 underline px-2">Reset</a>
                @endif
            </div>

        </form>
    </div>

    <!-- GRID KARTU PRODUK -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($products as $product)
            @php
                $cleanImage = $product->image
                    ? preg_replace('#^(public/|storage/)#', '', ltrim($product->image, '/'))
                    : null;
            @endphp
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden flex flex-col justify-between transition hover:shadow-md">
                
                <div>
                    <!-- Gambar & Badge Status -->
                    <div class="relative h-48 bg-gray-100 overflow-hidden">
                        @if($cleanImage)
                            <img src="{{ asset('storage/' . $cleanImage) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <i class="fa-solid fa-mug-hot text-3xl"></i>
                            </div>
                        @endif

                        <!-- Badge Status (Habis / Aktif / Off) -->
                        <div class="absolute top-3 right-3">
                            @if($product->stock <= 0)
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-white/90 backdrop-blur-sm text-red-600 shadow-xs">Habis</span>
                            @elseif($product->is_active)
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/90 backdrop-blur-sm text-white shadow-xs">Aktif</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-gray-800/80 backdrop-blur-sm text-white shadow-xs">Off</span>
                            @endif
                        </div>
                    </div>

                    <!-- Informasi Produk -->
                    <div class="p-5">
                        <div class="flex items-center justify-between text-[11px] text-gray-400 uppercase font-semibold mb-1">
                            <span>{{ $product->category->name ?? 'Kategori' }}</span>
                            <span>Stok: {{ $product->stock }}</span>
                        </div>
                        <h3 class="font-bold text-[#2C2623] text-base mb-1 truncate">{{ $product->name }}</h3>
                        <p class="text-sm font-semibold text-[#2C2623]">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Tombol Aksi Bawah (Edit, Off/On, Hapus) -->
                <div class="p-4 pt-0 grid grid-cols-3 gap-2 border-t border-gray-100 mt-2">
                    <!-- Tombol Edit -->
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="py-2 text-center bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-xl text-xs font-medium border border-gray-200/60 transition flex items-center justify-center space-x-1">
                        <i class="fa-solid fa-pen text-[10px]"></i>
                        <span>Edit</span>
                    </a>

                    <!-- Tombol Toggle Status On/Off -->
                    <form action="{{ route('admin.products.toggle', $product->id) }}" method="POST" class="w-full">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full py-2 text-center bg-gray-50 hover:bg-gray-100 {{ $product->is_active ? 'text-amber-700' : 'text-emerald-700' }} rounded-xl text-xs font-medium border border-gray-200/60 transition flex items-center justify-center space-x-1">
                            <i class="fa-solid {{ $product->is_active ? 'fa-eye-slash' : 'fa-eye' }} text-[10px]"></i>
                            <span>{{ $product->is_active ? 'Off' : 'Aktif' }}</span>
                        </button>
                    </form>

                    <!-- Tombol Hapus -->
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2 text-center bg-red-50 hover:bg-red-100 text-red-600 rounded-xl text-xs font-medium border border-red-200/60 transition flex items-center justify-center space-x-1">
                            <i class="fa-solid fa-trash text-[10px]"></i>
                            <span>Hapus</span>
                        </button>
                    </form>
                </div>

            </div>
        @empty
            <div class="col-span-3 py-12 text-center bg-white rounded-2xl border border-gray-200/80 shadow-sm">
                <i class="fa-solid fa-mug-hot text-gray-300 text-4xl mb-3"></i>
                <p class="text-sm font-medium text-gray-600">Belum ada produk yang ditemukan.</p>
                <p class="text-xs text-gray-400 mt-1">Coba cari dengan kata kunci lain atau tambahkan produk baru.</p>
            </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    @if($products->hasPages())
        <div class="mt-6">
            {{ $products->withQueryString()->links() }}
        </div>
    @endif

</div>
@endsection
