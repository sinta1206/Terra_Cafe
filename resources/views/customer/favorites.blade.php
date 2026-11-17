@extends('layouts.app')

@section('title', 'Menu Favorit - TERRA Cafe')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Menu Favorit Anda</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        @forelse($favorites as $fav)
            <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="h-44 bg-gray-100 rounded-xl mb-4 overflow-hidden flex items-center justify-center text-gray-400">
                        @if($fav->product->image)
                            <img src="{{ asset('storage/' . $fav->product->image) }}" alt="{{ $fav->product->name }}" class="w-full h-full object-cover">
                        @else
                            <span>No Image</span>
                        @endif
                    </div>
                    <span class="text-xs text-gray-500 font-medium">{{ $fav->product->category->name ?? 'Menu' }}</span>
                    <h3 class="font-bold text-gray-900 text-base mt-1">{{ $fav->product->name }}</h3>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <span class="font-bold text-gray-900">Rp {{ number_format($fav->product->price, 0, ',', '.') }}</span>
                    <a href="{{ route('customer.products.show', $fav->product->id) }}" class="px-3 py-1.5 bg-gray-900 text-white text-xs rounded-lg font-medium hover:bg-black">Pesan</a>
                </div>
            </div>
        @empty
            <p class="text-gray-500 col-span-4 text-center py-12">Belum ada menu yang ditambahkan ke daftar favorit.</p>
        @endforelse
    </div>
</div>
@endsection
