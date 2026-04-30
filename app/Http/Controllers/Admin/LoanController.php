<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Services\LoanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    protected $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    public function index(Request $request)
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
        $stats = $this->loanService->getAdminStats();

        return view('admin.loans.index', compact('loans', 'stats'));
    }

    public function show(Loan $loan)
    {
        $loan->load('user', 'book.category', 'approver', 'returnHandler');

        return view('admin.loans.show', compact('loan'));
    }

    public function approve(Request $request, Loan $loan)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $this->loanService->approveLoan($loan, Auth::user(), $request->notes);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()
            ->with('success', 'Peminjaman disetujui. Status: Dipinjam (Durasi: 45 jam)');
    }

    public function reject(Request $request, Loan $loan)
    {
        if (! in_array($loan->status, [Loan::STATUS_PENDING, Loan::STATUS_APPROVED])) {
            return redirect()->back()
                ->with('error', 'Peminjaman tidak dapat ditolak.');
        }
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);
        $loan->cancel($request->reason);
        $loan->book->incrementStock(1);

        return redirect()->back()
            ->with('success', 'Peminjaman berhasil ditolak.');
    }

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

    public function extend(Request $request, Loan $loan)
    {
        if (! in_array($loan->status, [Loan::STATUS_APPROVED, Loan::STATUS_BORROWED])) {
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
            'admin_notes' => ($loan->admin_notes ?? '')."\n".
                'Perpanjangan: +'.$request->days.' hari oleh '.Auth::user()->name.' pada '.now()->format('Y-m-d'),
        ]);

        return redirect()->back()
            ->with('success', 'Peminjaman berhasil diperpanjang hingga '.$newDueDate->format('d M Y'));
    }

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

    public function sendFine(Request $request, Loan $loan)
    {
        $daysLate = max(0, now()->diffInMinutes($loan->due_date));
        
        if ($daysLate < 3) {
            return redirect()->back()->with('error', 'Tagihan Denda hanya bisa dikirim jika peminjam terlambat minimal 3 hari.');
        }

        $loan->update([
            'is_fine_active' => true,
            'fine_status' => 'unpaid',
            'admin_notes' => ($loan->admin_notes ?? '') . "\n" .
                '[Surat Denda] Resmi Dikeluarkan oleh ' . Auth::user()->name . ' pada ' . now()->format('Y-m-d H:i'),
        ]);

        // Kirim Notifikasi Denda Utama ke User
        if ($loan->user) {
            $loan->user->notify(new \App\Notifications\LoanOverdueNotification($loan, false));
        }

        return redirect()->back()->with('success', 'Surat Denda resmi diterbitkan! Sistem akan menghitung akumulasi denda dari hari pertama.');
    }

    public function getStats()
    {
        return response()->json($this->loanService->getAdminStats());
    }
}
