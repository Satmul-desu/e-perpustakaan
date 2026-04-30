<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\CatalogService;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    protected $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function index(Request $request)
    {
        $products = $this->catalogService->getPaginatedProducts($request->all())
            ->withQueryString();
            
        $categories = Category::active()
            ->withCount(['products' => fn ($q) => $q->available()])
            ->whereHas('products', function ($q) {
                $q->available();
            })
            ->orderBy('name')
            ->get();
        $priceRange = Product::available()
            ->selectRaw('MIN(price) as min, MAX(price) as max')
            ->first();

        return view('catalog.index', compact('products', 'categories', 'priceRange'));
    }

    public function show($slug)
    {
        $product = Product::available()
            ->with(['category', 'images'])
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedBooks = Product::available()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('primaryImage')
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('catalog.show', compact('product', 'relatedBooks'));
    }

    public function searchSuggestions(Request $request)
    {
        $products = $this->catalogService->getSearchSuggestions(
            $request->get('q', ''),
            $request->get('limit', 8)
        );

        $suggestions = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->formatted_price,
                'final_price' => $product->formatted_final_price,
                'has_discount' => $product->has_discount,
                'image_url' => $product->image_url,
                'url' => route('catalog.show', $product->slug),
            ];
        });

        return response()->json([
            'query' => $query,
            'count' => $suggestions->count(),
            'products' => $suggestions,
        ]);
    }
}
