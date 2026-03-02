<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with comprehensive statistics.
     * 
     * Performance Optimizations:
     * - Uses Eager Loading to prevent N+1 queries
     * - Uses database aggregates (SUM, COUNT) instead of PHP loops
     * - Implements caching for data that rarely changes
     */
    public function index()
    {
        // ============================================================
        // 1. STATISTIK UTAMA (Cards) - with Caching
        // ============================================================
        // Cache key dengan timestamp untuk invalidation otomatis
        $cacheKey = 'admin_stats_' . now()->hour . '_' . now()->minute;
        
        $stats = Cache::remember($cacheKey, now()->addMinutes(5), function () {
            return [
                // Total Pendapatan (hanya dari order yang sudah dibayar)
                'total_revenue' => Order::where('payment_status', 'paid')
                    ->whereIn('status', ['processing', 'completed', 'delivered'])
                    ->sum('total_amount'),

                // Total Pesanan (semua status)
                'total_orders' => Order::count(),

                // Pesanan Pending (menunggu konfirmasi)
                'pending_orders' => Order::where('status', 'pending')
                    ->where('payment_status', 'paid')
                    ->count(),

                // Pesanan Sedang Diproses
                'processing_orders' => Order::where('status', 'processing')->count(),

                // Total Produk
                'total_products' => Product::count(),

                // Total Kategori
                'total_categories' => Category::count(),

                // Total Pelanggan (role = customer)
                'total_customers' => User::where('role', 'customer')->count(),

                // Stok Rendah (≤5) - Alert untuk restock
                'low_stock' => Product::where('stock', '<=', 5)
                    ->where('is_active', true)
                    ->count(),

                // Produk Habis
                'out_of_stock' => Product::where('stock', 0)
                    ->where('is_active', true)
                    ->count(),

                // Rata-rata Nilai Pesanan
                'avg_order_value' => Order::where('payment_status', 'paid')
                    ->avg('total_amount') ?? 0,

                // Aduan Pending (untuk notifikasi CS)
                'complaint_pending' => Complaint::where('status', 'pending')->count(),
                'complaint_urgent' => Complaint::where('priority', 'urgent')->where('status', '!=', 'resolved')->count(),
            ];
        });

        // ============================================================
        // 2. PESANAN TERBARU (Recent Orders)
        // ============================================================
        // Eager load 'user' dan 'orderItems' untuk menghindari N+1
        $recentOrders = Order::with(['user', 'orderItems.product'])
            ->latest()
            ->take(10)
            ->get();

        // ============================================================
        // 3. PRODUK TERLARIS (Top Products)
        // ============================================================
        // Using raw subquery for PostgreSQL compatibility
        $topProducts = Product::withoutGlobalScope('ordered')
            ->with('category', 'primaryImage')
            ->select('products.*')
            ->leftJoinSub(
                DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('orders.payment_status', 'paid')
                    ->select([
                        'order_items.product_id',
                        DB::raw('COALESCE(SUM(order_items.quantity), 0) as sold_count')
                    ])
                    ->groupBy('order_items.product_id'),
                'sold_counts',
                'products.id',
                '=',
                'sold_counts.product_id'
            )
            ->where('sold_counts.sold_count', '>', 0)
            ->orderByDesc('sold_counts.sold_count')
            ->take(5)
            ->get();

        // ============================================================
        // 4. DATA CHART: PENDAPATAN 7 HARI TERAKHIR
        // ============================================================
        $revenueChart = Order::withoutGlobalScope('ordered')
            ->select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total'),
            ])
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->get();

        // ============================================================
        // 5. DATA CHART: PENJUALAN BULANAN (12 Bulan)
        // ============================================================
        $monthlySales = Order::withoutGlobalScope('ordered')
            ->select([
                DB::raw("TO_CHAR(created_at, 'YYYY-MM') as month"),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total_amount) as revenue'),
            ])
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy(DB::raw("TO_CHAR(created_at, 'YYYY-MM')"))
            ->orderBy('month', 'asc')
            ->get();

        // ============================================================
        // 6. PENJUALAN PER KATEGORI (untuk Doughnut Chart)
        // ============================================================
        $salesByCategory = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->where('orders.payment_status', 'paid')
            ->where('orders.created_at', '>=', now()->subDays(30))
            ->groupBy('categories.id', 'categories.name')
            ->select([
                'categories.name as category_name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.subtotal) as total_revenue'),
            ])
            ->orderByDesc('total_revenue')
            ->get();

        // ============================================================
        // 7. AKTIVITAS TERBARU (Activity Log)
        // ============================================================
        $recentActivities = Order::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'type' => $order->payment_status === 'paid' ? 'payment' : 'order',
                    'message' => $order->user->name . ' memesan #' . $order->order_number,
                    'amount' => $order->total_amount,
                    'time' => $order->created_at->diffForHumans(),
                    'status' => $order->status,
                ];
            });

        // Complaint count untuk sidebar notification
        $complaintCount = $stats['complaint_pending'] + $stats['complaint_urgent'];

        // Get recent complaints for notification dropdown
        $recentComplaints = Complaint::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Get pending orders count for notifications
        $pendingCount = $stats['pending_orders'];

        return view('admin.dashboard', compact(
            'stats', 
            'recentOrders', 
            'topProducts', 
            'revenueChart',
            'monthlySales',
            'salesByCategory',
            'recentActivities',
            'complaintCount',
            'recentComplaints',
            'pendingCount'
        ));
    }
}
