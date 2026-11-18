@extends('layouts.admin')

@section('title', 'Detail Pesanan - TERRA Cafe')
@section('header-title', 'Detail & Status Pesanan')

@section('content')
<div class="max-w-3xl space-y-6">

    <!-- Informasi Utama Pesanan -->
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm space-y-4">
        <div class="flex justify-between items-start">
            <div>
                <span class="text-xs font-semibold text-gray-400 uppercase">Nomor Invoice</span>
                <h2 class="text-xl font-bold text-[#2C2623]">{{ $order->invoice_number }}</h2>
                <p class="text-xs text-gray-500 mt-0.5">Pelanggan: <strong class="text-[#2C2623]">{{ $order->user->name ?? 'Tamu' }}</strong></p>
            </div>
            <span class="text-xs text-gray-400">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
        </div>

        <div class="border-t border-gray-100 pt-4">
            <h3 class="font-bold text-gray-900 text-xs uppercase mb-3">Item Belanja</h3>
            <div class="space-y-2.5">
                @foreach($order->items as $item)
                    <div class="flex justify-between items-center text-xs">
                        <div>
                            <span class="font-bold text-[#2C2623]">{{ $item->product->name ?? 'Produk' }}</span>
                            <span class="text-gray-400 block">x{{ $item->quantity }} @ Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            @if($item->note)
                                <span class="text-[10px] text-gray-500 bg-gray-50 px-2 py-0.5 rounded border border-gray-200 mt-1 inline-block">{{ $item->note }}</span>
                            @endif
                        </div>
                        <span class="font-bold text-[#2C2623]">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
            <div class="border-t border-gray-100 mt-4 pt-3 flex justify-between font-bold text-sm">
                <span>Total Tagihan</span>
                <span class="text-[#2C2623]">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- PENGATURAN STATUS TERPISAH (PROSES PESANAN & PEMBAYARAN) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        
        <!-- 1. Form Update Status Pesanan (Dapur/Kafe) -->
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm space-y-4">
            <div>
                <h3 class="font-bold text-gray-900 text-xs uppercase">Status Pemrosesan Dapur</h3>
                <p class="text-[11px] text-gray-500">Atur progres pembuatan pesanan.</p>
            </div>

            <div class="py-1">
                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase
                    @if($order->status == 'completed') bg-emerald-100 text-emerald-800
                    @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                    @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="space-y-3 pt-2">
                @csrf
                @method('PATCH')
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs font-bold text-gray-700">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing (Diproses)</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                </select>
                <button type="submit" class="w-full py-2.5 bg-[#2C2623] hover:bg-black text-white rounded-xl font-bold text-xs transition">
                    Perbarui Status Pesanan
                </button>
            </form>
        </div>

        <!-- 2. Status & Konfirmasi Pembayaran (Kasir) -->
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm space-y-4">
            <div>
                <h3 class="font-bold text-gray-900 text-xs uppercase">Status Pembayaran Kasir</h3>
                <p class="text-[11px] text-gray-500">Metode: <strong class="uppercase text-gray-700">{{ optional($order->payment)->payment_gateway ?? '-' }}</strong></p>
            </div>

            <div class="py-1">
                @php $payStatus = optional($order->payment)->status ?? 'pending'; @endphp
                @if($payStatus == 'success' || $payStatus == 'paid')
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase">Lunas (Paid)</span>
                @else
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 uppercase">Belum Lunas (Pending)</span>
                @endif
            </div>

            @if($payStatus !== 'success' && $payStatus !== 'paid')
                <form action="{{ route('admin.orders.payment-status', $order->id) }}" method="POST" class="pt-2">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition shadow-xs">
                        Konfirmasi Pembayaran Lunas
                    </button>
                </form>
            @else
                <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100 text-emerald-800 text-[11px] font-bold">
                    <i class="fa-solid fa-circle-check mr-1"></i> Tagihan ini telah lunas dibayar.
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
