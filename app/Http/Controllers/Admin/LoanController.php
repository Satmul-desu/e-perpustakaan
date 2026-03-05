<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    /**
     * Display list of all loans (admin view)
     */
    public function index(Request $request)
    {
        $query = Loan::with(['user', 'book.category']);
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('loan_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('loan_date', '<=', $request->date_to);
        }
        
        // Search by user name or book name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('book', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        
        $loans = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Statistics
        $stats = [
            'total' => Loan::count(),
            'pending' => Loan::where('status', Loan::STATUS_PENDING)->count(),
            'borrowed' => Loan::where('status', Loan::STATUS_BORROWED)->count(),
            'returned' => Loan::where('status', Loan::STATUS_RETURNED)->count(),
            'overdue' => Loan::overdue()->count(),
        ];
        
        return view('admin.loans.index', compact('loans', 'stats'));
    }

    /**
     * Show loan details
     */
    public function show(Loan $loan)
    {
        $loan->load('user', 'book.category', 'approver', 'returnHandler');
        
        return view('admin.loans.show', compact('loan'));
    }

    /**
     * Approve loan request
     * Modified: Now directly sets status to BORROWED with 45 hours duration
     */
    public function approve(Request $request, Loan $loan)
    {
        if ($loan->status !== Loan::STATUS_PENDING) {
            return redirect()->back()
                ->with('error', 'Hanya peminjaman dengan status Menunggu yang dapat disetujui.');
        }

        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        // Langsung set ke BORROWED dengan 45 jam
        $loan->approve(Auth::user(), true, Loan::LOAN_DURATION_45_HOURS);
        
        if ($request->notes) {
            $loan->update(['admin_notes' => $request->notes]);
        }

        return redirect()->back()
            ->with('success', 'Peminjaman disetujui. Status: Dipinjam (Durasi: 45 jam)');
    }

    /**
     * Reject loan request
     */
    public function reject(Request $request, Loan $loan)
    {
        if (!in_array($loan->status, [Loan::STATUS_PENDING, Loan::STATUS_APPROVED])) {
            return redirect()->back()
                ->with('error', 'Peminjaman tidak dapat ditolak.');
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $loan->cancel($request->reason);
        
        // Restore book stock
        $loan->book->incrementStock(1);

        return redirect()->back()
            ->with('success', 'Peminjaman berhasil ditolak.');
    }

    /**
     * Mark book as borrowed (handed to user)
     */
    public function markBorrowed(Loan $loan)
    {
        if ($loan->status !== Loan::STATUS_APPROVED) {
            return redirect()->back()
                ->with('error', 'Hanya peminjaman yang disetujui yang dapat ditandai sebagai dipinjam.');
        }

        $loan->markAsBorrowed();

        return redirect()->back()
            ->with('success', 'Buku ditandai sebagai dipinjam.');
    }

    /**
     * Process book return
     */
    public function processReturn(Request $request, Loan $loan)
    {
        if ($loan->status !== Loan::STATUS_BORROWED) {
            return redirect()->back()
                ->with('error', 'Hanya buku yang sedang dipinjam yang dapat dikembalikan.');
        }

        $request->validate([
            'condition' => 'nullable|string|max:500',
        ]);

        $loan->markAsReturned(Auth::user());
        
        if ($request->condition) {
            $loan->update(['admin_notes' => $request->condition]);
        }

        return redirect()->back()
            ->with('success', 'Buku berhasil dikembalikan.');
    }

    /**
     * Extend loan due date
     */
    public function extend(Request $request, Loan $loan)
    {
        if (!in_array($loan->status, [Loan::STATUS_APPROVED, Loan::STATUS_BORROWED])) {
            return redirect()->back()
                ->with('error', 'Peminjaman tidak dapat diperpanjang.');
        }

        $request->validate([
            'days' => 'required|integer|min:1|max:30',
            'reason' => 'nullable|string|max:500',
        ]);

        $newDueDate = $loan->due_date->addDays($request->days);
        $loan->update([
            'due_date' => $newDueDate,
            'admin_notes' => ($loan->admin_notes ?? '') . "\n" . 
                'Perpanjangan: +' . $request->days . ' hari oleh ' . Auth::user()->name . ' pada ' . now()->format('Y-m-d'),
        ]);

        return redirect()->back()
            ->with('success', 'Peminjaman berhasil diperpanjang hingga ' . $newDueDate->format('d M Y'));
    }

    /**
     * Mark overdue loans
     */
    public function markOverdue()
    {
        $overdueLoans = Loan::whereIn('status', [Loan::STATUS_APPROVED, Loan::STATUS_BORROWED])
            ->where('due_date', '<', now())
            ->get();

        $count = 0;
        foreach ($overdueLoans as $loan) {
            if ($loan->checkOverdue()) {
                $count++;
            }
        }

        return redirect()->back()
            ->with('success', "{$count} peminjaman ditandai sebagai terlambat.");
    }

    /**
     * Get loan statistics for dashboard
     */
    public function getStats()
    {
        return response()->json([
            'pending' => Loan::where('status', Loan::STATUS_PENDING)->count(),
            'borrowed' => Loan::where('status', Loan::STATUS_BORROWED)->count(),
            'returned' => Loan::where('status', Loan::STATUS_RETURNED)->count(),
            'overdue' => Loan::overdue()->count(),
            'total_books_borrowed' => Loan::where('status', '!=', Loan::STATUS_CANCELLED)->count(),
        ]);
    }
}

