<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::where('status', 'completed');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $reports = $query->latest()->paginate(15);
        $totalRevenue = (clone $query)->sum('total_amount');
        $totalTransactions = (clone $query)->count();

        return view('admin.reports.index', compact('reports', 'totalRevenue', 'totalTransactions'));
    }
}
