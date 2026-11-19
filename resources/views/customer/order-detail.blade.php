@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->invoice_number . ' - TERRA Cafe')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <!-- Tombol Kembali & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('customer.orders.index') }}" class="text-xs font-bold text-[#8C7A6B] hover:text-[#2C2623] flex items-center space-x-1 transition mb-2">
                <span>&larr; Kembali ke Daftar Pesanan</span>
            </a>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#2C2623] flex items-center gap-3">
                Detail Pesanan <span class="text-base text-[#8C7A6B]">#{{ $order->invoice_number }}</span>
            </h1>
        </div>

        <!-- Badge Status Utama -->
        <div>
            <span class="px-3.5 py-1.5 text-xs font-extrabold rounded-xl uppercase tracking-wider
                @if($order->status == 'completed' || $order->status == 'paid') bg-green-50 text-green-700 border border-green-200
                @elseif($order->status == 'pending') bg-amber-50 text-amber-700 border border-amber-200
                @elseif($order->status == 'processing') bg-blue-50 text-blue-700 border border-blue-200
                @else bg-gray-50 text-gray-700 border border-gray-200 @endif">
                Status: {{ ucfirst($order->status) }}
            </span>
        </div>
    </div>

    <!-- Layout Dua Kolom -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">

        <!-- KOLOM KIRI: Daftar Item & Catatan -->
        <div class="md:col-span-7 space-y-6">

            <!-- Daftar Menu yang Dipesan -->
            <div class="bg-white border border-[#EAE5DF] rounded-[24px] p-6 shadow-[0_4px_15px_rgba(44,38,35,0.03)] space-y-4">
                <h3 class="font-extrabold text-[#2C2623] text-sm uppercase tracking-wider border-b border-[#F0ECE7] pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-mug-hot text-[#C49A6C]"></i> Item Pesanan
                </h3>

                <div class="divide-y divide-[#F0ECE7]">
                    @foreach($order->items as $item)
                        <div class="py-3.5 first:pt-0 last:pb-0 flex items-start justify-between gap-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-14 h-14 rounded-xl bg-[#F5F2EE] border border-[#F0ECE7] overflow-hidden flex-shrink-0 flex items-center justify-center">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fa-solid fa-mug-hot text-gray-400 text-xs"></i>
                                    @endif
                                </div>
                                <div class="space-y-0.5">
                                    <h4 class="font-extrabold text-[#2C2623] text-xs sm:text-sm">{{ $item->product->name ?? 'Produk Dihapus' }}</h4>
                                    <p class="text-[11px] font-bold text-[#8C7A6B]">{{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    @if($item->note)
                                        <span class="inline-block px-2 py-0.5 bg-[#FAF8F5] border border-[#E5E0D8] text-[10px] text-gray-500 rounded-md font-medium mt-1">
                                            {{ $item->note }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="font-extrabold text-[#2C2623] text-xs sm:text-sm">
                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tombol Pesan Lagi (Reorder) -->
            {{-- <div class="bg-white border border-[#EAE5DF] rounded-[24px] p-6 shadow-[0_4px_15px_rgba(44,38,35,0.03)] flex items-center justify-between">
                <div>
                    <h4 class="font-extrabold text-[#2C2623] text-xs">Ingin memesan menu yang sama lagi?</h4>
                    <p class="text-[11px] text-gray-400">Masukkan kembali semua item ke keranjang belanja Anda.</p>
                </div>
                <form action="{{ route('customer.orders.reorder', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 bg-[#2C2623] hover:bg-black text-white rounded-xl text-xs font-extrabold transition shadow-2xs cursor-pointer">
                        <i class="fa-solid fa-rotate-right mr-1"></i> Pesan Lagi
                    </button>
                </form>
            </div> --}}

        </div>

        <!-- KOLOM KANAN: Rincian Pembayaran & Informasi Pengiriman -->
        <div class="md:col-span-5 space-y-6">

            <!-- Rincian Pembayaran -->
            <div class="bg-white border border-[#EAE5DF] rounded-[24px] p-6 shadow-[0_4px_15px_rgba(44,38,35,0.03)] space-y-4 text-xs">
                <h3 class="font-extrabold text-[#2C2623] text-sm border-b border-[#F0ECE7] pb-3">Ringkasan Transaksi</h3>

                <div class="space-y-2.5 text-gray-500">
                    <div class="flex justify-between">
                        <span>Metode Pembayaran</span>
                        <span class="font-bold uppercase text-[#2C2623]">
                            {{ $order->payment->payment_gateway ?? 'Tunai / QRIS / Virtual Account' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span>Status Pembayaran</span>
                        <span class="font-bold uppercase {{ optional($order->payment)->status == 'success' || optional($order->payment)->status == 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ optional($order->payment)->status ?? 'Pending' }}
                        </span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-[#F0ECE7]">
                        <span>Subtotal Belanja</span>
                        <span class="font-bold text-[#2C2623]">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Diskon Voucher</span>
                        <span class="font-bold text-emerald-600">- Rp {{ number_format($order->discount ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Biaya Pengiriman</span>
                        <span class="font-bold text-[#2C2623]">
                            {{ $order->total_amount - ($order->subtotal - ($order->discount ?? 0)) > 0 ? 'Rp 10.000' : 'Rp 0' }}
                        </span>
                    </div>
                </div>

                <div class="border-t border-[#F0ECE7] pt-4 flex justify-between items-center text-sm">
                    <span class="font-extrabold text-[#2C2623]">Total Tagihan</span>
                    <span class="font-extrabold text-[#2C2623] text-base">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>

                <!-- Jika status masih pending dan metode midtrans/qris, beri tombol lanjutkan pembayaran -->
                @if($order->status == 'pending' && in_array(optional($order->payment)->payment_gateway, ['midtrans', 'qris']))
                    <div class="pt-2">
                        <a href="{{ route('customer.payment.qris', $order->id) }}" class="w-full block text-center py-3 bg-[#4A5342] hover:bg-[#3B4235] text-white rounded-xl font-extrabold transition text-xs shadow-sm">
                            Lanjutkan Pembayaran &rarr;
                        </a>
                    </div>
                @endif

            <!-- Informasi Waktu & Tanggal -->
            <div class="bg-[#FAF8F5] border border-[#E5E0D8] rounded-[20px] p-5 text-xs text-gray-500 space-y-2">
                <div class="flex items-center gap-2">
                    <i class="fa-regular fa-calendar-days text-[#C49A6C]"></i>
                    <span>Dibuat pada: <strong class="text-[#2C2623]">{{ $order->created_at->format('d M Y, H:i') }}</strong></span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-receipt text-[#C49A6C]"></i>
                    <span>Nomor Invoice: <strong class="text-[#2C2623]">{{ $order->invoice_number }}</strong></span>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
