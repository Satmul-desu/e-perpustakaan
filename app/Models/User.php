<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'email',

        'password',
        'phone',
        'address',
        'avatar',
        'google_id',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $with = [];

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

    public function hasActiveLoans(): bool
    {
        return $this->loans()->whereIn('status', [
            Loan::STATUS_PENDING,
            Loan::STATUS_APPROVED,
            Loan::STATUS_BORROWED,
        ])->exists();
    }

    public function activeLoansCount(): int
    {
        return $this->loans()->whereIn('status', [
            Loan::STATUS_PENDING,
            Loan::STATUS_APPROVED,
            Loan::STATUS_BORROWED,
        ])->count();
    }

    public function canBorrowBooks(int $maxLoans = 5): bool
    {
        return $this->activeLoansCount() < $maxLoans;
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeRegular($query)
    {
        return $query->where('role', 'customer');
    }

    public function isGoogleUser(): bool
    {
        return ! empty($this->google_id);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGoogleAvatar(): bool
    {
        return ! empty($this->avatar) &&
               (str_starts_with($this->avatar, 'http://') ||
                str_starts_with($this->avatar, 'https://'));
    }

    public function getAvatarUrlAttribute(): string
    {
        if (empty($this->avatar)) {
            return $this->getDefaultAvatarUrl();
        }
        if ($this->isGoogleAvatar()) {
            return $this->avatar;
        }
        
        return asset('storage/' . $this->avatar);
    }

    protected function getDefaultAvatarUrl(): string
    {
        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background=3b82f6&color=fff';
    }

    public function getAvatarHtmlAttribute(): string
    {
        $avatarUrl = $this->avatar_url;
        if ($avatarUrl && filter_var($avatarUrl, FILTER_VALIDATE_URL)) {
            return '<img src="'.e($avatarUrl).'" 
                    class="rounded-circle" 
                    width="32" height="32" 
                    alt="'.e($this->name).'" 
                    style="border: 2px solid #3b82f6; object-fit: cover;">';
        }

        return '<div class="rounded-circle d-flex align-items-center justify-content-center" 
                    style="width: 32px; height: 32px; border: 2px solid #3b82f6; background: rgba(59, 130, 246, 0.2);">
                    <i class="bi bi-person-fill" style="color: #60a5fa; font-size: 1rem;"></i>
                </div>';
    }

    public function getProfileAvatarHtmlAttribute($size = 120): string
    {
        $avatarUrl = $this->avatar_url;
        if ($avatarUrl && filter_var($avatarUrl, FILTER_VALIDATE_URL)) {
            return '<img src="'.e($avatarUrl).'" 
                    class="rounded-circle" 
                    width="'.$size.'" height="'.$size.'" 
                    alt="'.e($this->name).'" 
                    style="border: 3px solid #3b82f6; object-fit: cover;">';
        }

        return '<div class="rounded-circle d-flex align-items-center justify-content-center" 
                    style="width: '.$size.'px; height: '.$size.'px; border: 3px solid #3b82f6; background: rgba(59, 130, 246, 0.2);">
                    <i class="bi bi-person-fill" style="color: #60a5fa; font-size: '.($size / 2).'px;"></i>
                </div>';
    }

    public function getFullAddressAttribute(): string
    {
        return trim("{$this->address}");
    }

    public function hasInWishlist($product): bool
    {
        $productId = null;
        if ($product instanceof Product) {
            $productId = $product->id;
        } elseif (is_array($product) || is_object($product)) {
            $productId = is_array($product) ? ($product['id'] ?? null) : ($product->id ?? null);
        } elseif (is_numeric($product)) {
            $productId = (int) $product;
        }
        if (! $productId) {
            return false;
        }

        return $this->wishlists()
            ->where('product_id', $productId)
            ->exists();
    }

    public function getBadgeNameAttribute(): string
    {
        $loans = $this->loans()->count();
        if ($loans >= 15) return '👑 Kutu Buku Elite';
        if ($loans >= 5) return '🎖️ Pustakawan Aktif';
        return '🌱 Anggota Baru';
    }

    public function getBadgeColorAttribute(): string
    {
        $loans = $this->loans()->count();
        if ($loans >= 15) return 'warning';
        if ($loans >= 5) return 'primary';
        return 'success';
    }
}
