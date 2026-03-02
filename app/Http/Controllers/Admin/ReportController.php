<?php
// app/Http/Controllers/Admin/ReportController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
// use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman laporan di browser.
     * 
     * Fitur:
     * 1. Filter Rentang Tanggal (Date Range)
     * 2. Summary Statistik Lengkap
     * 3. Grafik Penjualan per Kategori (Analitik)
     * 4. Tabel Detail Transaksi dengan Pagination
     * 5. Analisis Produk Terlaris per Kategori
     */
    public function sales(Request $request)
    {
        // ============================================================
        // 1. FILTER TANGGAL (dengan validasi)
        // ============================================================
        $dateFrom = $request->date_from ?? now()->startOfMonth()->toDateString();
        $dateTo = $request->date_to ?? now()->toDateString();
        
        // Validasi: tanggal tidak boleh lebih dari 1 tahun
        if (now()->parse($dateFrom)->diffInDays(now()->parse($dateTo)) > 365) {
            $dateFrom = now()->subYear()->toDateString();
        }

        // Cache key berdasarkan filter
        $cacheKey = 'sales_report_' . md5($dateFrom . $dateTo);

        // ============================================================
        // 2. SUMMARY STATISTIK (dengan Caching)
        // ============================================================
        $summary = Cache::remember($cacheKey . '_summary', now()->addMinutes(5), function () use ($dateFrom, $dateTo) {
            return Order::whereDate('created_at', '>=', $dateFrom)
                ->whereDate('created_at', '<=', $dateTo)
                ->where('payment_status', 'paid')
                ->selectRaw('
                    COUNT(*) as total_orders,
                    SUM(total_amount) as total_revenue,
                    SUM(shipping_cost) as total_shipping,
                    AVG(total_amount) as avg_order_value,
                    COUNT(DISTINCT user_id) as unique_customers
                ')
                ->first();
        });

        // ============================================================
        // 3. DATA ORDER (dengan Eager Loading + Pagination)
        // ============================================================
        $orders = Order::with(['user', 'orderItems.product.category'])
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->where('payment_status', 'paid')
            ->latest()
            ->paginate(20);

        // ============================================================
        // 4. ANALISIS PENJUALAN PER KATEGORI
        // ============================================================
        $byCategory = Cache::remember($cacheKey . '_category', now()->addMinutes(10), function () use ($dateFrom, $dateTo) {
            return DB::table('order_items')
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->join('products', 'products.id', '=', 'order_items.product_id')
                ->join('categories', 'categories.id', '=', 'products.category_id')
                ->whereDate('orders.created_at', '>=', $dateFrom)
                ->whereDate('orders.created_at', '<=', $dateTo)
                ->where('orders.payment_status', 'paid')
                ->groupBy('categories.id', 'categories.name')
                ->select([
                    'categories.id',
                    'categories.name as category_name',
                    DB::raw('SUM(order_items.quantity) as total_sold'),
                    DB::raw('SUM(order_items.subtotal) as total_revenue'),
                    DB::raw('COUNT(DISTINCT order_items.product_id) as unique_products')
                ])
                ->orderByDesc('total_revenue')
                ->get();
        });

        // ============================================================
        // 5. PRODUK TERLARIS PER KATEGORI
        // ============================================================
        $topProductsByCategory = Cache::remember($cacheKey . '_top_products', now()->addMinutes(10), function () use ($dateFrom, $dateTo) {
            return DB::table('order_items')
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->join('products', 'products.id', '=', 'order_items.product_id')
                ->join('categories', 'categories.id', '=', 'products.category_id')
                ->whereDate('orders.created_at', '>=', $dateFrom)
                ->whereDate('orders.created_at', '<=', $dateTo)
                ->where('orders.payment_status', 'paid')
                ->groupBy('categories.id', 'categories.name', 'products.id', 'products.name')
                ->select([
                    'categories.name as category_name',
                    'products.id as product_id',
                    'products.name as product_name',
                    DB::raw('SUM(order_items.quantity) as total_sold'),
                    DB::raw('SUM(order_items.subtotal) as total_revenue')
                ])
                ->orderByDesc('total_sold')
                ->limit(10)
                ->get()
                ->groupBy('category_name');
        });

        // ============================================================
        // 6. TREN PENJUALAN HARIAN (untuk Line Chart)
        // ============================================================
        $dailyTrend = Order::withoutGlobalScope('ordered')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->where('payment_status', 'paid')
            ->select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total_amount) as revenue')
            ])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->get();

        // ============================================================
        // 7. ORDER STATUS DISTRIBUTION
        // ============================================================
        $orderStatus = Order::whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('orders.status')
            ->pluck('count', 'status')
            ->toArray();

        // ============================================================
        // 8. DAFTAR KATEGORI (untuk filter dropdown)
        // ============================================================
        $categories = Category::orderBy('name')->pluck('name', 'id');

        return view('admin.reports.sales', compact(
            'orders', 
            'summary', 
            'byCategory',
            'topProductsByCategory',
            'dailyTrend',
            'orderStatus',
            'categories',
            'dateFrom', 
            'dateTo'
        ));
    }

    /**
     * Handle download Excel dengan formatting lengkap.
     * NOTE: Feature disabled - maatwebsite/excel not installed
     */
    public function exportSales(Request $request)
    {
        $dateFrom = $request->date_from ?? now()->startOfMonth()->toDateString();
        $dateTo = $request->date_to ?? now()->toDateString();

        // Validasi
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        // Temporarily disabled - need to install maatwebsite/excel package
        return redirect()->back()->with('error', 'Fitur export Excel sementara tidak tersedia. Silakan install package maatwebsite/excel.');
        
        /*
        $filename = 'laporan-penjualan-' . $dateFrom . '-sd-' . $dateTo . '.xlsx';

        return Excel::download(
            new SalesReportExport($dateFrom, $dateTo),
            $filename
        );
        */
    }

    /**
     * Export laporan penjualan ke Word.
     */
    public function exportWord(Request $request)
    {
        $dateFrom = $request->date_from ?? now()->startOfMonth()->toDateString();
        $dateTo = $request->date_to ?? now()->toDateString();

        // Validasi
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        // Get data untuk export
        $summary = Order::whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->where('payment_status', 'paid')
            ->selectRaw('
                COUNT(*) as total_orders,
                SUM(total_amount) as total_revenue,
                SUM(shipping_cost) as total_shipping,
                AVG(total_amount) as avg_order_value,
                COUNT(DISTINCT user_id) as unique_customers
            ')
            ->first();

        $orders = Order::with(['user', 'orderItems.product'])
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->where('payment_status', 'paid')
            ->latest()
            ->get();

        $byCategory = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->whereDate('orders.created_at', '>=', $dateFrom)
            ->whereDate('orders.created_at', '<=', $dateTo)
            ->where('orders.payment_status', 'paid')
            ->groupBy('categories.id', 'categories.name')
            ->select([
                'categories.name as category_name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.subtotal) as total_revenue'),
            ])
            ->orderByDesc('total_revenue')
            ->get();

        // Generate filename
        $filename = 'laporan-penjualan-' . $dateFrom . '-sd-' . $dateTo . '.doc';

        // Set headers untuk Word
        header('Content-Type: application/vnd.ms-word');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Render view ke HTML string
        $html = view('admin.reports.word-export', compact(
            'dateFrom', 'dateTo', 'summary', 'orders', 'byCategory'
        ))->render();

        echo $html;
        exit;
    }

    /**
     * API endpoint untuk data chart (JSON).
     */
    public function chartData(Request $request)
    {
        $dateFrom = $request->date_from ?? now()->startOfMonth()->toDateString();
        $dateTo = $request->date_to ?? now()->toDateString();

        $dailyTrend = Order::withoutGlobalScope('ordered')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->where('payment_status', 'paid')
            ->select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total_amount) as revenue')
            ])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->get();

        $byCategory = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->whereDate('orders.created_at', '>=', $dateFrom)
            ->whereDate('orders.created_at', '<=', $dateTo)
            ->where('orders.payment_status', 'paid')
            ->groupBy('categories.id', 'categories.name')
            ->select([
                'categories.name as category_name',
                DB::raw('SUM(order_items.subtotal) as total_revenue'),
            ])
            ->orderByDesc('total_revenue')
            ->get();

        return response()->json([
            'daily_trend' => $dailyTrend,
            'by_category' => $byCategory,
        ]);
    }
}
