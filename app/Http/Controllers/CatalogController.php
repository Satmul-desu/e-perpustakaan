<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CatalogController extends Controller
{
    /**
     * Menampilkan halaman catalog publik dengan fitur filter lengkap.
     * Logika filtering dibangun secara dinamis menggunakan chain method.
     */
    public function index(Request $request)
    {
        // 1. BASE QUERY
        // Mulai dengan query dasar: ambil produk
        // ->with(): Eager Load relasi category & primaryImage untuk menghindari query berulang (N+1).
        // ->available(): Query Scope (lokal di model Product) yang memfilter produk aktif & stok > 0.
        $query = Product::query()
            ->select('id', 'name', 'slug', 'price', 'discount_price', 'category_id', 'stock', 'is_active', 'created_at') // Select only needed columns
            ->with(['category:id,name,slug', 'primaryImage:id,image_path,product_id']) // Eager load with specific columns
            ->available(); // Scope 'available'

        // 2. FILTERING PIPELINE
        // Menerapkan filter hanya jikda user mengirimkan parameter tertentu.

        // Filter: Search keyword (?q=iphone)
        if ($request->filled('q')) {
            $query->search($request->q); // Scope 'search'
        }

        // Filter: Category by Slug (?category=elektronik)
        // Modified untuk Mencari berdasarkan keyword yang terkait dengan genre/kategori
        if ($request->filled('category')) {
            $categorySlug = $request->category;
            
            // Mapping Genre Slug ke Keywords untuk pencarian
            // Logic: Jika user pilih genre X, cari semua buku yang:
            // 1. Berada di kategori dengan slug X
            // 2. ATAU memiliki keyword terkait X di nama buku
            
            $genreMappings = [
                'romance' => ['romance', 'cinta', 'love', 'romantis', 'twisted', 'boulevard', 'binding', 'sweetest oblivion', 'hooked', 'crossed', 'scarred', 'five feet apart', 'tentang kamu', 'kita & waktu', 'fabricante de la grimas', 'scarlet huntress', 'kau aku dan sepucuk angpau merah', 'the sweetest oblivion'],
                'fantasi' => ['fantasi', 'magic', 'dunia paralel', 'harry potter', 'fourth wing', 'nevernight', 'crimson rogue', 'fire in the sky', 'scarlet huntress', 'witch', 'uncrowned queen', 'sea witch', 'dark moon', 'kesatria putri & bintang jatuh', 'matahari', 'bintang', 'bumi', 'bulan', 'aprendiz del villano', 'human', 'ksatria'],
                'drama' => ['drama', 'tragis', 'selamat tinggal', 'bandung after rain', 'van der wijck', 'dilan 1990', 'echoes in the dark', 'tenggelamnya kapal', 'bandung after rain'],
                'inspiratif' => ['inspiratif', 'motivasi', 'inspirasi', 'mimpi', 'ranah 3 warna', 'rantau 1 muara', 'ranah', 'rantau'],
                'horor' => ['horor', 'seram', 'hantu', 'ghost', 'nightbooks', 'asylum', 'hide and don\'t seek', 'jack the ripper'],
                'thriller' => ['thriller', 'hide and don\'t seek', 'inherited', 'death pact', 'cold lake', 'the death pact'],
                'misteri' => ['misteri', 'mystery', 'jack the ripper'],
                'fiksi-ilmiah' => ['fiksi ilmiah', 'science fiction', 'sci-fi', 'hujan', 'human'],
                'fiksi-remaja' => ['fiksi remaja', 'young adult', 'remaja', 'they both die at the end', 'shadow girl', 'shadow gril'],
                'politik' => ['politik', 'korupsi', 'konspirasi', 'negeri para bedebah'],
                'agama' => ['agama', 'spiritual', 'keimanan'],
            ];
            
            if (isset($genreMappings[$categorySlug])) {
                $keywords = $genreMappings[$categorySlug];
                
                // Cek apakah ada kategori dengan slug ini di database
                $category = Category::where('slug', $categorySlug)->first();
                
                $query->where(function ($q) use ($keywords, $category) {
                    // Kondisi 1: Cari berdasarkan category slug jika kategori ada
                    if ($category) {
                        $q->orWhere('category_id', $category->id);
                    }
                    
                    // Kondisi 2: Cari di nama buku berdasarkan keyword genre
                    foreach ($keywords as $keyword) {
                        $q->orWhere('name', 'LIKE', "%{$keyword}%");
                    }
                });
            } else {
                // Jika tidak ada di mapping, cari berdasarkan slug kategori
                $category = Category::where('slug', $categorySlug)->first();
                if ($category) {
                    $query->where('category_id', $category->id);
                }
            }
        }

        // Filter: Price Range (?min_price=1000&max_price=50000)
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // 3. SORTING LOGIC (?sort=price_asc)
        // Default sorting adalah 'newest' (terbaru).
        $sort = $request->get('sort', 'newest');

        // Menggunakan method when() untuk penulisan if-else yang lebih bersih (fungsional).
        $query->when($sort === 'price_asc', fn($q) => $q->orderBy('price', 'asc'))
            ->when($sort === 'price_desc', fn($q) => $q->orderBy('price', 'desc'))
            ->when($sort === 'name_asc', fn($q) => $q->orderBy('name', 'asc'))
            ->when($sort === 'name_desc', fn($q) => $q->orderBy('name', 'desc'))
            ->when($sort === 'newest', fn($q) => $q->latest());

        // 4. EXECUTE & PAGINATE
        // Jalankan query dan ambil 12 produk per halaman.
        // withQueryString(): Menempelkan parameter filter saat ini ke link pagination (Next/Prev).
        // Tanpa ini, jika user klik halaman 2, filter pencariannya akan hilang.
        $products = $query->paginate(12)->withQueryString();

        // 5. DATA PENDUKUNG VIEW (SIDEBAR)

        // Ambil daftar kategori, TAPI:
        // ->withCount(): Hitung jumlah produk available di dalamnya.
        // ->having(): Hanya ambil kategori yang PUNYA produk (minimal 1).
        // Ini mencegah user memilih kategori kosong.
        $categories = Category::active()
            ->withCount(['products' => fn($q) => $q->available()])
            ->whereHas('products', function ($q) {
                $q->available();
            }) // Hanya yang punya produk
            ->orderBy('name')
            ->get();

        // Hitung Range harga global untuk keperluan UI (misal slider harga minimum-maksimum).
        // selectRaw lebih efisien daripada tarik semua data lalu di loop php.
        $priceRange = Product::available()
            ->selectRaw('MIN(price) as min, MAX(price) as max')
            ->first();

        return view('catalog.index', compact('products', 'categories', 'priceRange'));
    }

    /**
     * Menampilkan detail produk (Single Product Page).
     */
    public function show($slug)
    {
        // Cari produk berdasarkan SLUG, bukan ID (SEO Friendly).
        // PENTING: Gunakan scope available() agar user tidak bisa akses produk yang non-aktif via URL langsung.
        $product = Product::available()
            ->with(['category', 'images']) // Load semua gambar galeri
            ->where('slug', $slug)
            ->firstOrFail(); // 404 jika tidak ketemu

        return view('catalog.show', compact('product'));
    }

    /**
     * API Endpoint untuk Live Search Autocomplete
     * Mengembalikan JSON dengan suggestions buku berdasarkan keyword
     */
    public function searchSuggestions(Request $request)
    {
        $query = $request->get('q', '');
        $limit = $request->get('limit', 8);

        if (empty($query) || strlen($query) < 1) {
            return response()->json(['products' => []]);
        }

        $products = Product::query()
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
