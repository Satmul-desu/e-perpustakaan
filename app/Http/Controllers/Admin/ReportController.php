<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());
        $orderQuery = Order::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->where('payment_status', 'paid')
            ->whereIn('status', ['processing', 'completed']);
        if ($request->has('status') && $request->status) {
            $orderQuery->where('status', $request->status);
        }
        $ordersForStats = clone $orderQuery;
        $totalRevenue = (float) $ordersForStats->sum('total_amount');
        $totalOrders = $ordersForStats->count();
        $uniqueCustomers = $ordersForStats->distinct('user_id')->count('user_id');
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        $totalShipping = (float) $ordersForStats->sum('shipping_cost');
        $summary = (object) [
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'unique_customers' => $uniqueCustomers,
            'avg_order_value' => $avgOrderValue,
            'total_shipping' => $totalShipping,
        ];
        $orders = $orderQuery->with(['user', 'orderItems'])->orderBy('created_at', 'desc')->paginate(20);
        $byCategory = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('orders.created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->where('orders.payment_status', 'paid')
            ->whereIn('orders.status', ['processing', 'completed'])
            ->select(
                'categories.id as category_id',
                'categories.name as category_name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->get();
        $dailyTrend = DB::table('orders')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->where('payment_status', 'paid')
            ->whereIn('status', ['processing', 'completed'])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'orders' => (int) $item->orders,
                    'revenue' => (float) $item->revenue,
                ];
            });
        $topProductsByCategory = $byCategory->map(function ($category) {
            $products = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->whereBetween('orders.created_at', [$dateFrom, $dateTo . ' 23:59:59'])
                ->where('orders.payment_status', 'paid')
                ->whereIn('orders.status', ['processing', 'completed'])
                ->where('products.category_id', $category->category_id)
                ->select(
                    'products.id',
                    'products.name as product_name',
                    DB::raw('SUM(order_items.quantity) as total_sold'),
                    DB::raw('SUM(order_items.subtotal) as total_revenue')
                )
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('total_sold')
                ->limit(5)
                ->get();
            return $products;
        });
        return view('admin.reports.sales', compact(
            'dateFrom',
            'dateTo',
            'summary',
            'byCategory',
            'dailyTrend',
            'topProductsByCategory',
            'orders'
        ));
    }
    public function loans(Request $request)
    {
        $query = Loan::with(['user', 'book.category']);
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('loan_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('loan_date', '<=', $request->date_to);
        }
        $loans = $query->orderBy('loan_date', 'desc')->paginate(20);
        $stats = [
            'total' => Loan::count(),
            'pending' => Loan::where('status', 'pending')->count(),
            'borrowed' => Loan::where('status', 'borrowed')->count(),
            'returned' => Loan::where('status', 'returned')->count(),
            'overdue' => Loan::overdue()->count(),
        ];
        return view('admin.reports.loans', compact('loans', 'stats'));
    }
    public function exportLoans(Request $request)
    {
        $query = Loan::with(['user', 'book']);
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('loan_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('loan_date', '<=', $request->date_to);
        }
        $loans = $query->get();
        $csvData = [];
        $csvData[] = ['No', 'Peminjam', 'Email', 'Buku', 'Kategori', 'Tgl Pinjam', 'Jatuh Tempo', 'Tgl Kembali', 'Status', 'Durasi (hari)'];
        $statusText = [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'borrowed' => 'Dipinjam',
            'returned' => 'Dikembalikan',
            'overdue' => 'Terlambat',
            'cancelled' => 'Dibatalkan'
        ];
        foreach ($loans as $index => $loan) {
            $csvData[] = [
                $index + 1,
                $loan->user->name,
                $loan->user->email,
                $loan->book->name,
                $loan->book->category->name ?? '-',
                $loan->loan_date->format('Y-m-d'),
                $loan->due_date->format('Y-m-d'),
                $loan->return_date ? $loan->return_date->format('Y-m-d') : '-',
                $statusText[$loan->status] ?? $loan->status,
                $loan->loan_duration
            ];
        }
        $filename = 'laporan_peminjaman_' . date('Y-m-d_His') . '.csv';
        $handle = fopen('php:
        foreach ($csvData as $line) {
            fputcsv($handle, $line);
        }
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);
        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}