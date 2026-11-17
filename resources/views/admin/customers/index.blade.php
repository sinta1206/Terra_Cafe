@extends('layouts.admin')

@section('title', 'Data Customer - TERRA Cafe')
@section('header-title', 'Daftar Pelanggan')

@section('content')
<div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50 text-xs font-semibold text-gray-500 uppercase">
                <th class="p-4">Nama Pelanggan</th>
                <th class="p-4">Email</th>
                <th class="p-4">Nomor Telepon</th>
                <th class="p-4">Total Pesanan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm">
            @foreach($customers as $customer)
                <tr>
                    <td class="p-4 font-semibold text-gray-900">{{ $customer->name }}</td>
                    <td class="p-4 text-gray-600">{{ $customer->email }}</td>
                    <td class="p-4 text-gray-600">{{ $customer->phone ?? '-' }}</td>
                    <td class="p-4 text-gray-900">{{ $customer->orders_count }} Pesanan</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
