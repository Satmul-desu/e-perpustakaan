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
    public function exportLoansExcel(Request $request)
    {
        $query = Loan::with(['user', 'book']);
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('loan_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('loan_date', '<=', $request->date_to);
        }
        $loans = $query->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No')
              ->setCellValue('B1', 'Peminjam')
              ->setCellValue('C1', 'Email')
              ->setCellValue('D1', 'Buku')
              ->setCellValue('E1', 'Kategori')
              ->setCellValue('F1', 'Tgl Pinjam')
              ->setCellValue('G1', 'Jatuh Tempo')
              ->setCellValue('H1', 'Tgl Kembali')
              ->setCellValue('I1', 'Status')
              ->setCellValue('J1', 'Durasi (hari)');
              
        $statusText = [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'borrowed' => 'Dipinjam',
            'returned' => 'Dikembalikan',
            'overdue' => 'Terlambat',
            'cancelled' => 'Dibatalkan'
        ];

        $row = 2;
        foreach ($loans as $index => $loan) {
            $sheet->setCellValue("A{$row}", $index + 1)
                  ->setCellValue("B{$row}", $loan->user->name ?? '-')
                  ->setCellValue("C{$row}", $loan->user->email ?? '-')
                  ->setCellValue("D{$row}", $loan->book->name ?? '-')
                  ->setCellValue("E{$row}", $loan->book->category->name ?? '-')
                  ->setCellValue("F{$row}", $loan->loan_date ? $loan->loan_date->format('Y-m-d') : '-')
                  ->setCellValue("G{$row}", $loan->due_date ? $loan->due_date->format('Y-m-d') : '-')
                  ->setCellValue("H{$row}", $loan->return_date ? $loan->return_date->format('Y-m-d') : '-')
                  ->setCellValue("I{$row}", $statusText[$loan->status] ?? $loan->status)
                  ->setCellValue("J{$row}", $loan->loan_duration ?? '-');
            $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'laporan_peminjaman_' . date('Y-m-d_His') . '.xlsx';
        
        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();
        
        return response($content)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    public function exportLoansWord(Request $request)
    {
        $query = Loan::with(['user', 'book']);
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('loan_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('loan_date', '<=', $request->date_to);
        }
        $loans = $query->get();

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection(['orientation' => 'landscape']);
        
        $section->addTitle('Laporan Peminjaman', 1);
        
        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 50
        ];
        $phpWord->addTableStyle('Loans Table', $tableStyle);
        $table = $section->addTable('Loans Table');
        
        $table->addRow();
        $headers = ['No', 'Peminjam', 'Buku', 'Kategori', 'Tgl Pinjam', 'Jatuh Tempo', 'Status'];
        foreach ($headers as $header) {
            $table->addCell(2000)->addText($header, ['bold' => true]);
        }
        
        $statusText = [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'borrowed' => 'Dipinjam',
            'returned' => 'Dikembalikan',
            'overdue' => 'Terlambat',
            'cancelled' => 'Dibatalkan'
        ];

        foreach ($loans as $index => $loan) {
            $table->addRow();
            $table->addCell(500)->addText($index + 1);
            $table->addCell(2000)->addText($loan->user->name ?? '-');
            $table->addCell(2500)->addText(substr($loan->book->name ?? '-', 0, 30));
            $table->addCell(1500)->addText($loan->book->category->name ?? '-');
            $table->addCell(1500)->addText($loan->loan_date ? $loan->loan_date->format('Y-m-d') : '-');
            $table->addCell(1500)->addText($loan->due_date ? $loan->due_date->format('Y-m-d') : '-');
            $table->addCell(1500)->addText($statusText[$loan->status] ?? $loan->status);
        }

        $filename = 'laporan_peminjaman_' . date('Y-m-d_His') . '.docx';
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        
        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();
        
        return response($content)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}