<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'stock',
        'weight',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'stock' => 'integer',
        'weight' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // NOTE: Removed $with eager loading to prevent 500 errors when products
    // don't have category or primaryImage relationships.
    // Controllers should explicitly eager load when needed.

    // ===========================================
    // RELATIONSHIPS
    // ===========================================

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    // ===========================================
    // ACCESSORS
    // ===========================================

    /**
     * Get the image URL attribute (singleton for backward compatibility).
     */
    public function getImageUrlAttribute(): string
    {
        return $this->primaryImage?->image_url ?? $this->images->first()?->image_url ?? asset('images/placeholder.png');
    }

    /**
     * Get the formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get the formatted original price (before discount).
     */
    public function getFormattedOriginalPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get the formatted final price (after discount).
     */
    public function getFormattedFinalPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->final_price, 0, ',', '.');
    }

    /**
     * Get the final price (after discount).
     */
    public function getFinalPriceAttribute(): float
    {
        return $this->discount_price ?? $this->price;
    }

    /**
     * Get the display price for cart/checkout.
     * Uses final price (after discount) if available.
     */
    public function getDisplayPriceAttribute(): float
    {
        return $this->final_price;
    }

    /**
     * Check if product has discount.
     */
    public function getHasDiscountAttribute(): bool
    {
        return $this->discount_price !== null && $this->discount_price < $this->price;
    }

    /**
     * Calculate discount percentage.
     */
    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->has_discount) {
            return 0;
        }
        
        return round(($this->price - $this->discount_price) / $this->price * 100);
    }

    /**
     * Get stock status.
     */
    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'out_of_stock';
        } elseif ($this->stock <= 5) {
            return 'low_stock';
        }
        return 'in_stock';
    }

    // ===========================================
    // SCOPES
    // ===========================================

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_active', true)->where('stock', '>', 0);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeLowStock(Builder $query): Builder
    {
        return $query->where('stock', '>', 0)->where('stock', '<=', 5);
    }

    public function scopeByCategory(Builder $query, $categorySlug): Builder
    {
        return $query->whereHas('category', function ($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }

    public function scopeWithPriceRange(Builder $query, $min, $max): Builder
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    public function scopeSearch(Builder $query, $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        // Case-insensitive search menggunakan LOWER()
        return $query->where(function ($q) use ($search) {
            $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
              ->orWhereRaw('LOWER(description) LIKE ?', ["%{$search}%"]);
        });
    }

    // ===========================================
    // BOOT
    // ===========================================

    protected static function boot(): void
    {
        parent::boot();

        // Auto-generate slug dari name saat creating/updating
        static::saving(function ($product) {
            if (empty($product->slug) || $product->isDirty('name')) {
                $product->slug = \Illuminate\Support\Str::slug($product->name);
                
                // Pastikan slug unik
                $originalSlug = $product->slug;
                $counter = 1;
                while (static::where('slug', $product->slug)->where('id', '!=', $product->id)->exists()) {
                    $product->slug = $originalSlug . '-' . $counter++;
                }
            }
        });
    }

    // ===========================================
    // HELPER METHODS
    // ===========================================

    /**
     * Check if product is in user's wishlist.
     */
    public function isInWishlist(User $user = null): bool
    {
        $user = $user ?? \Illuminate\Support\Facades\Auth::user();
        
        if (!$user) {
            return false;
        }

        return $this->wishlists()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if product has enough stock for requested quantity.
     */
    public function hasStock(int $quantity): bool
    {
        return $this->stock >= $quantity;
    }

    /**
     * Decrement stock atomically.
     */
    public function decrementStock(int $quantity): bool
    {
        return $this->decrement('stock', $quantity);
    }

    /**
     * Increment stock atomically.
     */
    public function incrementStock(int $quantity): bool
    {
        return $this->increment('stock', $quantity);
    }
}
