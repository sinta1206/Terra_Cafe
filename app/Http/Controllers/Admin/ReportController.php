<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem; // Sesuaikan dengan nama model item pesanan Anda

class ReportController extends Controller
{
    public function index()
    {
        // 1. Total nilai pesanan (keseluruhan atau yang berstatus selesai/paid)
        $totalRevenue = Order::whereIn('status', ['paid', 'processing', 'completed'])->sum('total_amount');

        // 2. Jumlah transaksi selesai
        $completedTransactionsCount = Order::where('status', 'completed')->count();

        // 3. Total produk terjual (akumulasi quantity dari item pesanan)
        // Sesuaikan relasi jika menggunakan orderItems / items
        $totalItemsSold = OrderItem::whereHas('order', function($q) {
            $q->whereIn('status', ['paid', 'processing', 'completed']);
        })->sum('quantity');

        // 4. Rata-rata pesanan per customer
        $averageOrderValue = $completedTransactionsCount > 0 ? $totalRevenue / $completedTransactionsCount : 0;

        // 5. Produk terlaris (dikelompokkan berdasarkan produk)
        $topProducts = OrderItem::with('product')
            ->select('product_id', \DB::raw('SUM(quantity) as total_qty'), \DB::raw('SUM(price * quantity) as total_sales'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();



        return view('admin.reports.index', compact(

            'totalRevenue',
            'completedTransactionsCount',
            'totalItemsSold',
            'averageOrderValue',
            'topProducts'
        ));
    }

    public function exportCsv()
    {
        // Log sederhana ekspor CSV laporan
        $filename = 'laporan-penjualan-' . date('Y-m-d') . '.csv';
        
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $orders = Order::with('user')->whereIn('status', ['paid', 'processing', 'completed'])->get();

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Invoice', 'Pelanggan', 'Tanggal', 'Total', 'Status']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->invoice_number,
                    $order->user->name ?? 'Tamu',
                    $order->created_at->format('Y-m-d H:i'),
                    $order->total_amount,
                    $order->status
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
