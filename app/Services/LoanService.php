<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\Product;
use App\Models\User;

class LoanService
{
    public function requestLoan(User $user, int $bookId, ?int $duration = null, ?string $notes = null): Loan
    {
        $book = Product::findOrFail($bookId);

        $existingLoan = $user->loans()
            ->where('book_id', $book->id)
            ->whereIn('status', [
                Loan::STATUS_PENDING,
                Loan::STATUS_APPROVED,
                Loan::STATUS_BORROWED,
            ])
            ->first();

        if ($existingLoan) {
            throw new \Exception('Anda sudah memiliki peminjaman aktif untuk buku ini.');
        }

        if (!$user->canBorrowBooks()) {
            throw new \Exception('Anda sudah mencapai batas maksimal peminjaman buku.');
        }

        if ($book->stock <= 0) {
            throw new \Exception('Buku ini sedang tidak tersedia.');
        }

        $duration = $duration ?? Loan::DEFAULT_LOAN_DURATION;
        
        $loan = Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'loan_date' => now(),
            'due_date' => now()->addDays($duration),
            'status' => Loan::STATUS_PENDING,
            'notes' => $notes,
        ]);

        $book->decrementStock(1);

        return $loan;
    }

    public function cancelLoan(Loan $loan, ?string $reason = null): void
    {
        if (!in_array($loan->status, [Loan::STATUS_PENDING, Loan::STATUS_APPROVED])) {
            throw new \Exception('Peminjaman tidak dapat dibatalkan.');
        }

        $loan->cancel($reason ?? 'Dibatalkan oleh peminjam');
        $loan->book->incrementStock(1);
    }

    public function approveLoan(Loan $loan, User $admin, ?string $notes = null): void
    {
        if ($loan->status !== Loan::STATUS_PENDING) {
            throw new \Exception('Hanya peminjaman dengan status Menunggu yang dapat disetujui.');
        }

        // Menggunakan durasi default 45 jam sesuai logika awal di controller
        $loan->approve($admin, true, Loan::LOAN_DURATION_45_HOURS);
        
        if ($notes) {
            $loan->update(['admin_notes' => $notes]);
        }
    }

    public function rejectLoan(Loan $loan, string $reason): void
    {
        if (!in_array($loan->status, [Loan::STATUS_PENDING, Loan::STATUS_APPROVED])) {
            throw new \Exception('Peminjaman tidak dapat ditolak.');
        }

        $loan->cancel($reason);
        $loan->book->incrementStock(1);
    }

    /**
     * Mengambil statistik untuk dashboard admin dan badge sidebar.
     */
    public function getAdminStats(): array
    {
        return [
            'total' => Loan::count(),
            'pending' => Loan::where('status', Loan::STATUS_PENDING)->count(),
            'borrowed' => Loan::where('status', Loan::STATUS_BORROWED)->count(),
            'returned' => Loan::where('status', Loan::STATUS_RETURNED)->count(),
            'overdue' => Loan::overdue()->count(),
        ];
    }
}