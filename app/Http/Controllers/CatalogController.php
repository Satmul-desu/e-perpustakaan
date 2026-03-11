<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->select('id', 'name', 'slug', 'price', 'discount_price', 'category_id', 'stock', 'is_active', 'created_at')
            ->with(['category:id,name,slug', 'primaryImage:id,image_path,product_id'])
            ->available();
        if ($request->filled('q')) {
            $query->search($request->q);
        }
        if ($request->filled('category')) {
            $categorySlug = $request->category;
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
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        $sort = $request->get('sort', 'newest');
        $query->when($sort === 'price_asc', fn ($q) => $q->orderBy('price', 'asc'))
            ->when($sort === 'price_desc', fn ($q) => $q->orderBy('price', 'desc'))
            ->when($sort === 'name_asc', fn ($q) => $q->orderBy('name', 'asc'))
            ->when($sort === 'name_desc', fn ($q) => $q->orderBy('name', 'desc'))
            ->when($sort === 'newest', fn ($q) => $q->latest());
        $products = $query->paginate(12)->withQueryString();
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

        return view('catalog.show', compact('product'));
    }

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
