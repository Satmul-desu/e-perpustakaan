<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = ['user_id', 'session_id'];

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function getTotalAttribute(): float
    {
        return $this->items->sum('subtotal');
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp '.number_format($this->total, 0, ',', '.');
    }

    public function getTotalQuantityAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    public function loadItems(): self
    {
        return $this->load(['items.product', 'items.product.primaryImage']);
    }
}
