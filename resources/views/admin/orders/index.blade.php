@extends('layouts.admin')

@section('title', 'Kelola Pesanan - TERRA Cafe')
@section('header-title', 'Kelola Pesanan Masuk')

@section('content')
<div class="space-y-6">

    <!-- HEADER TRANSAKSI -->
    <div>
        <span class="text-[10px] font-bold tracking-widest text-gray-400 uppercase block">TRANSAKSI</span>
        <h1 class="text-2xl font-bold text-[#2C2623]">Kelola pesanan</h1>
        <p class="text-xs text-gray-500 mt-0.5">Periksa detail dan ubah status pesanan customer.</p>
    </div>

    <!-- BOX PENCARIAN & FILTER STATUS -->
    <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-sm">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col md:flex-row items-center justify-between gap-3">

            <!-- Input Cari Invoice / Customer -->
            <div class="relative w-full md:w-80">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                    <i class="fa-solid fa-search text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor invoice / customer..." class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:bg-white focus:ring-1 focus:ring-gray-300 transition">
            </div>

            <!-- Filter Status -->
            <div class="w-full md:w-auto flex items-center space-x-2">
                <select name="status" onchange="this.form.submit()" class="w-full md:w-48 px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:bg-white transition cursor-pointer">
                    <option value="all">Semua status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @if(request('search') || (request('status') && request('status') != 'all'))
                    <a href="{{ route('admin.orders.index') }}" class="text-[11px] text-gray-500 hover:text-red-600 underline px-2">Reset</a>
                @endif
            </div>

        </form>
    </div>

    <!-- TABEL KELOLA PESANAN -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/50 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                    <th class="p-4 pl-6">Invoice</th>
                    <th class="p-4">Pelanggan</th>
                    <th class="p-4">Waktu</th>
                    <th class="p-4">Total</th>
                    <th class="p-4">Status Pesanan</th>
                    <th class="p-4">Metode</th> <!-- Kolom Metode Pembayaran Terpisah -->
                    <th class="p-4">Status Pembayaran</th>
                    <th class="p-4 pr-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-xs text-gray-700">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/50 transition">
                        <!-- Nomor Invoice -->
                        <td class="p-4 pl-6 font-bold text-[#2C2623]">{{ $order->invoice_number }}</td>

                        <!-- Nama Customer -->
                        <td class="p-4 font-medium">{{ $order->user->name ?? 'Tamu' }}</td>

                        <!-- Waktu -->
                        <td class="p-4 text-gray-500">{{ $order->created_at->format('H:i') }} WIB</td>

                        <!-- Total Harga -->
                        <td class="p-4 font-semibold text-[#2C2623]">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>

                        <!-- Status Pesanan -->
                        <td class="p-4">
                            @if($order->status == 'pending')
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-800 uppercase">Pending</span>
                            @elseif($order->status == 'processing')
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 uppercase">Processing</span>
                            @elseif($order->status == 'completed')
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase">Completed</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-800 uppercase">Cancelled</span>
                            @endif
                        </td>

                        <!-- Kolom Metode Pembayaran (Cash / QRIS) -->
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-gray-100 text-gray-800 uppercase tracking-wider border border-gray-200">
                                {{ optional($order->payment)->payment_gateway ?? '-' }}
                            </span>
                        </td>

                        <!-- Status Pembayaran -->
                        <td class="p-4">
                            @php $payStatus = optional($order->payment)->status ?? 'pending'; @endphp
                            @if($payStatus == 'success' || $payStatus == 'paid')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase">Paid</span>
                            @elseif($payStatus == 'expired')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-gray-200 text-gray-700 uppercase">Expired</span>
                            @elseif($payStatus == 'failed')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-800 uppercase">Failed</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 uppercase">Pending</span>
                            @endif
                        </td>

                        <!-- Tombol Aksi -->
                        <td class="p-4 pr-6 text-right space-x-1.5">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium text-[11px] transition">
                                Periksa
                            </a>

                            @if($order->status == 'cancelled')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[10px] font-bold bg-red-50 text-red-600 border border-red-200">
                                    Terkunci
                                </span>
                            @elseif($order->status == 'pending')
                                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="processing">
                                    <button type="submit" class="px-3 py-1.5 bg-[#2C2623] hover:bg-black text-white rounded-xl font-medium text-[11px] transition shadow-xs">
                                        Terima Pesanan
                                    </button>
                                </form>
                            @elseif($order->status == 'processing')
                                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl font-medium text-[11px] transition shadow-xs">
                                        Tandai Selesai
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-gray-400">
                            <i class="fa-solid fa-receipt text-3xl mb-2 block"></i>
                            Belum ada pesanan masuk.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    @if($orders->hasPages())
        <div class="mt-4">
            {{ $orders->withQueryString()->links() }}
        </div>
    @endif

</div>
@endsection
