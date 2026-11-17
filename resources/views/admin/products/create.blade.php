@extends('layouts.admin')

@section('title', 'Tambah Produk - TERRA Cafe')
@section('header-title', 'Tambah Produk Baru')

@section('content')
<div class="max-w-2xl bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Kategori</label>
            <select name="category_id" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none">
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Nama Produk</label>
            <input type="text" name="name" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Harga (Rp)</label>
                <input type="number" name="price" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Stok</label>
                <input type="number" name="stock" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
            </div>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Deskripsi</label>
            <textarea name="description" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm"></textarea>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Foto Produk</label>
            <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gray-900 file:text-white">
        </div>
        <button type="submit" class="w-full py-3 bg-gray-900 text-white rounded-xl font-medium hover:bg-black transition">Simpan Produk</button>
    </form>
</div>
@endsection
