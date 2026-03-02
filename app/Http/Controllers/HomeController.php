<?php
// ================================================
// FILE: app/Http/Controllers/HomeController.php
// FUNGSI: Menangani halaman utama website
// ================================================

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman beranda.
     *
     * Halaman ini menampilkan:
     * - Hero section (static)
     * - Kategori populer
     * - Produk unggulan (featured)
     * - Produk terbaru
     */
    public function index()
    {
        // ================================================
        // AMBIL DATA KATEGORI
        // - Hanya yang aktif
        // - Hitung jumlah produk di masing-masing kategori
        // ================================================
        $categories = Category::query()
            ->active() // Scope: hanya is_active = true
            ->withCount(['activeProducts' => function ($q) {
                $q->where('is_active', true)
                    ->where('stock', '>', 0);
            }])
            ->whereHas('activeProducts', function ($q) {
                $q->where('is_active', true)
                    ->where('stock', '>', 0);
            }) // Hanya yang punya produk aktif
            ->orderBy('name')
            ->take(8) // Batasi 8 kategori
            ->get();

        // ================================================
        // PRODUK UNGGULAN (FEATURED)
        // - Flag is_featured = true
        // - Aktif dan ada stok
        // - Tampilkan 20 produk
        // ================================================
        $featuredProducts = Product::query()
            ->with(['category:id,name,slug', 'primaryImage:id,image_path,product_id']) // Eager load with specific columns
            ->active()                           // Scope: is_active = true
            ->inStock()                          // Scope: stock > 0
            ->featured()                         // Scope: is_featured = true
            ->latest()
            ->take(20)
            ->get();

        // ================================================
        // PRODUK TERBARU
        // - Urutkan dari yang paling baru
        // ================================================
        $latestProducts = Product::query()
            ->with(['category', 'primaryImage'])
            ->active()
            ->inStock()
            ->latest() // Order by created_at DESC
            ->take(8)
            ->get();

        // ================================================
        // KIRIM DATA KE VIEW
        // compact() membuat array ['key' => $key]
        // ================================================
        return view('home', compact(
            'categories',
            'featuredProducts',
            'latestProducts'
        ));
    }

    /**
     * Menampilkan halaman flash sale.
     *
     * Halaman ini menampilkan produk dengan diskon (flash sale)
     * dari database, bukan data hardcoded.
     */
    public function flashSale()
    {
        // ================================================
        // PRODUK FLASH SALE
        // - Ambil produk dengan diskon dari database
        // - Aktif dan ada stok
        // ================================================
        $flashSaleProducts = Product::query()
            ->with(['category:id,name,slug', 'primaryImage:id,image_path,product_id']) // Eager load with specific columns
            ->active()                           // Scope: is_active = true
            ->inStock()                          // Scope: stock > 0
            ->whereNotNull('discount_price')     // Hanya produk dengan diskon
            ->orWhere('discount_price', '>', 0)
            ->latest()
            ->paginate(20); // 20 produk per halaman

        // ================================================
        // KIRIM DATA KE VIEW
        // ================================================
        return view('flash-sale', compact('flashSaleProducts'));
    }
}
