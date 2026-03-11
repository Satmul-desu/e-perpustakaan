<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->loans()->with('book.category');
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        $loans = $query->orderBy('created_at', 'desc')->paginate(10);
        $stats = [
            'pending' => $user->loans()->where('status', Loan::STATUS_PENDING)->count(),
            'borrowed' => $user->loans()->where('status', Loan::STATUS_BORROWED)->count(),
            'returned' => $user->loans()->where('status', Loan::STATUS_RETURNED)->count(),
            'overdue' => $user->loans()->overdue()->count(),
        ];

        return view('loans.index', compact('loans', 'stats'));
    }

    public function show(Loan $loan)
    {
        $this->authorizeLoanAccess($loan);
        $loan->load('book.category', 'approver', 'returnHandler');

        return view('loans.show', compact('loan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:products,id',
            'duration' => 'nullable|integer|min:1|max:30',
            'notes' => 'nullable|string|max:500',
        ]);
        $user = Auth::user();
        $book = Product::findOrFail($request->book_id);
        $existingLoan = $user->loans()
            ->where('book_id', $book->id)
            ->whereIn('status', [
                Loan::STATUS_PENDING,
                Loan::STATUS_APPROVED,
                Loan::STATUS_BORROWED,
            ])
            ->first();
        if ($existingLoan) {
            return redirect()->back()
                ->with('error', 'Anda sudah memiliki peminjaman aktif untuk buku ini.');
        }
        if (! $user->canBorrowBooks()) {
            return redirect()->back()
                ->with('error', 'Anda sudah mencapai batas maksimal peminjaman buku.');
        }
        if ($book->stock <= 0) {
            return redirect()->back()
                ->with('error', 'Buku ini sedang tidak tersedia.');
        }
        $duration = $request->duration ?? Loan::DEFAULT_LOAN_DURATION;
        $loan = Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'loan_date' => now(),
            'due_date' => now()->addDays($duration),
            'status' => Loan::STATUS_PENDING,
            'notes' => $request->notes,
        ]);
        $book->decrementStock(1);

        return redirect()->route('loans.show', $loan)
            ->with('success', 'Permintaan peminjaman berhasil! Menunggu persetujuan admin.');
    }

    public function cancel(Request $request, Loan $loan)
    {
        $this->authorizeLoanAccess($loan);
        if (! in_array($loan->status, [Loan::STATUS_PENDING, Loan::STATUS_APPROVED])) {
            return redirect()->back()
                ->with('error', 'Peminjaman tidak dapat dibatalkan.');
        }
        $loan->cancel($request->reason ?? 'Dibatalkan oleh peminjam');
        $loan->book->incrementStock(1);

        return redirect()->route('loans.index')
            ->with('success', 'Peminjaman berhasil dibatalkan.');
    }

    public function requestReturn(Loan $loan)
    {
        $this->authorizeLoanAccess($loan);
        if ($loan->status !== Loan::STATUS_BORROWED) {
            return redirect()->back()
                ->with('error', 'Buku ini tidak dalam status dipinjam.');
        }

        return view('loans.return', compact('loan'));
    }

    public function processReturn(Request $request, Loan $loan)
    {
        $this->authorizeLoanAccess($loan);
        if ($loan->status !== Loan::STATUS_BORROWED) {
            return redirect()->back()
                ->with('error', 'Buku ini tidak dalam status dipinjam.');
        }
        $loan->update([
            'notes' => ($loan->notes ?? '')."\n".'Peminjam mengajukan pengembalian: '.now()->format('Y-m-d H:i'),
        ]);

        return redirect()->route('loans.index')
            ->with('success', 'Pengajuan pengembalian buku berhasil! Harap serahkan buku ke perpustakaan.');
    }

    private function authorizeLoanAccess(Loan $loan)
    {
        if (Auth::user()->id !== $loan->user_id && ! Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access to this loan.');
        }
    }
}
