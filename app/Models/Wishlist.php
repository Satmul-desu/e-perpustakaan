<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
    ];

    // Relationship: Wishlist belongs to User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: Wishlist belongs to Product
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
