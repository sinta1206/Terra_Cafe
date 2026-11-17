@extends('layouts.app')

@section('title', 'Pembayaran QRIS - TERRA Cafe')

@section('content')
<div class="max-w-md mx-auto px-4 py-12 text-center">
    <div class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm">
        <span class="text-xs font-semibold text-gray-500 uppercase">Selesaikan Pembayaran</span>
        <h1 class="text-2xl font-bold text-gray-900 mt-1">Scan QRIS</h1>
        <p class="text-sm text-gray-600 mt-1">Invoice: <span class="font-bold">{{ $order->invoice_number }}</span></p>

        <div class="my-6 p-4 bg-gray-50 border border-gray-200 rounded-2xl inline-block">
            <div class="w-48 h-48 bg-gray-200 mx-auto rounded-xl flex items-center justify-center text-gray-500 font-bold text-sm">
                [ QRIS BARCODE ]
            </div>
        </div>

        <p class="text-xl font-extrabold text-gray-900 mb-6">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>

        <form action="{{ route('customer.payment.qris.confirm', $order->id) }}" method="POST">
            @csrf
            <button type="submit" class="w-full py-3 bg-green-600 text-white rounded-xl font-medium hover:bg-green-700 transition shadow-sm">
                Saya Sudah Bayar
            </button>
        </form>
    </div>
</div>
@endsection
