<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'loan_date',
        'due_date',
        'return_date',
        'status',
        'notes',
        'admin_notes',
        'approved_by',
        'returned_to',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];

    // Konstanta status
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_BORROWED = 'borrowed';
    const STATUS_RETURNED = 'returned';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_CANCELLED = 'cancelled';

    // Konstanta durasi peminjaman default (hari)
    const DEFAULT_LOAN_DURATION = 7;

    // Konstanta durasi peminjaman 45 jam (untuk persetujuan admin langsung ke borrowed)
    const LOAN_DURATION_45_HOURS = 45;

    // ===========================================
    // RELATIONSHIPS
    // ===========================================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'book_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function returnHandler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'returned_to');
    }

    // ===========================================
    // ACCESSORS
    // ===========================================

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_APPROVED => 'info',
            self::STATUS_BORROWED => 'primary',
            self::STATUS_RETURNED => 'success',
            self::STATUS_OVERDUE => 'danger',
            self::STATUS_CANCELLED => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * Get status text in Indonesian
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Menunggu Persetujuan',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_BORROWED => 'Dipinjam',
            self::STATUS_RETURNED => 'Dikembalikan',
            self::STATUS_OVERDUE => 'Terlambat',
            self::STATUS_CANCELLED => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    /**
     * Check if loan is overdue
     */
    public function getIsOverdueAttribute(): bool
    {
        if ($this->status === self::STATUS_RETURNED) {
            return false;
        }
        return now()->gt($this->due_date);
    }

    /**
     * Get days remaining until due
     */
    public function getDaysRemainingAttribute(): int
    {
        if ($this->return_date) {
            return 0;
        }
        return now()->diffInDays($this->due_date, false);
    }

    /**
     * Get formatted loan duration
     */
    public function getLoanDurationAttribute(): int
    {
        return $this->loan_date->diffInDays($this->due_date);
    }

    /**
     * Get loan duration in hours (for 45-hour loans)
     */
    public function getLoanDurationHoursAttribute(): int
    {
        return $this->loan_date->diffInHours($this->due_date);
    }

    /**
     * Check if loan is measured in hours (45 hours)
     */
    public function getIsHoursDurationAttribute(): bool
    {
        return $this->loan_duration_hours <= 72; // 72 jam = 3 hari
    }

    // ===========================================
   
    // ================================= // SCOPES==========

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_APPROVED, self::STATUS_BORROWED]);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_OVERDUE)
                    ->orWhere(function ($q) {
                        $q->whereIn('status', [self::STATUS_APPROVED, self::STATUS_BORROWED])
                          ->where('due_date', '<', now());
                    });
    }

    public function scopeReturned($query)
    {
        return $query->where('status', self::STATUS_RETURNED);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ===========================================
    // HELPER METHODS
    // ===========================================

    /**
     * Approve the loan
     * Modified: Admin approval now directly sets status to BORROWED with 45 hours duration
     */
    public function approve(User $approver, bool $directBorrowed = true, int $durationHours = 45): bool
    {
        if ($directBorrowed) {
            // Langsung set ke status BORROWED dengan durasi 45 jam
            $this->status = self::STATUS_BORROWED;
            $this->loan_date = now();
            $this->due_date = now()->addHours($durationHours);
        } else {
            // Set ke status APPROVED (metode lama)
            $this->status = self::STATUS_APPROVED;
        }
        
        $this->approved_by = $approver->id;
        return $this->save();
    }

    /**
     * Mark as borrowed
     */
    public function markAsBorrowed(): bool
    {
        $this->status = self::STATUS_BORROWED;
        $this->loan_date = now();
        return $this->save();
    }

    /**
     * Mark as returned
     */
    public function markAsReturned(User $handler): bool
    {
        $this->status = self::STATUS_RETURNED;
        $this->return_date = now();
        $this->returned_to = $handler->id;
        
        // Kembalikan stok buku
        $this->book->incrementStock(1);
        
        return $this->save();
    }

    /**
     * Cancel the loan
     */
    public function cancel(string $reason = null): bool
    {
        $this->status = self::STATUS_CANCELLED;
        if ($reason) {
            $this->admin_notes = $reason;
        }
        return $this->save();
    }

    /**
     * Check and update overdue status
     */
    public function checkOverdue(): bool
    {
        if (in_array($this->status, [self::STATUS_BORROWED, self::STATUS_APPROVED]) && $this->is_overdue) {
            $this->status = self::STATUS_OVERDUE;
            return $this->save();
        }
        return false;
    }
}

