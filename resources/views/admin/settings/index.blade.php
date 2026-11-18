@extends('layouts.admin')

@section('title', 'Pengaturan Cafe - TERRA Cafe')
@section('header-title', 'Pengaturan Sistem & Profil Kafe')

@section('content')
<div class="space-y-6">

    <!-- HEADER PENGATURAN -->
    <div>
        <span class="text-[10px] font-bold tracking-widest text-gray-400 uppercase block">KONFIGURASI</span>
        <h1 class="text-2xl font-bold text-[#2C2623]">Pengaturan kafe</h1>
        <p class="text-xs text-gray-500 mt-0.5">Kelola informasi profil, kontak, media sosial, dan jam operasional kafe.</p>
    </div>

    <!-- CARD FORM PENGATURAN -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-sm max-w-3xl">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <!-- Nama Kafe -->
            <div>
                <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-1">Nama Kafe</label>
                <input type="text" name="cafe_name" value="{{ $settings['cafe_name'] ?? 'TERRA Cafe' }}" class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:bg-white focus:ring-1 focus:ring-gray-300 transition">
            </div>

            <!-- Nomor Kontak / Telepon -->
            <div>
                <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-1">Nomor Kontak / Telepon</label>
                <input type="text" name="cafe_phone" value="{{ $settings['cafe_phone'] ?? '' }}" placeholder="Contoh: 081234567890" class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:bg-white focus:ring-1 focus:ring-gray-300 transition">
            </div>

            <!-- Instagram URL -->
            <div>
                <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-1">Link Instagram</label>
                <input type="url" name="instagram_url" value="{{ $settings['instagram_url'] ?? '' }}" placeholder="https://instagram.com/terracafe" class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:bg-white focus:ring-1 focus:ring-gray-300 transition">
            </div>

            <!-- TikTok URL -->
            <div>
                <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-1">Link TikTok</label>
                <input type="url" name="tiktok_url" value="{{ $settings['tiktok_url'] ?? '' }}" placeholder="https://tiktok.com/@terracafe" class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:bg-white focus:ring-1 focus:ring-gray-300 transition">
            </div>

            <!-- Jam Operasional -->
            <div>
                <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-1">Jam Operasional (Contoh: 09.00 - 22.00 WIB)</label>
                <input type="text" name="cafe_hours" value="{{ $settings['cafe_hours'] ?? '09.00 - 22.00 WIB' }}" class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:bg-white focus:ring-1 focus:ring-gray-300 transition">
            </div>

            <!-- Alamat Kafe -->
            <div>
                <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-1">Alamat Kafe</label>
                <textarea name="cafe_address" rows="3" class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:bg-white focus:ring-1 focus:ring-gray-300 transition">{{ $settings['cafe_address'] ?? '' }}</textarea>
            </div>

            <!-- Teks Tentang Kami -->
            <div>
                <label class="block text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-1">Teks "Tentang Kami" (Cerita Cafe)</label>
                <textarea name="about_text" rows="4" class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:bg-white focus:ring-1 focus:ring-gray-300 transition">{{ $settings['about_text'] ?? '' }}</textarea>
            </div>

            <!-- Tombol Simpan -->
            <div class="pt-2">
                <button type="submit" class="w-full py-3 bg-[#2C2623] hover:bg-black text-white rounded-xl text-xs font-medium transition shadow-sm">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
