<?php
// app/Http/Controllers/Admin/ProductController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk dengan fitur pagination dan filtering.
     */
    public function index(Request $request): View
    {
        $products = Product::query()
        // Eager Loading: Meload relasi kategori & gambar utama sekaligus.
        // Tanpa 'with', Laravel akan mengeksekusi 1 query tambahan untuk SETIAP baris produk (N+1 Problem).
            ->with(['category', 'primaryImage', 'images'])

        // Filter: Pencarian nama produk
            ->when($request->search, function ($query, $search) {
                $query->search($search); // Menggunakan Scope 'search' di Model Product
            })
        // Filter: Berdasarkan Kategori
            ->when($request->category, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->latest()           // Urut dari yang terbaru
            ->paginate(15)       // Batasi 15 item per halaman
            ->withQueryString(); // Memastikan parameter URL (?search=xx) tetap ada saat pindah halaman

        // Ambil data kategori untuk dropdown filter di view
        $categories = Category::active()->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Menampilkan form tambah produk.
     */
    public function create(): View
    {
        // Ambil kategori untuk dropdown.
        // HANYA kategori yang aktif yang boleh dipilih.
        $categories = Category::active()->orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Menyimpan produk baru ke database.
     * Menggunakan DB Transaction untuk integritas data (Product + Images).
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        try {
            // === DB TRANSACTION START ===
            DB::beginTransaction();

            // 1. Siapkan data produk dengan nilai default untuk price (karena ini library, tidak perlu harga)
            $validatedData = $request->validated();
            
            // Set default price ke 0 untuk library (buku tidak dijual)
            if (!isset($validatedData['price'])) {
                $validatedData['price'] = 0;
            }

            // 2. Simpan data produk
            $product = Product::create($validatedData);

            // 3. Upload gambar (jika ada)
            if ($request->hasFile('images')) {
                $this->uploadImages($request->file('images'), $product);
            }

            // === DB TRANSACTION COMMIT ===
            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Buku berhasil ditambahkan!');

        } catch (\Exception $e) {
            // === DB TRANSACTION ROLLBACK ===
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan buku: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail produk.
     */
    public function show(Product $product): View
    {
        // Load semua relasi yang dibutuhkan untuk halaman detail dengan eager loading
        // Gunakan nested eager loading untuk menghindari N+1 query problem
        $product->load([
            'category', 
            'images', 
            'primaryImage',
            'orderItems.order.user'  // Nested eager loading untuk orderItems->order->user
        ]);

        return view('admin.products.show', compact('product'));
    }

    /**
     * Menampilkan form edit produk.
     */
    public function edit(Product $product): View
    {
        $categories = Category::active()->orderBy('name')->get();
        // Load gambar yang sudah ada agar bisa ditampilkan/dihapus di form edit.
        $product->load(['images', 'primaryImage', 'category']);

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Memperbarui data produk.
     * Juga menggunakan Transaction karena melibatkan update produk + upload/delete gambar.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // 1. Update data dasar produk
            $validatedData = $request->validated();
            
            // Ensure price has a value (for library system)
            if (!isset($validatedData['price'])) {
                $validatedData['price'] = $product->price ?? 0;
            }

            $product->update($validatedData);

            // 2. Upload gambar BARU (jika user menambah gambar)
            if ($request->hasFile('images')) {
                $this->uploadImages($request->file('images'), $product);
            }

            // 3. Hapus gambar LAMA (yang dicentang user untuk dihapus)
            if ($request->has('delete_images')) {
                $this->deleteImages($request->delete_images);
            }

            // 4. Set gambar Utama (Primary Image)
            if ($request->has('primary_image')) {
                $this->setPrimaryImage($product, $request->primary_image);
            }

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Buku berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus produk.
     */
    public function destroy(Product $product): RedirectResponse
    {
        try {
            // Cek apakah produk memiliki item pesanan terkait
            // Jika ada, produk tidak bisa dihapus untuk menjaga integritas data pesanan
            if ($product->orderItems()->exists()) {
                return back()->with('error', 'Gagal menghapus: Produk ini sudah ada dalam pesanan dan tidak dapat dihapus. Pertimbangkan untuk menonaktifkan produk (non-aktifkan) sebagai gantinya.');
            }

            // Loop dan hapus semua file gambar fisik dari server.
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            // Hapus record produk dari database.
            $product->delete();

            return redirect()->route('admin.products.index')->with('success', 'Produk dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    // --- Helper Methods ---
    // Method protected agar tidak bisa diakses via URL/Route, hanya internal class ini.

    protected function uploadImages(array $files, Product $product): void
    {
        // Cek apakah produk ini baru pertama kali punya gambar?
        // Jika ya, gambar pertama yang diupload otomatis jadi Primary.
        $isFirst = $product->images()->count() === 0;

        foreach ($files as $index => $file) {
            // Generate nama unik menggunakan UUID untuk keamanan
            $filename = \Illuminate\Support\Str::uuid() . '.' . $file->extension();

            // Simpan fisik file
            $path = $file->storeAs('products', $filename, 'public');

            // Simpan info ke database table product_images
            $product->images()->create([
                'image_path' => $path,
                // Jika ini gambar pertama, set as primary
                'is_primary' => $isFirst && $index === 0,
                'sort_order' => $product->images()->count() + $index,
            ]);
        }
    }

    protected function deleteImages(array $imageIds): void
    {
        // Ambil data gambar berdasarkan ID yang dikirim
        $images = ProductImage::whereIn('id', $imageIds)->get();

        foreach ($images as $image) {
            // Hapus file fisik
            Storage::disk('public')->delete($image->image_path);
            // Hapus record DB
            $image->delete();
        }
    }

    protected function setPrimaryImage(Product $product, int $imageId): void
    {
        // Reset semua gambar produk ini jadi non-primary
        $product->images()->update(['is_primary' => false]);

        // Set gambar yang dipilih jadi primary
        $product->images()->where('id', $imageId)->update(['is_primary' => true]);
    }
}
