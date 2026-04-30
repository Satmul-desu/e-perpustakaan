<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CatalogService
{
    public function getPaginatedProducts(array $filters): LengthAwarePaginator
    {
        $query = Product::query()
            ->select('id', 'name', 'slug', 'price', 'discount_price', 'category_id', 'stock', 'is_active', 'created_at')
            ->with(['category:id,name,slug', 'primaryImage:id,image_path,product_id'])
            ->available();

        if (!empty($filters['q'])) {
            $query->search($filters['q']);
        }

        if (!empty($filters['category'])) {
            $categorySlug = $filters['category'];
            $genreMappings = config('genres.mappings', []);

            if (isset($genreMappings[$categorySlug])) {
                $keywords = $genreMappings[$categorySlug];
                $category = Category::where('slug', $categorySlug)->first();
                $query->where(function ($q) use ($keywords, $category) {
                    if ($category) {
                        $q->orWhere('category_id', $category->id);
                    }
                    foreach ($keywords as $keyword) {
                        $q->orWhere('name', 'LIKE', "%{$keyword}%");
                    }
                });
            } else {
                $category = Category::where('slug', $categorySlug)->first();
                if ($category) {
                    $query->where('category_id', $category->id);
                }
            }
        }

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        $sort = $filters['sort'] ?? 'newest';
        $query->when($sort === 'price_asc', fn ($q) => $q->orderBy('price', 'asc'))
            ->when($sort === 'price_desc', fn ($q) => $q->orderBy('price', 'desc'))
            ->when($sort === 'name_asc', fn ($q) => $q->orderBy('name', 'asc'))
            ->when($sort === 'name_desc', fn ($q) => $q->orderBy('name', 'desc'))
            ->when($sort === 'newest', fn ($q) => $q->latest());

        return $query->paginate(12);
    }

    public function getSearchSuggestions(string $query, int $limit = 8): Collection
    {
        if (empty($query) || strlen($query) < 1) {
            return collect();
        }

        return Product::query()
            ->select('id', 'name', 'slug', 'price', 'discount_price')
            ->with(['primaryImage:id,image_path,product_id'])
            ->available()
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->orderBy('name', 'asc')
            ->limit($limit)
            ->get();
    }
}