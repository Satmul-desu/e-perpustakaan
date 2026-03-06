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
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_BORROWED = 'borrowed';
    const STATUS_RETURNED = 'returned';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_CANCELLED = 'cancelled';
    const DEFAULT_LOAN_DURATION = 7;
    const LOAN_DURATION_45_HOURS = 45;
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
    public function getIsOverdueAttribute(): bool
    {
        if ($this->status === self::STATUS_RETURNED) {
            return false;
        }
        return now()->gt($this->due_date);
    }
    public function getDaysRemainingAttribute(): int
    {
        if ($this->return_date) {
            return 0;
        }
        return now()->diffInDays($this->due_date, false);
    }
    public function getLoanDurationAttribute(): int
    {
        return $this->loan_date->diffInDays($this->due_date);
    }
    public function getLoanDurationHoursAttribute(): int
    {
        return $this->loan_date->diffInHours($this->due_date);
    }
    public function getIsHoursDurationAttribute(): bool
    {
        return $this->loan_duration_hours <= 72; 
    }
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
    public function approve(User $approver, bool $directBorrowed = true, int $durationHours = 45): bool
    {
        if ($directBorrowed) {
            $this->status = self::STATUS_BORROWED;
            $this->loan_date = now();
            $this->due_date = now()->addHours($durationHours);
        } else {
            $this->status = self::STATUS_APPROVED;
        }
        $this->approved_by = $approver->id;
        return $this->save();
    }
    public function markAsBorrowed(): bool
    {
        $this->status = self::STATUS_BORROWED;
        $this->loan_date = now();
        return $this->save();
    }
    public function markAsReturned(User $handler): bool
    {
        $this->status = self::STATUS_RETURNED;
        $this->return_date = now();
        $this->returned_to = $handler->id;
        $this->book->incrementStock(1);
        return $this->save();
    }
    public function cancel(string $reason = null): bool
    {
        $this->status = self::STATUS_CANCELLED;
        if ($reason) {
            $this->admin_notes = $reason;
        }
        return $this->save();
    }
    public function checkOverdue(): bool
    {
        if (in_array($this->status, [self::STATUS_BORROWED, self::STATUS_APPROVED]) && $this->is_overdue) {
            $this->status = self::STATUS_OVERDUE;
            return $this->save();
        }
        return false;
    }
}