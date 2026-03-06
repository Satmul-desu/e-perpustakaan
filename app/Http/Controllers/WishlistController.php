<?php
namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class WishlistController extends Controller
{
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
        if (!auth()->check()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Silakan login terlebih dahulu.',
            ], 401);
        }
        $user = auth()->user();
        if ($user->hasInWishlist($product)) {
            $user->wishlists()->where('product_id', $product->id)->delete();
            $added   = false; 
            $message = 'Produk dihapus dari wishlist.';
        } else {
            $user->wishlists()->create(['product_id' => $product->id]);
            $added   = true; 
            $message = 'Produk ditambahkan ke wishlist!';
        }
        return response()->json([
            'status'  => 'success',
            'added'   => $added,
            'message' => $message,
            'count'   => $user->wishlists()->count(), 
        ]);
    }
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        $product = Product::findOrFail($request->product_id);
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        $user = auth()->user();
        if (!$user->hasInWishlist($product)) {
            $user->wishlists()->create(['product_id' => $product->id]);
            return back()->with('success', 'Produk ditambahkan ke wishlist!');
        }
        return back()->with('info', 'Produk sudah ada di wishlist.');
    }
    public function destroy(Product $product): \Illuminate\Http\RedirectResponse
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        $user = auth()->user();
        if ($user->hasInWishlist($product)) {
            $user->wishlists()->where('product_id', $product->id)->delete();
            return back()->with('success', 'Produk dihapus dari wishlist.');
        }
        return back()->with('info', 'Produk tidak ada di wishlist.');
    }
}