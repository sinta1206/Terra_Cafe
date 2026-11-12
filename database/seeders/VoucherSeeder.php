<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;
use Carbon\Carbon;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        Voucher::create([
            'code' => 'TERRAHEMAT',
            'name' => 'Diskon Pembukaan Terra Cafe',
            'type' => 'percent',
            'value' => 15.00, // Diskon 15%
            'min_purchase' => 50000,
            'expired_at' => Carbon::now()->addMonth(),
        ]);

        Voucher::create([
            'code' => 'KOPIJUMAT',
            'name' => 'Potongan Langsung Jumat Berkah',
            'type' => 'fixed',
            'value' => 10000, // Potongan Rp 10.000
            'min_purchase' => 30000,
            'expired_at' => Carbon::now()->addWeeks(2),
        ]);
    }
}
