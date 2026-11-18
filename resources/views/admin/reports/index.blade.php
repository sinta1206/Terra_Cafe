@extends('layouts.admin')

@section('title', 'Laporan Penjualan - TERRA Cafe')
@section('header-title', 'Laporan Penjualan')

@section('content')
<div class="space-y-6">

    <!-- HEADER & TOMBOL EKSPOR CSV -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <span class="text-[10px] font-bold tracking-widest text-gray-400 uppercase block">ANALISIS SEDERHANA</span>
            <h1 class="text-2xl font-bold text-[#2C2623]">Laporan penjualan</h1>
            <p class="text-xs text-gray-500 mt-0.5">Ringkasan transaksi dan akumulasi produk terjual.</p>
        </div>
        <a href="{{ route('admin.reports.csv') }}" class="inline-flex items-center justify-center space-x-2 bg-white hover:bg-gray-50 text-[#2C2623] border border-gray-200 px-4 py-2.5 rounded-xl text-xs font-medium transition shadow-xs w-fit">
            <i class="fa-solid fa-download text-[10px]"></i>
            <span>Ekspor CSV</span>
        </a>
    </div>

    <!-- 4 KARTU STATISTIK UTAMA -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1 -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-xs text-gray-500 font-medium">Total nilai pesanan</span>
            <div class="mt-4">
                <h3 class="text-xl font-bold text-[#2C2623]">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-gray-400 mt-0.5">Termasuk pesanan diproses</p>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-xs text-gray-500 font-medium">Transaksi selesai</span>
            <div class="mt-4">
                <h3 class="text-xl font-bold text-[#2C2623]">{{ $completedTransactionsCount ?? 0 }}</h3>
                <p class="text-[11px] text-gray-400 mt-0.5">Pesanan berstatus selesai</p>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-xs text-gray-500 font-medium">Produk terjual</span>
            <div class="mt-4">
                <h3 class="text-xl font-bold text-[#2C2623]">{{ $totalItemsSold ?? 0 }} item</h3>
                <p class="text-[11px] text-gray-400 mt-0.5">Total akumulasi item</p>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-xs text-gray-500 font-medium">Rata-rata pesanan</span>
            <div class="mt-4">
                <h3 class="text-xl font-bold text-[#2C2623]">Rp {{ number_format($averageOrderValue ?? 0, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-gray-400 mt-0.5">Per pesanan customer</p>
            </div>
        </div>
    </div>

    <!-- TABEL PRODUK TERJUAL TERLARIS -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
        <div class="p-6 pb-4 border-b border-gray-100">
            <h3 class="font-bold text-[#2C2623] text-sm">Produk terjual terlaris</h3>
        </div>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/50 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                    <th class="p-4 pl-6">Produk</th>
                    <th class="p-4">Kategori</th>
                    <th class="p-4">Harga Satuan</th>
                    <th class="p-4">Jumlah Terjual</th>
                    <th class="p-4 pr-6 text-right">Total Penjualan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-xs text-gray-700">
                @forelse($topProducts ?? [] as $item)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-4 pl-6 font-semibold text-[#2C2623] flex items-center space-x-2">
                            <i class="fa-solid fa-mug-hot text-gray-400"></i>
                            <span>{{ $item->product->name ?? 'Produk' }}</span>
                        </td>
                        <td class="p-4 text-gray-500">{{ $item->product->category->name ?? '-' }}</td>
                        <td class="p-4 text-gray-700">Rp {{ number_format($item->product->price ?? 0, 0, ',', '.') }}</td>
                        <td class="p-4 text-gray-900 font-medium">{{ $item->total_qty }} item</td>
                        <td class="p-4 pr-6 text-right font-semibold text-[#2C2623]">Rp {{ number_format($item->total_sales, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-gray-400">
                            <i class="fa-solid fa-chart-line text-3xl mb-2 block"></i>
                            Belum ada data penjualan produk.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <!-- Baris Total Keseluruhan -->
            <tfoot>
                <tr class="bg-gray-50/80 border-t border-gray-200 text-xs font-bold text-[#2C2623]">
                    <td colspan="3" class="p-4 pl-6 text-right uppercase text-gray-500 tracking-wider">Total Keseluruhan:</td>
                    <td class="p-4 text-gray-900">{{ $totalItemsSold ?? 0 }} item</td>
                    <td class="p-4 pr-6 text-right text-gray-900">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

</div>
@endsection
