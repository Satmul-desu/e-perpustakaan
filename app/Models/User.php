<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'avatar',
        'google_id',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    // Eager loading defaults
    protected $with = [];

    // Relationships
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function approvedLoans(): HasMany
    {
        return $this->hasMany(Loan::class, 'approved_by');
    }

    /**
     * Check if user has active loans
     */
    public function hasActiveLoans(): bool
    {
        return $this->loans()->whereIn('status', [
            Loan::STATUS_PENDING,
            Loan::STATUS_APPROVED,
            Loan::STATUS_BORROWED,
        ])->exists();
    }

    /**
     * Get count of active loans
     */
    public function activeLoansCount(): int
    {
        return $this->loans()->whereIn('status', [
            Loan::STATUS_PENDING,
            Loan::STATUS_APPROVED,
            Loan::STATUS_BORROWED,
        ])->count();
    }

    /**
     * Check if user can borrow more books
     */
    public function canBorrowBooks(int $maxLoans = 5): bool
    {
        return $this->activeLoansCount() < $maxLoans;
    }

    // Scopes
    public function scopeAdmin($query)
    {
        return $query->where('is_admin', true);
    }

    public function scopeRegular($query)
    {
        return $query->where('is_admin', false);
    }

    /**
     * Check if user logged in via Google (has google_id)
     */
    public function isGoogleUser(): bool
    {
        return !empty($this->google_id);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    /**
     * Check if avatar is from Google (URL, not local path)
     */
    public function isGoogleAvatar(): bool
    {
        // Avatar dari Google adalah URL (mengandung http:// atau https://)
        // Avatar lokal adalah path file (tidak mengandung http)
        return !empty($this->avatar) && 
               (str_starts_with($this->avatar, 'http://') || 
                str_starts_with($this->avatar, 'https://'));
    }

    /**
     * Get avatar URL - handles both Google URL and local file path
     */
    public function getAvatarUrlAttribute(): string
    {
        // Jika avatar kosong
        if (empty($this->avatar)) {
            return $this->getDefaultAvatarUrl();
        }

        // Jika avatar adalah URL Google
        if ($this->isGoogleAvatar()) {
            return $this->avatar;
        }

        // Jika avatar adalah file lokal
        if (file_exists(public_path('storage/' . $this->avatar))) {
            return asset('storage/' . $this->avatar);
        }

        return $this->getDefaultAvatarUrl();
    }

    /**
     * Get default avatar URL (placeholder)
     */
    protected function getDefaultAvatarUrl(): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=3b82f6&color=fff&size=128';
    }

    /**
     * Get avatar image HTML or icon based on availability
     */
    public function getAvatarHtmlAttribute(): string
    {
        $avatarUrl = $this->avatar_url;

        if ($avatarUrl && filter_var($avatarUrl, FILTER_VALIDATE_URL)) {
            return '<img src="' . e($avatarUrl) . '" 
                    class="rounded-circle" 
                    width="32" height="32" 
                    alt="' . e($this->name) . '" 
                    style="border: 2px solid #3b82f6; object-fit: cover;">';
        }
        
        // Jika tidak ada avatar, tampilkan icon Bootstrap
        return '<div class="rounded-circle d-flex align-items-center justify-content-center" 
                    style="width: 32px; height: 32px; border: 2px solid #3b82f6; background: rgba(59, 130, 246, 0.2);">
                    <i class="bi bi-person-fill" style="color: #60a5fa; font-size: 1rem;"></i>
                </div>';
    }

    /**
     * Get avatar for profile page - larger size
     */
    public function getProfileAvatarHtmlAttribute($size = 120): string
    {
        $avatarUrl = $this->avatar_url;

        if ($avatarUrl && filter_var($avatarUrl, FILTER_VALIDATE_URL)) {
            return '<img src="' . e($avatarUrl) . '" 
                    class="rounded-circle" 
                    width="' . $size . '" height="' . $size . '" 
                    alt="' . e($this->name) . '" 
                    style="border: 3px solid #3b82f6; object-fit: cover;">';
        }
        
        // Jika tidak ada avatar, tampilkan icon Bootstrap besar
        return '<div class="rounded-circle d-flex align-items-center justify-content-center" 
                    style="width: ' . $size . 'px; height: ' . $size . 'px; border: 3px solid #3b82f6; background: rgba(59, 130, 246, 0.2);">
                    <i class="bi bi-person-fill" style="color: #60a5fa; font-size: ' . ($size/2) . 'px;"></i>
                </div>';
    }

    public function getFullAddressAttribute(): string
    {
        return trim("{$this->address}");
    }

    /**
     * Check if user has product in wishlist.
     * Safely extracts product ID from Product object, array, or ID.
     */
    public function hasInWishlist($product): bool
    {
        // Extract product ID safely
        $productId = null;
        
        if ($product instanceof Product) {
            $productId = $product->id;
        } elseif (is_array($product) || is_object($product)) {
            // Handle array or stdClass (from JSON deserialization)
            $productId = is_array($product) ? ($product['id'] ?? null) : ($product->id ?? null);
        } elseif (is_numeric($product)) {
            $productId = (int) $product;
        }
        
        if (!$productId) {
            return false;
        }
        
        return $this->wishlists()
            ->where('product_id', $productId)
            ->exists();
    }
}
