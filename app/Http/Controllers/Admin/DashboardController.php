<?php
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
    public function index()
    {
        $cacheKey = 'admin_stats_' . now()->hour . '_' . now()->minute;
        $stats = Cache::remember($cacheKey, now()->addMinutes(5), function () {
            return [
                'total_loans' => Loan::count(),
                'active_loans' => Loan::whereIn('status', ['approved', 'borrowed'])->count(),
                'overdue_loans' => Loan::where(function ($q) {
                        $q->where('status', 'overdue')
                          ->orWhere(function ($q2) {
                              $q2->whereIn('status', ['approved', 'borrowed'])
                                 ->where('due_date', '<', now());
                          });
                    })->count(),
                'available_books' => Product::where('stock', '>', 0)
                    ->where('is_active', true)
                    ->count(),
                    'total_books' => Product::count(),
                'total_categories' => Category::count(),
                'total_members' => User::where('role', 'customer')->count(),
                                'low_stock' => Product::where('stock', '<=', 5)
                    ->where('is_active', true)
                    ->count(),
                'out_of_stock' => Product::where('stock', 0)
                    ->where('is_active', true)
                    ->count(),
                'avg_loans_per_month' => $this->calculateAvgLoansPerMonth(),
                'pending_loans' => Loan::where('status', 'pending')->count(),
                'complaint_pending' => Complaint::where('status', 'pending')->count(),
                'complaint_urgent' => Complaint::where('priority', 'urgent')->where('status', '!=', 'resolved')->count(),
            ];
        });
        $recentLoans = Loan::with(['user', 'book.category', 'book.primaryImage'])
            ->latest()
            ->take(10)
            ->get();
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
        $loansChart = Loan::select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
            ])
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->get();
        $monthlyLoans = Loan::select([
                DB::raw("TO_CHAR(created_at, 'YYYY-MM') as month"),
                DB::raw('COUNT(*) as loans'),
            ])
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy(DB::raw("TO_CHAR(created_at, 'YYYY-MM')"))
            ->orderBy('month', 'asc')
            ->get();
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
        $complaintCount = $stats['complaint_pending'] + $stats['complaint_urgent'];
        $recentComplaints = Complaint::with('user')
            ->latest()
            ->take(5)
            ->get();
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