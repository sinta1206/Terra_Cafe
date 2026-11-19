@extends('layouts.app')

@section('title', 'Pembayaran - TERRA Cafe')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="bg-white border border-[#EAE5DF] rounded-[24px] p-8 shadow-sm space-y-6 text-center">

        <!-- Header -->
        <div class="space-y-1">
            <span class="text-[10px] font-extrabold tracking-[3px] text-[#8C7A6B] uppercase">Selesaikan Pembayaran</span>
            <h1 class="text-2xl font-extrabold text-[#2C2623]">Pembayaran Virtual Account</h1>
            <p class="text-xs text-gray-500">Klik tombol di bawah untuk membuka halaman pembayaran Midtrans dan mendapatkan nomor VA.</p>
        </div>

        <!-- Info Invoice & Total -->
        <div class="bg-[#FAF8F5] border border-[#E5E0D8] rounded-2xl p-4 flex justify-between items-center text-xs">
            <div class="text-left">
                <span class="text-gray-400 block font-medium">No. Invoice</span>
                <span class="font-extrabold text-[#2C2623]">{{ $order->invoice_number }}</span>
            </div>
            <div class="text-right">
                <span class="text-gray-400 block font-medium">Total Tagihan</span>
                <span class="font-extrabold text-[#C49A6C] text-sm">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Countdown Timer 15 Menit -->
        <div class="bg-amber-50 border border-amber-200 rounded-2xl px-4 py-3 flex items-center justify-between text-xs">
            <span class="font-bold text-amber-900">
                <i class="fa-regular fa-clock mr-1"></i> Batas Waktu Pembayaran
            </span>
            <span id="countdown" class="font-extrabold text-sm text-amber-800 tabular-nums">15:00</span>
        </div>

        <!-- Petunjuk Singkat -->
        <div class="text-xs text-gray-500 space-y-1 bg-[#FAF8F5] border border-[#E5E0D8] p-4 rounded-2xl text-left">
            <p class="font-bold text-[#2C2623]"><i class="fa-solid fa-circle-info mr-1 text-[#8C7A6B]"></i> Petunjuk Pembayaran:</p>
            <ol class="list-decimal list-inside space-y-1 text-[11px] text-gray-600">
                <li>Klik tombol <b>Bayar Sekarang</b> sebelum batas waktu habis.</li>
                <li>Pilih bank <b>Virtual Account</b> pada pop-up Midtrans Snap.</li>
                <li>Salin nomor Virtual Account yang muncul lalu selesaikan pembayaran (atau simulasi di Midtrans Simulator).</li>
                <li>Status pesanan akan diperbarui setelah pembayaran berhasil.</li>
            </ol>
        </div>

        <!-- Tombol Aksi -->
        <div class="space-y-3 pt-2">
            <button type="button" id="pay-button"
                class="w-full py-3.5 bg-[#4A5342] hover:bg-[#3B4235] text-white rounded-[20px] text-xs font-extrabold transition shadow-sm cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed">
                Bayar Sekarang &rarr;
            </button>

            <a href="{{ route('customer.orders.index') }}" class="block w-full py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-[20px] text-xs font-bold transition">
                Cek Daftar Pesanan
            </a>
        </div>

    </div>

</div>

<!-- Midtrans Snap JS (Sandbox) -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
    const deadline = {{ $deadlineTimestamp }};
    const countdownEl = document.getElementById('countdown');
    const payButton = document.getElementById('pay-button');

    function updateCountdown() {
        const diff = deadline - Date.now();

        if (diff <= 0) {
            countdownEl.textContent = "00:00";
            payButton.disabled = true;
            payButton.textContent = "Waktu Pembayaran Habis";
            clearInterval(timerInterval);
            return;
        }

        const totalSeconds = Math.floor(diff / 1000);
        const minutes = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
        const seconds = String(totalSeconds % 60).padStart(2, '0');
        countdownEl.textContent = `${minutes}:${seconds}`;
    }

    updateCountdown();
    const timerInterval = setInterval(updateCountdown, 1000);

    payButton.addEventListener('click', function () {
        if (Date.now() >= deadline) return;

        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function (result) {
                window.location.href = "{{ route('customer.orders.show', $order->id) }}";
            },
            onPending: function (result) {
                window.location.href = "{{ route('customer.orders.show', $order->id) }}";
            },
            onError: function (result) {
                alert('Pembayaran gagal diproses. Silakan coba lagi.');
            },
            onClose: function () {
                // Pop-up ditutup sebelum bayar; countdown tetap berjalan
            }
        });
    });
</script>
@endsection
