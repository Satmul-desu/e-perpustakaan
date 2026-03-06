<?php
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
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with(['category', 'primaryImage', 'images'])
            ->when($request->search, function ($query, $search) {
                $query->search($search); 
            })
          ->when($request->category, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->latest()          
            ->paginate(15)       
            ->withQueryString(); 
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.products.index', compact('products', 'categories'));
    }
    public function create(): View
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }
    public function store(StoreProductRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validated();
            if (!isset($validatedData['price'])) {
                $validatedData['price'] = 0;
            }
            $product = Product::create($validatedData);
            if ($request->hasFile('images')) {
                $this->uploadImages($request->file('images'), $product);
            }
            DB::commit();
            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Buku berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan buku: ' . $e->getMessage());
        }
    }
    public function show(Product $product): View
    {
        $product->load([
            'category', 
            'images', 
            'primaryImage',
            'orderItems.order.user' 
        ]);
        return view('admin.products.show', compact('product'));
    }
    public function edit(Product $product): View
    {
        $categories = Category::active()->orderBy('name')->get();
        $product->load(['images', 'primaryImage', 'category']);
        return view('admin.products.edit', compact('product', 'categories'));
    }
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validated();
            if (!isset($validatedData['price'])) {
                $validatedData['price'] = $product->price ?? 0;
            }
            $product->update($validatedData);
            if ($request->hasFile('images')) {
                $this->uploadImages($request->file('images'), $product);
            }
            if ($request->has('delete_images')) {
                $this->deleteImages($request->delete_images);
            }
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
    public function destroy(Product $product): RedirectResponse
    {
        try {
            if ($product->orderItems()->exists()) {
                return back()->with('error', 'Gagal menghapus: Produk ini sudah ada dalam pesanan dan tidak dapat dihapus. Pertimbangkan untuk menonaktifkan produk (non-aktifkan) sebagai gantinya.');
            }
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }
            $product->delete();
            return redirect()->route('admin.products.index')->with('success', 'Produk dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
    protected function uploadImages(array $files, Product $product): void
    {
        $isFirst = $product->images()->count() === 0;
        foreach ($files as $index => $file) {
            $filename = \Illuminate\Support\Str::uuid() . '.' . $file->extension();
            $path = $file->storeAs('products', $filename, 'public');
            $product->images()->create([
                'image_path' => $path,
                'is_primary' => $isFirst && $index === 0,
                'sort_order' => $product->images()->count() + $index,
            ]);
        }
    }
    protected function deleteImages(array $imageIds): void
    {
        $images = ProductImage::whereIn('id', $imageIds)->get();
        foreach ($images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }
    }
    protected function setPrimaryImage(Product $product, int $imageId): void
    {
        $product->images()->update(['is_primary' => false]);
        $product->images()->where('id', $imageId)->update(['is_primary' => true]);
    }
}