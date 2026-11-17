@extends('layouts.admin')

@section('title', 'Detail Pesanan - TERRA Cafe')
@section('header-title', 'Detail & Status Pesanan')

@section('content')
<div class="max-w-2xl bg-white border border-gray-100 rounded-2xl p-6 shadow-sm space-y-6">
    <div>
        <span class="text-xs font-semibold text-gray-500 uppercase">Nomor Invoice</span>
        <h2 class="text-xl font-bold text-gray-900">{{ $order->invoice_number }}</h2>
        <p class="text-sm text-gray-600 mt-1">Pelanggan: {{ $order->user->name ?? 'Tamu' }}</p>
    </div>

    <div>
        <h3 class="font-bold text-gray-900 mb-2">Item Belanja</h3>
        <div class="space-y-2">
            @foreach($order->items as $item)
                <div class="flex justify-between text-sm">
                    <span>{{ $item->product->name }} (x{{ $item->quantity }})</span>
                    <span class="font-semibold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="space-y-4 pt-4 border-t">
        @csrf
        @method('PATCH')
        <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Perbarui Status Pesanan</label>
            <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing (Diproses)</option>
                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
            </select>
        </div>
        <button type="submit" class="w-full py-3 bg-gray-900 text-white rounded-xl font-medium hover:bg-black transition">Perbarui Status</button>
    </form>
</div>
@endsection
