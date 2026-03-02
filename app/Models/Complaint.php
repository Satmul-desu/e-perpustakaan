<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'category',
        'subject',
        'message',
        'status',
        'priority',
        'order_number',
        'admin_response',
        'responded_by',
        'responded_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    /**
     * Get the user that owns the complaint.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who responded to the complaint.
     */
    public function responder()
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    /**
     * Get status badge color.
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'in_progress' => 'info',
            'resolved' => 'success',
            'closed' => 'secondary',
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    /**
     * Get priority badge color.
     */
    public function getPriorityBadgeAttribute()
    {
        $badges = [
            'low' => 'secondary',
            'normal' => 'info',
            'high' => 'warning',
            'urgent' => 'danger',
        ];

        return $badges[$this->priority] ?? 'secondary';
    }

    /**
     * Get type icon.
     */
    public function getTypeIconAttribute()
    {
        $icons = [
            'complaint' => 'bi-exclamation-circle',
            'report' => 'bi-flag',
            'question' => 'bi-question-circle',
        ];

        return $icons[$this->type] ?? 'bi-chat';
    }

    /**
     * Get category display name.
     */
    public function getCategoryNameAttribute()
    {
        $names = [
            'order' => 'Pesanan',
            'product' => 'Produk',
            'payment' => 'Pembayaran',
            'shipping' => 'Pengiriman',
            'other' => 'Lainnya',
        ];

        return $names[$this->category] ?? $this->category;
    }

    /**
     * Get status display name.
     */
    public function getStatusNameAttribute()
    {
        $names = [
            'pending' => 'Menunggu',
            'in_progress' => 'Diproses',
            'resolved' => 'Selesai',
            'closed' => 'Ditutup',
        ];

        return $names[$this->status] ?? $this->status;
    }

    /**
     * Get priority display name.
     */
    public function getPriorityNameAttribute()
    {
        $names = [
            'low' => 'Rendah',
            'normal' => 'Normal',
            'high' => 'Tinggi',
            'urgent' => 'Mendesak',
        ];

        return $names[$this->priority] ?? $this->priority;
    }

    /**
     * Scope for pending complaints.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for user's complaints.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}

