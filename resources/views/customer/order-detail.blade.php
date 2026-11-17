@extends('layouts.app')

@section('title', 'Detail Pesanan - TERRA Cafe')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex justify-between items-center border-b pb-4">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase">Detail Transaksi</span>
                <h1 class="text-xl font-bold text-gray-900 mt-0.5">Invoice: {{ $order->invoice_number }}</h1>
            </div>
            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 uppercase">
                {{ $order->status }}
            </span>
        </div>

        <div>
            <h3 class="font-bold text-gray-900 mb-3">Item Pesanan</h3>
            <div class="space-y-3">
                @foreach($order->items as $item)
                    <div class="flex justify-between text-sm">
                        <span>{{ $item->product->name }} (x{{ $item->quantity }})</span>
                        <span class="font-semibold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="border-t pt-4 space-y-2">
            <div class="flex justify-between text-sm text-gray-600">
                <span>Subtotal</span>
                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-base font-bold text-gray-900 pt-2 border-t">
                <span>Total Pembayaran</span>
                <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="pt-4 flex space-x-4">
            <form action="{{ route('customer.orders.reorder', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white rounded-xl text-sm font-medium hover:bg-black transition">
                    Pesan Lagi
                </button>
            </form>
            <a href="{{ route('customer.orders.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-200 transition">
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection
