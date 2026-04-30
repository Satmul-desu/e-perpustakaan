<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Product;
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

        try {
            $loan = $this->loanService->requestLoan(
                Auth::user(),
                $request->book_id,
                $request->duration,
                $request->notes
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('loans.show', $loan)
            ->with('success', 'Permintaan peminjaman berhasil! Menunggu persetujuan admin.');
    }

    public function cancel(Request $request, Loan $loan)
    {
        $this->authorizeLoanAccess($loan);
        
        try {
            $this->loanService->cancelLoan($loan, $request->reason);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

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
