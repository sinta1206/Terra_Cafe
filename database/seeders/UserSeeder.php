<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Admin
        User::create([
            'name' => 'Admin Terra Cafe',
            'email' => 'admin@terracafe.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '08111222333',
        ]);

        // Akun Customer
        User::create([
            'name' => 'Budi Customer',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'phone' => '088899990000',
        ]);
    }
}
