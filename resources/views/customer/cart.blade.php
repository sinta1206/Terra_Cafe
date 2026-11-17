@extends('layouts.app')

@section('title', 'Keranjang Belanja - TERRA Cafe')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Keranjang Belanja Anda</h1>

    @if($cart && $cart->items->count() > 0)
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden mb-6">
            <div class="divide-y divide-gray-100">
                @foreach($cart->items as $item)
                    <div class="p-4 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between">
                        <div class="flex items-center space-x-4 mb-4 sm:mb-0">
                            <div class="w-16 h-16 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0 flex items-center justify-center text-gray-400">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                @else
                                    <span>Foto</span>
                                @endif
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">{{ $item->product->name }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Catatan: {{ $item->note ?? '-' }}</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">Rp {{ number_format($item->product->price, 0, ',', '.') }} x {{ $item->quantity }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <span class="font-bold text-gray-900">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</span>
                            <form action="{{ route('customer.cart.remove', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col sm:flex-row justify-between items-center">
            <div>
                <p class="text-sm text-gray-500">Total Pembayaran</p>
                <h3 class="text-2xl font-bold text-gray-900">
                    Rp {{ number_format($cart->items->sum(fn($i) => $i->product->price * $i->quantity), 0, ',', '.') }}
                </h3>
            </div>
            <a href="{{ route('customer.checkout') }}" class="mt-4 sm:mt-0 px-6 py-3 bg-gray-900 text-white rounded-xl font-medium hover:bg-black transition shadow-sm">
                Lanjut ke Checkout &rarr;
            </a>
        </div>
    @else
        <div class="bg-white border border-gray-100 rounded-2xl p-12 text-center">
            <p class="text-gray-500 mb-4">Keranjang belanja Anda masih kosong.</p>
            <a href="{{ route('public.menu') }}" class="px-6 py-3 bg-gray-900 text-white rounded-xl text-sm font-medium hover:bg-black">Mulai Belanja</a>
        </div>
    @endif
</div>
@endsection
