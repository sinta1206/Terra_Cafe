@extends('layouts.admin')

@section('title', 'Tambah Kategori - TERRA Cafe')
@section('header-title', 'Tambah Kategori Baru')

@section('content')
<div class="max-w-xl bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
    <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Nama Kategori</label>
            <input type="text" name="name" required placeholder="Contoh: Coffee"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Deskripsi (Opsional)</label>
            <textarea name="description" rows="3" placeholder="Keterangan kategori..."
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800"></textarea>
        </div>
        <button type="submit" class="w-full py-3 bg-gray-900 text-white rounded-xl font-medium hover:bg-black transition">Simpan Kategori</button>
    </form>
</div>
@endsection
