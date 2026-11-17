@extends('layouts.admin')

@section('title', 'Kelola Produk - TERRA Cafe')
@section('header-title', 'Kelola Produk Menu')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-900">Daftar Menu Kafe</h2>
        <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-black">Tambah Produk</a>
    </div>

    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50 text-xs font-semibold text-gray-500 uppercase">
                    <th class="p-4">Nama Produk</th>
                    <th class="p-4">Kategori</th>
                    <th class="p-4">Harga</th>
                    <th class="p-4">Stok</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @foreach($products as $product)
                    <tr>
                        <td class="p-4 font-semibold text-gray-900">{{ $product->name }}</td>
                        <td class="p-4 text-gray-500">{{ $product->category->name ?? '-' }}</td>
                        <td class="p-4 text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="p-4 text-gray-700">{{ $product->stock }}</td>
                        <td class="p-4 text-right space-x-2">
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline">
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
