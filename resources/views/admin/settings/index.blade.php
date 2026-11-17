@extends('layouts.admin')

@section('title', 'Pengaturan Cafe - TERRA Cafe')
@section('header-title', 'Pengaturan Sistem Kafe')

@section('content')
<div class="max-w-xl bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Nama Kafe</label>
            <input type="text" name="cafe_name" value="{{ $settings['cafe_name'] ?? 'TERRA Cafe' }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Nomor Kontak</label>
            <input type="text" name="cafe_phone" value="{{ $settings['cafe_phone'] ?? '' }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Alamat Kafe</label>
            <textarea name="cafe_address" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">{{ $settings['cafe_address'] ?? '' }}</textarea>
        </div>
        <button type="submit" class="w-full py-3 bg-gray-900 text-white rounded-xl font-medium hover:bg-black transition">Simpan Pengaturan</button>
    </form>
</div>
@endsection
