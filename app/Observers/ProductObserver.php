<?php
namespace App\Observers;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
class ProductObserver
{
    public function created(Product $product): void
    {
        Cache::forget('featured_products');
        Cache::forget('category_' . $product->category_id . '_products');
        activity()
            ->performedOn($product)
            ->causedBy(auth()->user())
            ->log('Produk baru dibuat: ' . $product->name);
    }
    public function updated(Product $product): void
    {
        Cache::forget('product_' . $product->id);
        Cache::forget('featured_products');
        if ($product->isDirty('category_id')) {
            Cache::forget('category_' . $product->getOriginal('category_id') . '_products');
            Cache::forget('category_' . $product->category_id . '_products');
        }
    }
    public function deleted(Product $product): void
    {
        Cache::forget('product_' . $product->id);
        Cache::forget('featured_products');
        Cache::forget('category_' . $product->category_id . '_products');
    }
}