@extends('layouts.app')

@section('title', 'Checkout - TERRA Cafe')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Selesaikan Pesanan</h1>

    <form action="{{ route('customer.checkout.process') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Pilihan Tipe Pesanan -->
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
            <h2 class="font-bold text-gray-900 mb-4">Metode Pesanan</h2>
            <div class="grid grid-cols-3 gap-4">
                <label class="border border-gray-200 rounded-xl p-3 text-center text-sm font-medium cursor-pointer hover:border-black">
                    <input type="radio" name="order_type" value="delivery" class="mb-1" checked> Delivery
                </label>
                <label class="border border-gray-200 rounded-xl p-3 text-center text-sm font-medium cursor-pointer hover:border-black">
                    <input type="radio" name="order_type" value="take_away" class="mb-1"> Take Away
                </label>
                <label class="border border-gray-200 rounded-xl p-3 text-center text-sm font-medium cursor-pointer hover:border-black">
                    <input type="radio" name="order_type" value="dine_in" class="mb-1"> Dine In
                </label>
            </div>
        </div>

        <!-- Kontak & Alamat -->
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm space-y-4">
            <h2 class="font-bold text-gray-900 mb-2">Informasi Pengiriman</h2>
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Nomor Telepon / WhatsApp</label>
                <input type="text" name="phone" value="{{ auth()->user()->phone }}" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Alamat Lengkap</label>
                <textarea name="address" rows="3" placeholder="Masukkan alamat pengantaran..."
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800"></textarea>
            </div>
        </div>

        <!-- Voucher Promo -->
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
            <h2 class="font-bold text-gray-900 mb-2">Kode Voucher (Opsional)</h2>
            <input type="text" name="voucher_code" placeholder="Masukkan kode promo..."
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
        </div>

        <!-- Metode Pembayaran -->
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
            <h2 class="font-bold text-gray-900 mb-4">Metode Pembayaran</h2>
            <div class="space-y-3">
                <label class="flex items-center space-x-3 border border-gray-200 p-4 rounded-xl cursor-pointer hover:border-black">
                    <input type="radio" name="payment_method" value="qris" checked class="text-black">
                    <div>
                        <span class="font-bold text-gray-900 text-sm block">QRIS (Scan & Bayar)</span>
                        <span class="text-xs text-gray-500">Bayar instan via GoPay, OVO, Dana, M-Banking</span>
                    </div>
                </label>
                <label class="flex items-center space-x-3 border border-gray-200 p-4 rounded-xl cursor-pointer hover:border-black">
                    <input type="radio" name="payment_method" value="cash" class="text-black">
                    <div>
                        <span class="font-bold text-gray-900 text-sm block">Tunai di Kasir</span>
                        <span class="text-xs text-gray-500">Bayar langsung saat pesanan diambil</span>
                    </div>
                </label>
            </div>
        </div>

        <button type="submit" class="w-full py-4 bg-gray-900 text-white rounded-xl font-medium hover:bg-black transition shadow-sm text-center">
            Proses Pesanan Sekarang
        </button>
    </form>
</div>
@endsection
