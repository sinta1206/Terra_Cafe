@extends('layouts.admin')

@section('title', 'Kelola Pesanan - TERRA Cafe')
@section('header-title', 'Kelola Pesanan Masuk')

@section('content')
<div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50 text-xs font-semibold text-gray-500 uppercase">
                <th class="p-4">Invoice</th>
                <th class="p-4">Pelanggan</th>
                <th class="p-4">Total</th>
                <th class="p-4">Status</th>
                <th class="p-4 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm">
            @foreach($orders as $order)
                <tr>
                    <td class="p-4 font-semibold text-gray-900">{{ $order->invoice_number }}</td>
                    <td class="p-4 text-gray-700">{{ $order->user->name ?? 'Tamu' }}</td>
                    <td class="p-4 text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td class="p-4">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-50 text-yellow-700 uppercase">{{ $order->status }}</span>
                    </td>
                    <td class="p-4 text-right">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="px-3 py-1.5 bg-gray-900 text-white text-xs rounded-lg font-medium">Periksa</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection


