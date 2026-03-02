<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'midtrans_order_id',
        'status',
        'payment_status',
        'total_amount',
        'shipping_cost',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'payment_method',
        'notes',
    ];

    protected $with = ['user', 'orderItems', 'payment'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Accessors
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'processing' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    public function getPaymentStatusColorAttribute(): string
    {
        return match($this->payment_status) {
            'paid' => 'success',
            'pending' => 'warning',
            'failed' => 'danger',
            default => 'secondary',
        };
    }

    public function getPaymentStatusTextAttribute(): string
    {
        return match($this->payment_status) {
            'paid' => 'Lunas',
            'pending' => 'Menunggu Pembayaran',
            'failed' => 'Gagal',
            default => ucfirst($this->payment_status ?? 'Tidak diketahui'),
        };
    }

    public function getItemsSubtotalAttribute(): float
    {
        return $this->orderItems->sum('subtotal');
    }
}
