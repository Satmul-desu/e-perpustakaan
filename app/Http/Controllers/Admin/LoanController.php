<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
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
        $stats = [
            'total' => Loan::count(),
            'pending' => Loan::where('status', Loan::STATUS_PENDING)->count(),
            'borrowed' => Loan::where('status', Loan::STATUS_BORROWED)->count(),
            'returned' => Loan::where('status', Loan::STATUS_RETURNED)->count(),
            'overdue' => Loan::overdue()->count(),
        ];

        return view('admin.loans.index', compact('loans', 'stats'));
    }

    public function show(Loan $loan)
    {
        $loan->load('user', 'book.category', 'approver', 'returnHandler');

        return view('admin.loans.show', compact('loan'));
    }

    public function approve(Request $request, Loan $loan)
    {
        if ($loan->status !== Loan::STATUS_PENDING) {
            return redirect()->back()
                ->with('error', 'Hanya peminjaman dengan status Menunggu yang dapat disetujui.');
        }
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);
        $loan->approve(Auth::user(), true, Loan::LOAN_DURATION_45_HOURS);
        if ($request->notes) {
            $loan->update(['admin_notes' => $request->notes]);
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
        if ($loan->status !== Loan::STATUS_OVERDUE && ! $loan->is_overdue) {
            return redirect()->back()->with('error', 'Hanya buku yang terlambat dapat diberikan denda.');
        }

        $request->validate([
            'fine_amount' => 'required|numeric|min:1000',
        ]);

        $loan->update([
            'fine_amount' => $request->fine_amount,
            'fine_status' => 'unpaid',
            'admin_notes' => ($loan->admin_notes ?? '') . "\n" .
                'Sanksi Denda: Rp ' . number_format($request->fine_amount, 0, ',', '.') . ' oleh ' . Auth::user()->name . ' pada ' . now()->format('Y-m-d'),
        ]);

        return redirect()->back()->with('success', 'Tagihan Denda berhasil dikirim ke Pengguna.');
    }

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
