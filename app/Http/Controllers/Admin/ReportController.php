<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Query utama hanya untuk pesanan yang berstatus selesai (completed)
        $query = Order::where('status', 'completed');

        // Filter rentang tanggal jika dipilih oleh admin
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // Ringkasan Statistik
        $totalRevenue = (clone $query)->sum('total_amount');
        $totalTransactions = (clone $query)->count();

        // Menghitung total item produk yang terjual
        $completedOrderIds = (clone $query)->pluck('id');
        $totalItemsSold = OrderItem::whereIn('order_id', $completedOrderIds)->sum('quantity');

        // Daftar produk terlaris (akumulasi produk terjual)
        $topProducts = OrderItem::select('product_id',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(price * quantity) as total_sales'))
            ->whereIn('order_id', $completedOrderIds)
            ->with('product.category')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->get();

        $reports = $query->latest()->paginate(15);

        return view('admin.reports.index', compact(
            'reports',
            'totalRevenue',
            'totalTransactions',
            'totalItemsSold',
            'topProducts'
        ));
    }

    // Fitur Tombol "Ekspor CSV" sesuai desain Figma
    public function exportCsv(Request $request)
    {
        $filename = 'laporan-penjualan-terracafe-' . date('Y-m-d') . '.csv';

        $orders = Order::with('user')->where('status', 'completed')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Invoice', 'Customer', 'Tanggal', 'Total Belanja', 'Status']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->invoice_number,
                    $order->user->name ?? 'Guest',
                    $order->created_at,
                    $order->total_amount,
                    $order->status
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
