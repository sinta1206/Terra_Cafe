@extends('layouts.admin')

@section('title', 'Kelola Kategori - TERRA Cafe')
@section('header-title', 'Kelola Kategori Menu')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-900">Daftar Kategori</h2>
        <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-black">Tambah Kategori</a>
    </div>

    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50 text-xs font-semibold text-gray-500 uppercase">
                    <th class="p-4">Nama Kategori</th>
                    <th class="p-4">Slug</th>
                    <th class="p-4">Jumlah Produk</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @foreach($categories as $category)
                    <tr>
                        <td class="p-4 font-semibold text-gray-900">{{ $category->name }}</td>
                        <td class="p-4 text-gray-500">{{ $category->slug }}</td>
                        <td class="p-4 text-gray-700">{{ $category->products_count }} Produk</td>
                        <td class="p-4 text-right">
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
