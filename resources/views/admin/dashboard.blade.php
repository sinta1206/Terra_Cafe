@extends('layouts.admin')

@section('title', 'Dashboard - TERRA Cafe')

@section('content')
<div class="space-y-6">

    <!-- HERO SECTION / KARTU SAMBUTAN -->
    <div class="bg-[#F3EFEA] border border-[#E5DFD7] rounded-3xl p-8 relative overflow-hidden shadow-sm">
        <div class="max-w-xl">
            <span class="text-[11px] font-bold tracking-widest text-gray-500 uppercase block mb-1">OVERVIEW - TERRA CAFE</span>
            <h1 class="text-3xl font-serif font-bold text-[#2C2623] mb-2">Selamat datang, Admin.</h1>
            <p class="text-sm text-gray-600 mb-6">Ringkasan operasional cafe dan aktivitas pesanan hari ini.</p>
            
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center space-x-2 bg-[#2C2623] text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-black transition shadow-sm">
                <span>Kelola produk</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>
    </div>

    <!-- 4 KOTAK STATISTIK (GRID STATS) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Pesanan -->
        <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-xs text-gray-500 font-medium">Total pesanan</span>
            <div class="my-3">
                <h3 class="text-3xl font-bold text-[#2C2623]">{{ $totalOrders ?? 24 }}</h3>
            </div>
            <span class="text-[11px] text-gray-400">Transaksi hari ini</span>
        </div>

        <!-- Menunggu Konfirmasi -->
        <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-xs text-gray-500 font-medium">Menunggu konfirmasi</span>
            <div class="my-3">
                <h3 class="text-3xl font-bold text-amber-600">{{ $pendingOrdersCount ?? 3 }}</h3>
            </div>
            <span class="text-[11px] text-amber-600/80 font-medium">Perlu ditindaklanjuti</span>
        </div>

        <!-- Pemasukan -->
        <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-xs text-gray-500 font-medium">Pemasukan</span>
            <div class="my-3">
                <h3 class="text-2xl font-bold text-[#2C2623]">Rp {{ number_format($totalRevenue ?? 842000, 0, ',', '.') }}</h3>
            </div>
            <span class="text-[11px] text-gray-400">Dari 21 pesanan selesai</span>
        </div>

        <!-- Stok Menipis -->
        <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-xs text-gray-500 font-medium">Stok menipis</span>
            <div class="my-3">
                <h3 class="text-3xl font-bold text-red-600">{{ $lowStockCount ?? 1 }}</h3>
            </div>
            <span class="text-[11px] text-red-500 font-medium">Perlu diperiksa</span>
        </div>
    </div>

    <!-- TABEL PESANAN TERBARU -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-5 flex items-center justify-between border-b border-gray-100">
            <h3 class="font-bold text-[#2C2623] text-base">Pesanan terbaru</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-gray-500 hover:text-black transition">Lihat semua pesanan</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                        <th class="py-3 px-6">Pesanan</th>
                        <th class="py-3 px-6">Customer</th>
                        <th class="py-3 px-6">Waktu</th>
                        <th class="py-3 px-6">Total</th>
                        <th class="py-3 px-6">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($recentOrders ?? [] as $order)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-6 font-medium text-[#2C2623]">#{{ $order->code ?? 'TR-8821' }}</td>
                            <td class="py-4 px-6 text-gray-600">{{ $order->user->name ?? 'Aditya Pratama' }}</td>
                            <td class="py-4 px-6 text-gray-500 text-xs">{{ $order->created_at ? $order->created_at->format('H:i') . ' WIB' : '10:15 WIB' }}</td>
                            <td class="py-4 px-6 font-medium text-[#2C2623]">Rp {{ number_format($order->total_price ?? 59000, 0, ',', '.') }}</td>
                            <td class="py-4 px-6">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                    Diproses
                                </span>
                            </td>
                        </tr>
                    @empty
                        <!-- Data Contoh Sesuai Gambar Jika Belum Ada Pesanan -->
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-6 font-medium text-[#2C2623]">#TR-8821</td>
                            <td class="py-4 px-6 text-gray-600">Aditya Pratama</td>
                            <td class="py-4 px-6 text-gray-500 text-xs">10:15 WIB</td>
                            <td class="py-4 px-6 font-medium text-[#2C2623]">Rp 59.000</td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700">Diproses</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-6 font-medium text-[#2C2623]">#TR-8820</td>
                            <td class="py-4 px-6 text-gray-600">Siti Rahma</td>
                            <td class="py-4 px-6 text-gray-500 text-xs">09:40 WIB</td>
                            <td class="py-4 px-6 font-medium text-[#2C2623]">Rp 35.000</td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">Selesai</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- BAGIAN BAWAH: AKSI CEPAT & STOK PERLU PERHATIAN -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pb-6">
        
        <!-- Aksi Cepat -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-sm">
            <h3 class="font-bold text-[#2C2623] text-sm mb-4">Aksi cepat</h3>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-xl text-xs font-medium border border-gray-200/60 transition">
                    Lihat semua pesanan
                </a>
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-xl text-xs font-medium border border-gray-200/60 transition">
                    Lihat semua produk
                </a>
                <a href="{{ route('admin.customers.index') }}" class="px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-xl text-xs font-medium border border-gray-200/60 transition">
                    Lihat semua customers
                </a>
            </div>
        </div>

        <!-- Stok Perlu Perhatian -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-sm">
            <h3 class="font-bold text-[#2C2623] text-sm mb-4">Stok perlu perhatian</h3>
            <div class="bg-red-50/60 border border-red-100 rounded-xl p-3.5 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-mug-hot text-red-400 text-sm"></i>
                    <span class="text-xs font-medium text-gray-800">Chicken Katsu Rice</span>
                </div>
                <span class="text-xs font-bold text-red-600 bg-white px-2.5 py-1 rounded-lg border border-red-100 shadow-2xs">3 tersisa</span>
            </div>
        </div>

    </div>

</div>
@endsection