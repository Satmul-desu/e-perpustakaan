<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    /**
     * Display list of user's loans
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = $user->loans()->with('book.category');
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        $loans = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get loan statistics
        $stats = [
            'pending' => $user->loans()->where('status', Loan::STATUS_PENDING)->count(),
            'borrowed' => $user->loans()->where('status', Loan::STATUS_BORROWED)->count(),
            'returned' => $user->loans()->where('status', Loan::STATUS_RETURNED)->count(),
            'overdue' => $user->loans()->overdue()->count(),
        ];
        
        return view('loans.index', compact('loans', 'stats'));
    }

    /**
     * Show loan details
     */
    public function show(Loan $loan)
    {
        $this->authorizeLoanAccess($loan);
        
        $loan->load('book.category', 'approver', 'returnHandler');
        
        return view('loans.show', compact('loan'));
    }

    /**
     * Request to borrow a book
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:products,id',
            'duration' => 'nullable|integer|min:1|max:30',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $book = Product::findOrFail($request->book_id);
        
        // Check if user already has pending/active loan for this book
        $existingLoan = $user->loans()
            ->where('book_id', $book->id)
            ->whereIn('status', [
                Loan::STATUS_PENDING,
                Loan::STATUS_APPROVED,
                Loan::STATUS_BORROWED
            ])
            ->first();

        if ($existingLoan) {
            return redirect()->back()
                ->with('error', 'Anda sudah memiliki peminjaman aktif untuk buku ini.');
        }

        // Check if user can borrow more books
        if (!$user->canBorrowBooks()) {
            return redirect()->back()
                ->with('error', 'Anda sudah mencapai batas maksimal peminjaman buku.');
        }

        // Check if book is available (stock > 0)
        if ($book->stock <= 0) {
            return redirect()->back()
                ->with('error', 'Buku ini sedang tidak tersedia.');
        }

        $duration = $request->duration ?? Loan::DEFAULT_LOAN_DURATION;
        
        // Create loan request
        $loan = Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'loan_date' => now(),
            'due_date' => now()->addDays($duration),
            'status' => Loan::STATUS_PENDING,
            'notes' => $request->notes,
        ]);

        // Decrement book stock temporarily (will be restored if cancelled)
        $book->decrementStock(1);

        return redirect()->route('loans.show', $loan)
            ->with('success', 'Permintaan peminjaman berhasil! Menunggu persetujuan admin.');
    }

    /**
     * Cancel a loan request
     */
    public function cancel(Request $request, Loan $loan)
    {
        $this->authorizeLoanAccess($loan);
        
        if (!in_array($loan->status, [Loan::STATUS_PENDING, Loan::STATUS_APPROVED])) {
            return redirect()->back()
                ->with('error', 'Peminjaman tidak dapat dibatalkan.');
        }

        $loan->cancel($request->reason ?? 'Dibatalkan oleh peminjam');
        
        // Restore book stock
        $loan->book->incrementStock(1);

        return redirect()->route('loans.index')
            ->with('success', 'Peminjaman berhasil dibatalkan.');
    }

    /**
     * Request to return a book
     */
    public function requestReturn(Loan $loan)
    {
        $this->authorizeLoanAccess($loan);

        if ($loan->status !== Loan::STATUS_BORROWED) {
            return redirect()->back()
                ->with('error', 'Buku ini tidak dalam status dipinjam.');
        }

        // In a real system, you might have a separate return request workflow
        // For now, we'll just mark it as returned directly
        
        return view('loans.return', compact('loan'));
    }

    /**
     * Process book return
     */
    public function processReturn(Request $request, Loan $loan)
    {
        $this->authorizeLoanAccess($loan);

        if ($loan->status !== Loan::STATUS_BORROWED) {
            return redirect()->back()
                ->with('error', 'Buku ini tidak dalam status dipinjam.');
        }

        // For regular users, they can mark return request
        // The actual return processing will be done by admin
        $loan->update([
            'notes' => ($loan->notes ?? '') . "\n" . 'Peminjam mengajukan pengembalian: ' . now()->format('Y-m-d H:i'),
        ]);

        return redirect()->route('loans.index')
            ->with('success', 'Pengajuan pengembalian buku berhasil! Harap serahkan buku ke perpustakaan.');
    }

    /**
     * Authorize loan access for user
     */
    private function authorizeLoanAccess(Loan $loan)
    {
        if (Auth::user()->id !== $loan->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access to this loan.');
        }
    }
}

