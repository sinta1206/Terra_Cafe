<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'cafe_name', 'value' => 'Terra Cafe'],
            ['key' => 'cafe_address', 'value' => 'Jl. Merdeka No. 45, Kota Anda'],
            ['key' => 'cafe_phone', 'value' => '081234567890'],
            ['key' => 'tax_percentage', 'value' => '10'], // Pajak 10%
            ['key' => 'qris_merchant_id', 'value' => 'ID1029384756QRS'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
