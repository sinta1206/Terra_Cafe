@extends('layouts.app')

@section('title', 'Profil Saya - TERRA Cafe')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="max-w-2xl mx-auto space-y-6">

        <!-- Header Judul -->
        <div class="space-y-1 text-center sm:text-left">
            <span class="text-[10px] font-extrabold tracking-[3px] text-[#8C7A6B] uppercase">AKUN TERRA KAMU</span>
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#2C2623] flex items-center justify-center sm:justify-start gap-3">
                Profil Saya 👤
            </h1>
        </div>

        <!-- 1. KARTU INFORMASI UTAMA -->
        <div class="bg-white border border-[#EAE5DF] rounded-[24px] p-6 shadow-[0_4px_15px_rgba(44,38,35,0.03)] hover:shadow-[0_8px_25px_rgba(44,38,35,0.07)] transition-all duration-300 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            
            <div class="flex items-center space-x-4">
                <!-- Avatar Inisial -->
                <div class="w-14 h-14 rounded-full bg-[#EFECE6] text-[#2C2623] font-bold flex items-center justify-center text-lg border border-[#E5E0D8] flex-shrink-0">
                    @php
                        $names = explode(' ', $user->name);
                        $initials = strtoupper(substr($names[0], 0, 1) . (isset($names[1]) ? substr($names[1], 0, 1) : ''));
                    @endphp
                    {{ $initials }}
                </div>

                <div class="space-y-0.5">
                    <h3 class="font-extrabold text-[#2C2623] text-lg leading-snug">{{ $user->name }}</h3>
                    <p class="text-xs text-gray-500 font-medium">{{ $user->email }}</p>
                    <p class="text-xs text-gray-400">{{ $user->phone ?? 'Belum ada nomor telepon' }}</p>
                </div>
            </div>

            <!-- Tombol Edit Profil (Ikon Pensil) -->
            <a href="{{ route('customer.profile.edit') }}" 
               class="w-10 h-10 rounded-full bg-[#FAF8F5] border border-[#E5E0D8] hover:bg-[#2C2623] hover:text-white hover:border-[#2C2623] text-[#2C2623] flex items-center justify-center transition duration-300 shadow-2xs flex-shrink-0 ml-auto sm:ml-0" 
               title="Edit Profil">
                <i class="fa-solid fa-pen text-xs"></i>
            </a>

        </div>


        <!-- 2. KARTU STATISTIK (Alamat Utama & Voucher Aktif dari Promo) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            
            <!-- Alamat Utama (Menggantikan Poin Terra) -->
            <div class="bg-white border border-[#EAE5DF] rounded-[24px] p-5 shadow-[0_4px_15px_rgba(44,38,35,0.03)] hover:shadow-[0_8px_25px_rgba(44,38,35,0.07)] transition-all duration-300 flex items-center space-x-4">
                <div class="w-11 h-11 rounded-xl bg-[#FAF8F5] border border-[#E5E0D8] flex items-center justify-center text-[#2C2623] text-sm flex-shrink-0">
                    <i class="fa-solid fa-location-dot text-[#C49A6C]"></i>
                </div>
                <div class="overflow-hidden">
                    <span class="text-[10px] font-extrabold text-[#8C7A6B] uppercase tracking-wider block">Alamat Utama</span>
                    <span class="font-extrabold text-[#2C2623] text-xs sm:text-sm block truncate" title="{{ $user->address ?? 'Belum diatur' }}">
                        {{ $user->address ?? 'Belum diatur' }}
                    </span>
                </div>
            </div>

            <!-- Voucher Aktif (Sinkron dengan Promo) -->
            <div class="bg-white border border-[#EAE5DF] rounded-[24px] p-5 shadow-[0_4px_15px_rgba(44,38,35,0.03)] hover:shadow-[0_8px_25px_rgba(44,38,35,0.07)] transition-all duration-300 flex items-center space-x-4">
                <div class="w-11 h-11 rounded-xl bg-[#FAF8F5] border border-[#E5E0D8] flex items-center justify-center text-[#2C2623] text-sm flex-shrink-0">
                    <i class="fa-solid fa-ticket text-[#C49A6C]"></i>
                </div>
                <div>
                    <span class="text-[10px] font-extrabold text-[#8C7A6B] uppercase tracking-wider block">Voucher Aktif</span>
                    <span class="font-extrabold text-[#2C2623] text-lg">
                        {{ isset($activeVouchersCount) ? $activeVouchersCount . ' Kupon' : (\App\Models\Voucher::where('is_active', true)->count() . ' Kupon') }}
                    </span>
                </div>
            </div>

        </div>


        <!-- 3. MENU NAVIGASI PINTASAN -->
        <div class="bg-white border border-[#EAE5DF] rounded-[24px] overflow-hidden shadow-[0_4px_15px_rgba(44,38,35,0.03)] divide-y divide-[#F0ECE7]">
            
            <!-- Riwayat Pesanan -->
            <a href="{{ route('customer.orders.index') }}" class="flex items-center justify-between p-5 hover:bg-[#FAF8F5] transition duration-200 group">
                <div class="flex items-center space-x-3.5">
                    <div class="w-9 h-9 rounded-lg bg-[#FAF8F5] border border-[#E5E0D8] flex items-center justify-center text-[#2C2623] text-xs">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <span class="text-xs font-bold text-[#2C2623]">Riwayat Pesanan</span>
                </div>
                <i class="fa-solid fa-chevron-right text-xs text-gray-400 group-hover:translate-x-0.5 transition"></i>
            </a>

            <!-- Menu Favorit -->
            <a href="{{ route('customer.favorites') }}" class="flex items-center justify-between p-5 hover:bg-[#FAF8F5] transition duration-200 group">
                <div class="flex items-center space-x-3.5">
                    <div class="w-9 h-9 rounded-lg bg-[#FAF8F5] border border-[#E5E0D8] flex items-center justify-center text-[#2C2623] text-xs">
                        <i class="fa-solid fa-heart"></i>
                    </div>
                    <span class="text-xs font-bold text-[#2C2623]">Menu Favorit</span>
                </div>
                <i class="fa-solid fa-chevron-right text-xs text-gray-400 group-hover:translate-x-0.5 transition"></i>
            </a>

        </div>


        <!-- 4. TOMBOL KELUAR AKUN -->
        <div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-3.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-[20px] text-xs font-extrabold transition duration-300 border border-red-200/60 shadow-2xs flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-right-from-bracket text-xs"></i>
                    Keluar Akun
                </button>
            </form>
        </div>

    </div>

</div>
@endsection
