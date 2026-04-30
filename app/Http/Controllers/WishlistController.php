<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    protected $wishlistService;

    public function __construct(WishlistService $wishlistService)
    {
        $this->wishlistService = $wishlistService;
    }

    public function index()
    {
        $products = auth()->user()->wishlists()
            ->with(['product.category', 'product.primaryImage', 'product.images'])
            ->latest('wishlists.created_at')
            ->paginate(12);

        return view('wishlist.index', compact('products'));
    }

    public function toggle(Product $product): JsonResponse
    {
        if (! auth()->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Silakan login terlebih dahulu.',
            ], 401);
        }
        
        $result = $this->wishlistService->toggle(auth()->user(), $product);

        return response()->json([
            'status' => 'success',
            'added' => $result['added'],
            'message' => $result['message'],
            'count' => auth()->user()->wishlists()->count(),
        ]);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        $product = Product::findOrFail($request->product_id);
        if (! auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $added = $this->wishlistService->addToWishlist(auth()->user(), $product);
        
        if ($added) {
            return back()->with('success', 'Produk ditambahkan ke wishlist!');
        }

        return back()->with('info', 'Produk sudah ada di wishlist.');
    }

    public function destroy(Product $product): \Illuminate\Http\RedirectResponse
    {
        if (! auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $removed = $this->wishlistService->removeFromWishlist(auth()->user(), $product);
        
        if ($removed) {
            return back()->with('success', 'Produk dihapus dari wishlist.');
        }

        return back()->with('info', 'Produk tidak ada di wishlist.');
    }
}
