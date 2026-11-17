@extends('layouts.admin')

@section('title', 'Beranda Admin - TERRA Cafe')
@section('header-title', 'Beranda Admin')

@section('content')
<div class="space-y-6">
    <!-- Statistik Ringkasan -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white border border-gray-100 p-6 rounded-2xl shadow-sm">
            <span class="text-xs font-semibold text-gray-500 uppercase">Pesanan Hari Ini</span>
            <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $totalOrdersToday }}</h3>
        </div>
        <div class="bg-white border border-gray-100 p-6 rounded-2xl shadow-sm">
            <span class="text-xs font-semibold text-gray-500 uppercase">Perlu Ditindaklanjuti</span>
            <h3 class="text-2xl font-bold text-yellow-600 mt-1">{{ $pendingOrdersCount }} Pesanan</h3>
        </div>
        <div class="bg-white border border-gray-100 p-6 rounded-2xl shadow-sm">
            <span class="text-xs font-semibold text-gray-500 uppercase">Total Pendapatan Selesai</span>
            <h3 class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Tabel Pesanan Terbaru -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="font-bold text-gray-900">Pesanan Terbaru Masuk</h2>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($latestOrders as $order)
                <div class="p-4 sm:p-6 flex justify-between items-center">
                    <div>
                        <span class="text-xs font-semibold text-gray-500">{{ $order->invoice_number }} - {{ $order->user->name ?? 'Tamu' }}</span>
                        <h4 class="font-bold text-gray-900 text-sm mt-0.5">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h4>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-50 text-yellow-700 uppercase">{{ $order->status }}</span>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="px-3 py-1.5 bg-gray-900 text-white text-xs rounded-lg font-medium">Detail</a>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500 text-sm">Belum ada pesanan terbaru.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection 
