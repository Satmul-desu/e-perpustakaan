<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Relasi Inverse One-to-Many: CartItem milik SATU Cart.
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Relasi Inverse One-to-Many: CartItem milik SATU Product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // ==================== ACCESSORS ====================

    /**
     * Accessor: Subtotal per item
     * Rumus: quantity * product.display_price
     */
    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->product->display_price;
    }

    /**
     * Accessor: Formatted Subtotal
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    /**
     * Accessor: Total Weight per item
     * Rumus: quantity * product.weight
     */
    public function getTotalWeightAttribute(): int
    {
        return $this->quantity * $this->product->weight;
    }
}
