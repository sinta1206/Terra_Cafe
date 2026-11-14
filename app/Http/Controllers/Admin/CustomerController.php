<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer');

        // Fitur pencarian nama/email customer sesuai input search di desain
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Mengambil data customer dengan tambahan informasi relasi pesanannya
        $customers = $query->withCount('orders')
                           ->withSum(['orders' => function($q) {
                               $q->where('status', 'completed');
                           }], 'total_amount')
                           ->latest()
                           ->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }
}
