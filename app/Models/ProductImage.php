<?php
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
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function getImageUrlAttribute(): string
    {
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }
        if (str_starts_with($this->image_path, 'storage/')) {
            return asset($this->image_path);
        }
        if (str_starts_with($this->image_path, 'products/')) {
            return asset('storage/' . $this->image_path);
        }
        $path = $this->image_path;
        if (!str_starts_with($path, 'images/') && !str_starts_with($path, 'storage/')) {
            $path = 'images/' . $path;
        }
        return asset($path);
    }
    public function getThumbnailUrlAttribute(): string
    {
        $thumbnailPath = str_replace('.', '_thumb.', $this->image_path);
        if (Storage::disk('public')->exists($thumbnailPath)) {
            return asset('storage/' . $thumbnailPath);
        }
        if (str_starts_with($this->image_path, 'products/')) {
            return asset('storage/' . $this->image_path);
        }
        $path = $this->image_path;
        if (!str_starts_with($path, 'images/') && !str_starts_with($path, 'storage/')) {
            $path = 'images/' . $path;
        }
        return asset($path);
    }
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        });
    }
    public function makePrimary(): void
    {
        $this->product->images()
            ->where('id', '!=', $this->id)
            ->update(['is_primary' => false]);
        $this->update(['is_primary' => true]);
    }
}