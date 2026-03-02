<?php
// app/Http/Controllers/WishlistController.php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Menampilkan halaman daftar wishlist user.
     */
    public function index()
    {
        // Ambil produk yang di-wishlist oleh user yang sedang login
        $products = auth()->user()->wishlists()
            ->with(['product.category', 'product.primaryImage', 'product.images']) // Eager load through product
            ->latest('wishlists.created_at')     // Urutkan dari yang baru di-wishlist
            ->paginate(12);

        return view('wishlist.index', compact('products'));
    }

    /**
     * Toggle wishlist (AJAX handler).
     * Endpoint ini akan dipanggil oleh JavaScript.
     *
     * Konsep Toggle:
     * - Jika user SUDAH like -> Hapus (Unlike/Delete)
     * - Jika user BELUM like -> Tambah (Like/Create)
     */
    public function toggle(Product $product): JsonResponse
    {
        // Pastikan user sudah login (冗余检查 untuk keamanan)
        if (!auth()->check()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Silakan login terlebih dahulu.',
            ], 401);
        }

        $user = auth()->user();

        // 1. Cek apakah produk ini ada di daftar wishlist user?
        if ($user->hasInWishlist($product)) {
            // Skenario: User mau UNLIKE
            // Hapus record wishlist berdasarkan product_id
            $user->wishlists()->where('product_id', $product->id)->delete();

            $added   = false; // Indikator untuk frontend: "Hapus warna merah"
            $message = 'Produk dihapus dari wishlist.';
        } else {
            // Skenario: User mau LIKE
            // Create record baru di tabel wishlists
            $user->wishlists()->create(['product_id' => $product->id]);

            $added   = true; // Indikator untuk frontend: "Ubah jadi merah"
            $message = 'Produk ditambahkan ke wishlist!';
        }

        // Return JSON response yang ringan untuk JavaScript
        // Kita kirim status "added" agar JS tahu harus ganti ikon love jadi merah atau abu-abu.
        return response()->json([
            'status'  => 'success',
            'added'   => $added,
            'message' => $message,
            'count'   => $user->wishlists()->count(), // Kirim jumlah terbaru untuk update badge header
        ]);
    }

    /**
     * Add to wishlist (non-AJAX fallback).
     */
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

    /**
     * Remove from wishlist (non-AJAX fallback).
     */
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
