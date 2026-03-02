<?php
// app/Models/ProductImage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'image_path',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    // ==================== RELATIONSHIPS ====================

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // ==================== ACCESSORS ====================

    /**
     * URL gambar lengkap.
     */
    public function getImageUrlAttribute(): string
    {
        // Jika sudah URL lengkap (http/https), langsung kembalikan
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }

        // Jika menggunakan storage/ (file di storage/app/public), generate URL storage
        if (str_starts_with($this->image_path, 'storage/')) {
            return asset($this->image_path);
        }
        
        // Untuk path seperti 'products/uuid.ext' di database (disimpan via Storage::disk('public')->storeAs()),
        // file sebenarnya ada di storage/app/public/products/xxx.ext
        // jadi kita perlu tambahkan prefix 'storage/'
        if (str_starts_with($this->image_path, 'products/')) {
            return asset('storage/' . $this->image_path);
        }
        
        // Untuk path seperti 'books/book1.jpeg' di database,
        // file sebenarnya ada di public/images/books/book1.jpeg
        // jadi kita perlu menambahkan prefix 'images/'
        $path = $this->image_path;
        if (!str_starts_with($path, 'images/') && !str_starts_with($path, 'storage/')) {
            $path = 'images/' . $path;
        }
        
        return asset($path);
    }

    /**
     * URL thumbnail (jika menggunakan image processing).
     */
    public function getThumbnailUrlAttribute(): string
    {
        // Jika punya thumbnail terpisah
        $thumbnailPath = str_replace('.', '_thumb.', $this->image_path);

        if (Storage::disk('public')->exists($thumbnailPath)) {
            return asset('storage/' . $thumbnailPath);
        }

        // Fallback ke image URL reguler
        if (str_starts_with($this->image_path, 'products/')) {
            return asset('storage/' . $this->image_path);
        }
        
        $path = $this->image_path;
        if (!str_starts_with($path, 'images/') && !str_starts_with($path, 'storage/')) {
            $path = 'images/' . $path;
        }
        return asset($path);
    }

    // ==================== BOOT ====================

    protected static function boot()
    {
        parent::boot();

        // Hapus file saat record dihapus
        static::deleting(function ($image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        });
    }

    // ==================== HELPER METHODS ====================

    /**
     * Set gambar ini sebagai primary.
     */
    public function makePrimary(): void
    {
        // Unset primary lainnya
        $this->product->images()
            ->where('id', '!=', $this->id)
            ->update(['is_primary' => false]);

        // Set ini sebagai primary
        $this->update(['is_primary' => true]);
    }
}
