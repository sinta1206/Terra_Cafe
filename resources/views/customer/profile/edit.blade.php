@extends('layouts.app')

@section('title', 'Profil Saya - TERRA Cafe')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-8 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-bold text-gray-900">Profil Saya</h1>
            <a href="{{ route('customer.profile.edit') }}" class="px-4 py-2 bg-gray-900 text-white text-xs rounded-xl font-medium hover:bg-black">Edit Profil</a>
        </div>

        <div class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase">Nama Lengkap</label>
                <p class="text-gray-900 font-medium mt-1">{{ $user->name }}</p>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase">Email</label>
                <p class="text-gray-900 font-medium mt-1">{{ $user->email }}</p>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase">Nomor Telepon</label>
                <p class="text-gray-900 font-medium mt-1">{{ $user->phone ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
