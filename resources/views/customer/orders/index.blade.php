@extends('layouts.app')

@section('title', 'Pesanan Saya - TERRA Cafe')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Riwayat Pesanan Anda</h1>

    @if($orders->count() > 0)
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <div>
                        <span class="text-xs font-semibold text-gray-500">Invoice: {{ $order->invoice_number }}</span>
                        <h3 class="font-bold text-gray-900 text-base mt-0.5">Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h3>
                        <p class="text-xs text-gray-500 mt-1">Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="mt-4 sm:mt-0 flex items-center space-x-4">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                            @if($order->status == 'completed') bg-green-50 text-green-700
                            @elseif($order->status == 'pending') bg-yellow-50 text-yellow-700
                            @else bg-blue-50 text-blue-700 @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                        <a href="{{ route('customer.orders.show', $order->id) }}" class="px-4 py-2 bg-gray-900 text-white text-xs rounded-xl font-medium hover:bg-black">Detail</a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white border border-gray-100 rounded-2xl p-12 text-center text-gray-500">
            Belum ada riwayat pesanan.
        </div>
    @endif
</div>
@endsection
