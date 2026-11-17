@extends('layouts.app')

@section('title', 'Promo & Voucher - TERRA Cafe')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Promo & Voucher Tersedia</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        @forelse($vouchers as $voucher)
            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm border-l-4 border-l-gray-900 flex flex-col justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 uppercase">Kode: {{ $voucher->code }}</span>
                    <h3 class="font-bold text-gray-900 text-lg mt-1">{{ $voucher->name }}</h3>
                    <p class="text-sm text-gray-600 mt-2">Diskon: <span class="font-semibold">{{ $voucher->value }}%</span> (Min. Belanja Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }})</p>
                </div>
                <div class="mt-6 text-xs text-gray-400">
                    Berlaku sampai: {{ \Carbon\Carbon::parse($voucher->expired_at)->format('d M Y') }}
                </div>
            </div>
        @empty
            <p class="text-gray-500 col-span-2 text-center py-12">Belum ada promo aktif saat ini.</p>
        @endforelse
    </div>
</div>
@endsection
