@extends('layouts.app')

@section('title', 'Checkout - TERRA Cafe')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-2xl font-bold text-[#2C2623] mb-6">Selesaikan Pesanan</h1>

    <form action="{{ route('customer.checkout.process') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Pilihan Tipe Pesanan -->
        <div class="bg-white border border-[#EAE5DF] rounded-2xl p-6 shadow-sm space-y-3">
            <h2 class="font-bold text-[#2C2623] text-sm">Metode Pesanan</h2>
            <div class="grid grid-cols-3 gap-3">
                <label class="border border-[#E5E0D8] bg-[#FAF8F5] rounded-xl p-3 text-center text-xs font-bold text-[#2C2623] cursor-pointer hover:border-[#2C2623]">
                    <input type="radio" name="order_type" value="delivery" checked class="mb-1 accent-[#2C2623]" onchange="updateCheckoutSummary()"> Delivery
                </label>
                <label class="border border-[#E5E0D8] bg-[#FAF8F5] rounded-xl p-3 text-center text-xs font-bold text-[#2C2623] cursor-pointer hover:border-[#2C2623]">
                    <input type="radio" name="order_type" value="take_away" class="mb-1 accent-[#2C2623]" onchange="updateCheckoutSummary()"> Take Away
                </label>
                <label class="border border-[#E5E0D8] bg-[#FAF8F5] rounded-xl p-3 text-center text-xs font-bold text-[#2C2623] cursor-pointer hover:border-[#2C2623]">
                    <input type="radio" name="order_type" value="dine_in" class="mb-1 accent-[#2C2623]" onchange="updateCheckoutSummary()"> Dine In
                </label>
            </div>
        </div>

        <!-- Kontak & Alamat -->
        <div class="bg-white border border-[#EAE5DF] rounded-2xl p-6 shadow-sm space-y-4">
            <h2 class="font-bold text-[#2C2623] text-sm">Informasi Pengiriman</h2>
            <div>
                <label class="block text-xs font-bold text-[#2C2623] uppercase mb-1">Nomor Telepon / WhatsApp</label>
                <input type="text" name="phone" value="{{ auth()->user()->phone ?? '' }}" required
                    class="w-full px-4 py-2.5 rounded-xl border border-[#EAE5DF] bg-[#FAF8F5] text-xs font-medium text-[#2C2623] focus:outline-none focus:border-[#2C2623]">
            </div>
            <div id="address-box">
                <label class="block text-xs font-bold text-[#2C2623] uppercase mb-1">Alamat Lengkap</label>
                <textarea name="address" rows="3" placeholder="Masukkan alamat pengantaran..."
                    class="w-full px-4 py-2.5 rounded-xl border border-[#EAE5DF] bg-[#FAF8F5] text-xs font-medium text-[#2C2623] focus:outline-none focus:border-[#2C2623]">{{ auth()->user()->address ?? '' }}</textarea>
            </div>
        </div>

        <!-- Rangkuman Singkat & Voucher Aktif -->
        @php
            $subtotal = $cart->items->sum(fn($i) => ($i->price > 0 ? $i->price : ($i->product->price + (str_contains($i->note, 'Large') ? 3000 : 0))) * $i->quantity);
            $discount = session('voucher_discount', 0);
            $appliedVoucher = session('applied_voucher');
        @endphp

        <div class="bg-white border border-[#EAE5DF] rounded-2xl p-6 shadow-sm space-y-3 text-xs">
            <h2 class="font-bold text-[#2C2623] text-sm mb-2">Ringkasan Pembayaran</h2>
            <div class="flex justify-between text-gray-500">
                <span>Subtotal Pesanan</span>
                <span class="font-bold text-[#2C2623]" id="subtotal-val" data-subtotal="{{ $subtotal }}">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-gray-500">
                <span>Diskon Voucher ({{ $appliedVoucher ?? 'Tidak ada' }})</span>
                <span class="font-bold text-emerald-600" id="discount-val" data-discount="{{ $discount }}">- Rp {{ number_format($discount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-gray-500">
                <span>Biaya Pengiriman</span>
                <span class="font-bold text-[#2C2623]" id="shipping-val">Rp 10.000</span>
            </div>
            <div class="border-t border-[#F0ECE7] pt-3 flex justify-between items-center text-sm">
                <span class="font-extrabold text-[#2C2623]">Total Pembayaran</span>
                <span class="font-extrabold text-[#2C2623] text-base" id="total-val">Rp {{ number_format(max(0, $subtotal - $discount) + 10000, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Metode Pembayaran -->
        <div class="bg-white border border-[#EAE5DF] rounded-2xl p-6 shadow-sm">
            <h2 class="font-bold text-[#2C2623] text-sm mb-4">Metode Pembayaran</h2>
            <div class="space-y-3 text-xs">
                <label class="flex items-center space-x-3 border border-[#E5E0D8] bg-[#FAF8F5] p-4 rounded-xl cursor-pointer hover:border-[#2C2623]">
                    <input type="radio" name="payment_method" value="qris" checked class="accent-[#2C2623]">
                    <div>
                        <span class="font-extrabold text-[#2C2623] text-sm block">Pembayaran Online (QRIS / Virtual Account)</span>
                        <span class="text-gray-500">Bayar instan via GoPay, OVO, Dana, M-Banking</span>
                    </div>
                </label>
                <label class="flex items-center space-x-3 border border-[#E5E0D8] bg-[#FAF8F5] p-4 rounded-xl cursor-pointer hover:border-[#2C2623]">
                    <input type="radio" name="payment_method" value="cash" class="accent-[#2C2623]">
                    <div>
                        <span class="font-extrabold text-[#2C2623] text-sm block">Tunai di Kasir</span>
                        <span class="text-gray-500">Bayar langsung saat pesanan diambil</span>
                    </div>
                </label>
            </div>
        </div>

        <button type="submit" class="w-full py-4 bg-[#2C2623] hover:bg-black text-white rounded-[20px] font-extrabold transition shadow-sm text-center text-xs tracking-wider uppercase cursor-pointer">
            Proses Pesanan Sekarang &rarr;
        </button>
    </form>
</div>

<script>
function updateCheckoutSummary() {
    const selectedType = document.querySelector('input[name="order_type"]:checked').value;
    const subtotal = parseInt(document.getElementById('subtotal-val').getAttribute('data-subtotal')) || 0;
    const discount = parseInt(document.getElementById('discount-val').getAttribute('data-discount')) || 0;

    let shippingFee = 10000;
    if (selectedType === 'take_away' || selectedType === 'dine_in') {
        shippingFee = 0;
        document.getElementById('address-box').style.display = 'none'; // Sembunyikan alamat jika bukan delivery
    } else {
        document.getElementById('address-box').style.display = 'block';
    }

    const total = Math.max(0, subtotal - discount) + shippingFee;

    document.getElementById('shipping-val').innerText = 'Rp ' + shippingFee.toLocaleString('id-ID');
    document.getElementById('total-val').innerText = 'Rp ' + total.toLocaleString('id-ID');
}
</script>
@endsection
