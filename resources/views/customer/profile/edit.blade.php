@extends('layouts.app')

@section('title', 'Edit Profil - TERRA Cafe')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <!-- Header Judul -->
    <div class="space-y-1">
        <span class="text-[10px] font-extrabold tracking-[3px] text-[#8C7A6B] uppercase">AKUN TERRA KAMU</span>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#2C2623]">
            Profil Saya
        </h1>
    </div>

    <!-- Layout Dua Kolom -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- KOLOM KIRI: Kartu Ringkasan Profil & Statistik -->
        <div class="lg:col-span-4 bg-white border border-[#EAE5DF] rounded-[28px] p-6 sm:p-8 shadow-[0_4px_20px_rgba(44,38,35,0.03)] text-center space-y-6">
            
            <!-- Avatar & Identitas -->
            <div class="space-y-3 flex flex-col items-center">
                <div class="w-20 h-20 rounded-full bg-[#EFECE6] text-[#2C2623] font-bold flex items-center justify-center text-2xl border border-[#E5E0D8]">
                    @php
                        $names = explode(' ', $user->name);
                        $initials = strtoupper(substr($names[0], 0, 1) . (isset($names[1]) ? substr($names[1], 0, 1) : ''));
                    @endphp
                    {{ $initials }}
                </div>
                
                <div class="space-y-0.5">
                    <h3 class="font-extrabold text-[#2C2623] text-lg">{{ $user->name }}</h3>
                    <p class="text-xs text-gray-500 font-medium">{{ $user->email }}</p>
                </div>
            </div>

            <!-- Kotak Statistik (Total Pesanan & Menu Favorit) -->
            <div class="grid grid-cols-2 gap-3 pt-2">
                <div class="bg-[#FAF8F5] border border-[#F0ECE7] rounded-[20px] p-4 text-center space-y-1">
                    <span class="font-extrabold text-[#2C2623] text-base block">{{ $ordersCount ?? 2 }}</span>
                    <span class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider block">Total pesanan</span>
                </div>
                <div class="bg-[#FAF8F5] border border-[#F0ECE7] rounded-[20px] p-4 text-center space-y-1">
                    <span class="font-extrabold text-[#2C2623] text-base block">{{ $favoritesCount ?? 2 }}</span>
                    <span class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider block">Menu favorit</span>
                </div>
            </div>

        </div>


        <!-- KOLOM KANAN: Formulir Edit Profil -->
        <div class="lg:col-span-8 bg-white border border-[#EAE5DF] rounded-[28px] p-6 sm:p-8 shadow-[0_4px_20px_rgba(44,38,35,0.03)]">
            
            <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-[#2C2623]">Nama lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-4 py-3 bg-[#FAF8F5] border border-[#EAE5DF] rounded-xl text-xs font-medium text-[#2C2623] focus:outline-none focus:border-[#2C2623] transition">
                        @error('name')
                            <span class="text-[10px] text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-[#2C2623]">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-4 py-3 bg-[#FAF8F5] border border-[#EAE5DF] rounded-xl text-xs font-medium text-[#2C2623] focus:outline-none focus:border-[#2C2623] transition">
                        @error('email')
                            <span class="text-[10px] text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nomor HP -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-[#2C2623]">Nomor HP</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="081234567890"
                               class="w-full px-4 py-3 bg-[#FAF8F5] border border-[#EAE5DF] rounded-xl text-xs font-medium text-[#2C2623] focus:outline-none focus:border-[#2C2623] transition">
                        @error('phone')
                            <span class="text-[10px] text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Alamat Utama -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-[#2C2623]">Alamat utama</label>
                        <input type="text" name="address" value="{{ old('address', $user->address ?? '') }}" placeholder="Jl. Soekarno Hatta No. 28, Malang"
                               class="w-full px-4 py-3 bg-[#FAF8F5] border border-[#EAE5DF] rounded-xl text-xs font-medium text-[#2C2623] focus:outline-none focus:border-[#2C2623] transition">
                        @error('address')
                            <span class="text-[10px] text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Tombol Simpan Perubahan -->
                <div class="pt-4">
                    <button type="submit" class="px-6 py-3 bg-[#4A5342] hover:bg-[#3B4235] text-white rounded-xl text-xs font-extrabold transition duration-300 shadow-sm cursor-pointer">
                        Simpan perubahan
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>
@endsection
