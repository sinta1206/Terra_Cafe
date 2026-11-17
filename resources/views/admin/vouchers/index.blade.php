@extends('layouts.admin')

@section('title', 'Kelola Voucher - TERRA Cafe')
@section('header-title', 'Kelola Voucher & Promo')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-900">Daftar Voucher</h2>
        <a href="{{ route('admin.vouchers.create') }}" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-black">Buat Voucher</a>
    </div>

    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50 text-xs font-semibold text-gray-500 uppercase">
                    <th class="p-4">Kode</th>
                    <th class="p-4">Nama Promo</th>
                    <th class="p-4">Diskon</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @foreach($vouchers as $voucher)
                    <tr>
                        <td class="p-4 font-semibold text-gray-900">{{ $voucher->code }}</td>
                        <td class="p-4 text-gray-700">{{ $voucher->name }}</td>
                        <td class="p-4 text-gray-900">{{ $voucher->value }}%</td>
                        <td class="p-4 text-right">
                            <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST">
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
