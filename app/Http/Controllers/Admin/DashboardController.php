<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Loan;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with comprehensive statistics for library.
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
                // Total Peminjaman (semua status)
                'total_loans' => Loan::count(),

                // Buku Sedang Dipinjam (status borrowed/approved)
                'active_loans' => Loan::whereIn('status', ['approved', 'borrowed'])->count(),

                // Buku Terlambat (overdue)
                'overdue_loans' => Loan::where(function ($q) {
                        $q->where('status', 'overdue')
                          ->orWhere(function ($q2) {
                              $q2->whereIn('status', ['approved', 'borrowed'])
                                 ->where('due_date', '<', now());
                          });
                    })->count(),

                // Total Buku Tersedia (stok > 0)
                'available_books' => Product::where('stock', '>', 0)
                    ->where('is_active', true)
                    ->count(),

                // Total Buku
                'total_books' => Product::count(),

                // Total Kategori
                'total_categories' => Category::count(),

                // Total Anggota Perpustakaan
                'total_members' => User::where('role', 'customer')->count(),

                // Stok Rendah (≤5) - Alert untuk restock
                'low_stock' => Product::where('stock', '<=', 5)
                    ->where('is_active', true)
                    ->count(),

                // Buku Habis
                'out_of_stock' => Product::where('stock', 0)
                    ->where('is_active', true)
                    ->count(),

                // Rata-rata Peminjaman per bulan
                'avg_loans_per_month' => $this->calculateAvgLoansPerMonth(),

                // Peminjaman Pending (menunggu persetujuan)
                'pending_loans' => Loan::where('status', 'pending')->count(),

                // Aduan Pending (untuk notifikasi CS)
                'complaint_pending' => Complaint::where('status', 'pending')->count(),
                'complaint_urgent' => Complaint::where('priority', 'urgent')->where('status', '!=', 'resolved')->count(),
            ];
        });

        // ============================================================
        // 2. PEMINJAMAN TERBARU (Recent Loans)
        // ============================================================
        $recentLoans = Loan::with(['user', 'book.category', 'book.primaryImage'])
            ->latest()
            ->take(10)
            ->get();

        // ============================================================
        // 3. BUKU TERPOPULER (Most Popular Books - by loan count)
        // ============================================================
        $topBooks = Product::with('category', 'primaryImage')
            ->select('products.*')
            ->leftJoinSub(
                DB::table('loans')
                    ->select([
                        'book_id',
                        DB::raw('COUNT(*) as loan_count')
                    ])
                    ->groupBy('book_id'),
                'loan_counts',
                'products.id',
                '=',
                'loan_counts.book_id'
            )
            ->whereNotNull('loan_counts.loan_count')
            ->orderByDesc('loan_counts.loan_count')
            ->take(5)
            ->get();

        // ============================================================
        // 4. DATA CHART: PEMINJAMAN 7 HARI TERAKHIR
        // ============================================================
        $loansChart = Loan::select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
            ])
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->get();

        // ============================================================
        // 5. DATA CHART: PEMINJAMAN BULANAN (12 Bulan)
        // ============================================================
        $monthlyLoans = Loan::select([
                DB::raw("TO_CHAR(created_at, 'YYYY-MM') as month"),
                DB::raw('COUNT(*) as loans'),
            ])
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy(DB::raw("TO_CHAR(created_at, 'YYYY-MM')"))
            ->orderBy('month', 'asc')
            ->get();

        // ============================================================
        // 6. PEMINJAMAN PER KATEGORI (untuk Doughnut Chart)
        // ============================================================
        $loansByCategory = DB::table('loans')
            ->join('products', 'products.id', '=', 'loans.book_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->where('loans.created_at', '>=', now()->subDays(30))
            ->groupBy('categories.id', 'categories.name')
            ->select([
                'categories.name as category_name',
                DB::raw('COUNT(loans.id) as total_loans'),
            ])
            ->orderByDesc('total_loans')
            ->get();

        // ============================================================
        // 7. ANGGOTA TERLAMBAT (Members with Overdue Books)
        // ============================================================
        $overdueMembers = Loan::with(['user', 'book'])
            ->where(function ($q) {
                $q->where('status', 'overdue')
                  ->orWhere(function ($q2) {
                      $q2->whereIn('status', ['approved', 'borrowed'])
                         ->where('due_date', '<', now());
                  });
            })
            ->latest()
            ->take(5)
            ->get();

        // ============================================================
        // 8. AKTIVITAS TERBARU (Activity Log)
        // ============================================================
        $recentActivities = Loan::with(['user', 'book'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($loan) {
                $statusText = match($loan->status) {
                    'pending' => 'menunggu persetujuan',
                    'approved' => 'disetujui',
                    'borrowed' => 'dipinjam',
                    'returned' => 'dikembalikan',
                    'overdue' => 'terlambat',
                    'cancelled' => 'dibatalkan',
                    default => $loan->status,
                };
                return [
                    'type' => $loan->status === 'returned' ? 'return' : 'loan',
                    'message' => $loan->user->name . ' ' . $statusText . ' "' . ($loan->book->name ?? 'Buku') . '"',
                    'time' => $loan->created_at->diffForHumans(),
                    'status' => $loan->status,
                ];
            });

        // Complaint count untuk sidebar notification
        $complaintCount = $stats['complaint_pending'] + $stats['complaint_urgent'];

        // Get recent complaints for notification dropdown
        $recentComplaints = Complaint::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Get pending loans count for notifications
        $pendingCount = $stats['pending_loans'];

        return view('admin.dashboard', compact(
            'stats', 
            'recentLoans', 
            'topBooks', 
            'loansChart',
            'monthlyLoans',
            'loansByCategory',
            'recentActivities',
            'overdueMembers',
            'complaintCount',
            'recentComplaints',
            'pendingCount'
        ));
    }

    /**
     * Calculate average loans per month
     */
    private function calculateAvgLoansPerMonth(): int
    {
        $totalLoans = Loan::count();
        if ($totalLoans == 0) return 0;
        
        $firstLoan = Loan::oldest('created_at')->first();
        if (!$firstLoan) return 0;
        
        $months = now()->diffInMonths($firstLoan->created_at) + 1;
        return round($totalLoans / max(1, $months));
    }
}

