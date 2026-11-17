@extends('layouts.app')

@section('title', 'Dashboard Customer - TERRA Cafe')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Sapaan / Header Welcome -->
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm mb-8 flex flex-col md:flex-row justify-between items-start md:items-center">
        <div>
            <span class="text-xs uppercase tracking-wider text-gray-500 font-semibold">Dashboard Pelanggan</span>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Halo, {{ $user->name }}! 👋</h1>
            <p class="text-sm text-gray-600 mt-1">Mau pesan apa hari ini di Terra Cafe?</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <a href="{{ route('customer.cart') }}" class="px-4 py-2 bg-gray-900 text-white rounded-xl text-sm font-medium hover:bg-black transition">
                <i class="fa-solid fa-cart-shopping mr-2"></i> Keranjang Saya
            </a>
        </div>
    </div>

    <!-- Pesanan Terbaru (Recent Orders) -->
    <div class="mb-10">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Pesanan Terbaru Anda</h2>
        @if($recentOrders->count() > 0)
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
                <div class="divide-y divide-gray-100">
                    @foreach($recentOrders as $order)
                        <div class="p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                            <div>
                                <span class="text-xs font-semibold text-gray-500">Invoice: {{ $order->invoice_number }}</span>
                                <h3 class="font-bold text-gray-900 text-base mt-0.5">Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h3>
                                <p class="text-xs text-gray-500 mt-1">Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div class="mt-3 sm:mt-0 flex items-center space-x-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                    @if($order->status == 'completed') bg-green-50 text-green-700
                                    @elseif($order->status == 'pending') bg-yellow-50 text-yellow-700
                                    @else bg-blue-50 text-blue-700 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                                <a href="{{ route('customer.orders.show', $order->id) }}" class="text-sm font-medium text-gray-900 hover:underline">Detail &rarr;</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white border border-gray-100 rounded-2xl p-8 text-center text-gray-500 text-sm">
                Belum ada riwayat pesanan. Yuk, buat pesanan pertamamu!
            </div>
        @endif
    </div>

    <!-- Menu Populer / Katalog Cepat -->
    <div>
        <h2 class="text-lg font-bold text-gray-900 mb-4">Menu Pilihan Untukmu</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($popularProducts as $product)
                <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="h-44 bg-gray-100 rounded-xl mb-4 overflow-hidden flex items-center justify-center text-gray-400">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <span>No Image</span>
                            @endif
                        </div>
                        <span class="text-xs text-gray-500 font-medium">{{ $product->category->name ?? 'Menu' }}</span>
                        <h3 class="font-bold text-gray-900 text-base mt-1">{{ $product->name }}</h3>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $product->description }}</p>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        <a href="{{ route('customer.products.show', $product->id) }}" class="px-3 py-1.5 bg-gray-900 text-white text-xs rounded-lg font-medium hover:bg-black transition">
                            Pesan
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
