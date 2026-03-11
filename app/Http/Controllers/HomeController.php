<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->active()
            ->withCount(['activeProducts' => function ($q) {
                $q->where('is_active', true)
                    ->where('stock', '>', 0);
            }])
            ->whereHas('activeProducts', function ($q) {
                $q->where('is_active', true)
                    ->where('stock', '>', 0);
            })
            ->orderBy('name')
            ->take(8)
            ->get();
        $featuredProducts = Product::query()
            ->with(['category:id,name,slug', 'primaryImage:id,image_path,product_id'])
            ->active()
            ->inStock()
            ->featured()
            ->latest()
            ->take(20)
            ->get();
        $latestProducts = Product::query()
            ->with(['category', 'primaryImage'])
            ->active()
            ->inStock()
            ->latest()
            ->take(8)
            ->get();

        return view('home', compact(
            'categories',
            'featuredProducts',
            'latestProducts'
        ));
    }

    public function flashSale()
    {
        $flashSaleProducts = Product::query()
            ->with(['category:id,name,slug', 'primaryImage:id,image_path,product_id'])
            ->active()
            ->inStock()
            ->whereNotNull('discount_price')
            ->orWhere('discount_price', '>', 0)
            ->latest()
            ->paginate(20);

        return view('flash-sale', compact('flashSaleProducts'));
    }
}
