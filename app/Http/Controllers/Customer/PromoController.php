<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Voucher;

class PromoController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::where('expired_at', '>', now())->get();
        return view('customer.promos', compact('vouchers'));
    }
}
