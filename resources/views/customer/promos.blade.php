@extends('layouts.app')

@section('title', 'Promo & Voucher - TERRA Cafe')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <!-- Header Judul -->
    <div class="space-y-1">
        <span class="text-[10px] font-extrabold tracking-[3px] text-[#8C7A6B] uppercase">LEBIH HEMAT, LEBIH NIKMAT</span>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#2C2623] flex items-center gap-3">
            Promo & Voucher 🎟️
        </h1>
    </div>

    <!-- Grid Kartu Voucher -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($vouchers as $index => $voucher)
            @php
                // Bergantian warna kartu: card pertama gelap (sage/kopi), card kedua terang (krem)
                $isDark = $index % 2 == 0;
            @endphp

            <div class="{{ $isDark ? 'bg-[#3E4A41] text-white' : 'bg-[#EFECE6] text-[#2C2623]' }} rounded-3xl p-8 shadow-sm flex flex-col justify-between relative overflow-hidden transition-all duration-300 hover:shadow-md">
                
                <!-- Bagian Atas: Keterangan & Judul -->
                <div class="space-y-3">
                    <span class="text-[10px] font-bold uppercase tracking-widest {{ $isDark ? 'text-[#C2D1C5]' : 'text-[#8C7A6B]' }}">
                        {{ $voucher->type ?? 'PROMO PILIHAN' }}
                    </span>

                    <h3 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                        @if($voucher->value_type == 'fixed')
                            Potongan Rp {{ number_format($voucher->value, 0, ',', '.') }}
                        @else
                            Diskon {{ $voucher->value }}%
                        @endif
                    </h3>

                    <p class="text-xs md:text-sm {{ $isDark ? 'text-gray-200' : 'text-gray-600' }} leading-relaxed">
                        Untuk pesanan minimal Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }}.
                    </p>
                </div>

                <!-- Bagian Bawah: Tombol Kode & Salin -->
                <div class="mt-8 flex items-center justify-between pt-6 border-t {{ $isDark ? 'border-white/10' : 'border-[#2C2623]/10' }}">
                    
                    <!-- Kotak Kode Voucher -->
                    <div class="border border-dashed {{ $isDark ? 'border-white/40 bg-white/5' : 'border-[#2C2623]/30 bg-white/50' }} px-4 py-2 rounded-xl text-xs font-mono font-bold tracking-wider">
                        {{ $voucher->code }}
                    </div>

                    <!-- Tombol Salin Kode -->
                    <button type="button" 
                            onclick="copyVoucherCode('{{ $voucher->code }}', this)"
                            class="{{ $isDark ? 'bg-white text-[#3E4A41] hover:bg-gray-100' : 'bg-[#3E4A41] text-white hover:bg-[#2C2623]' }} px-5 py-2.5 rounded-xl text-xs font-bold transition duration-300 shadow-xs cursor-pointer">
                        Salin kode
                    </button>

                </div>

            </div>
        @empty
            <div class="col-span-2 bg-white border border-[#EAE5DF] rounded-3xl p-12 text-center text-gray-400 text-xs shadow-sm">
                <i class="fa-solid fa-ticket text-4xl text-[#C9B49B] mb-3"></i>
                <h4 class="font-bold text-[#2C2623] text-sm">Belum ada promo aktif saat ini</h4>
                <p class="text-gray-400 mt-1">Silakan cek kembali secara berkala untuk promo menarik lainnya.</p>
            </div>
        @endforelse
    </div>

</div>

<!-- Script untuk Menyalin Kode Promo secara Otomatis -->
<script>
    function copyVoucherCode(code, buttonElement) {
        navigator.clipboard.writeText(code).then(() => {
            const originalText = buttonElement.innerText;
            buttonElement.innerText = 'Berhasil disalin! ✔';
            buttonElement.classList.add('bg-green-600', 'text-white');

            setTimeout(() => {
                buttonElement.innerText = originalText;
                buttonElement.classList.remove('bg-green-600', 'text-white');
            }, 2000);
        }).catch(err => {
            alert('Gagal menyalin kode.');
        });
    }
</script>
@endsection
