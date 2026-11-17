@extends('layouts.admin')

@section('title', 'Laporan Penjualan - TERRA Cafe')
@section('header-title', 'Laporan & Rekapitulasi')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="bg-white border border-gray-100 p-6 rounded-2xl shadow-sm">
            <span class="text-xs font-semibold text-gray-500 uppercase">Total Pendapatan Selesai</span>
            <h3 class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white border border-gray-100 p-6 rounded-2xl shadow-sm">
            <span class="text-xs font-semibold text-gray-500 uppercase">Total Transaksi Selesai</span>
            <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $totalTransactions }} Transaksi</h3>
        </div>
    </div>

    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex justify-between items-center">
        <h2 class="font-bold text-gray-900">Riwayat Transaksi Selesai</h2>
        <a href="{{ route('admin.reports.csv') }}" class="px-4 py-2 bg-gray-900 text-white text-xs font-medium rounded-xl hover:bg-black">Ekspor CSV</a>
    </div>
</div>
@endsection
