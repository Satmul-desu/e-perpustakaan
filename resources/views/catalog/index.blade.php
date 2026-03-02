@extends('layouts.app')

@section('content')
<div class="catalog-page py-4">
    <div class="container">
        <div class="row g-4">
            {{-- SIDEBAR FILTER (Desktop) --}}
            <div class="col-lg-3 d-none d-lg-block">
                <div class="filter-sidebar card border-0" style="background: rgba(30, 41, 59, 0.95); border: 1px solid #334155 !important; position: sticky; top: 80px;">
                    <div class="card-header bg-transparent border-bottom border-secondary fw-bold py-3">
                        <i class="bi bi-funnel me-2"></i>Filter Produk
                    </div>
                    <div class="card-body">
                        <form action="{{ route('catalog.index') }}" method="GET" id="filter-form">
                            @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif

                            {{-- Genre Filter --}}
                            <div class="filter-group mb-4">
                                <h6 class="fw-bold mb-3 text-white d-flex align-items-center">
                                    <i class="bi bi-grid-3x3-gap me-2" style="color: #60a5fa;"></i>
                                    Genre
                                </h6>
                                
                                {{-- Search Genre --}}
                                <div class="mb-3">
                                    <input type="text" id="genre-search" class="form-control form-control-sm" 
                                        placeholder="Cari genre..."
                                        style="background: rgba(15, 23, 42, 0.6); border: 1px solid #334155; color: white; font-size: 0.85rem;">
                                </div>

                                {{-- Radio Buttons --}}
                                <div class="genre-list">
                                    <div class="form-check mb-2 ps-0 genre-item">
                                        <label class="form-check-label d-flex align-items-center cursor-pointer" for="category_all">
                                            <input class="form-check-input me-2 mt-0" type="radio" name="category" 
                                                value="" id="category_all"
                                                {{ !request('category') ? 'checked' : '' }}>
                                            <span class="text-light">Semua Genre</span>
                                            <span class="badge bg-secondary ms-auto" style="font-size: 0.7rem;">{{ $products->total() }}</span>
                                        </label>
                                    </div>

                                    @php
                                        $genreList = collect([
                                            ['slug' => 'romance', 'name' => 'Romance', 'icon' => '💕', 'count' => 17],
                                            ['slug' => 'fantasi', 'name' => 'Fantasi', 'icon' => '✨', 'count' => 15],
                                            ['slug' => 'drama', 'name' => 'Drama', 'icon' => '🎭', 'count' => 8],
                                            ['slug' => 'inspiratif', 'name' => 'Inspiratif', 'icon' => '🌟', 'count' => 6],
                                            ['slug' => 'horor', 'name' => 'Horor', 'icon' => '👻', 'count' => 5],
                                            ['slug' => 'fiksi-remaja', 'name' => 'Fiksi Remaja', 'icon' => '📖', 'count' => 5],
                                            ['slug' => 'politik', 'name' => 'Politik', 'icon' => '⚖️', 'count' => 2],
                                            ['slug' => 'agama', 'name' => 'Agama', 'icon' => '🕊️', 'count' => 4],
                                        ]);
                                    @endphp
                                    
                                    @foreach($genreList as $genre)
                                        <div class="form-check mb-2 ps-0 genre-item" data-name="{{ strtolower($genre['name']) }}">
                                            <label class="form-check-label d-flex align-items-center cursor-pointer" for="category_{{ $genre['slug'] }}">
                                                <input class="form-check-input me-2 mt-0" type="radio" name="category" 
                                                    value="{{ $genre['slug'] }}"
                                                    id="category_{{ $genre['slug'] }}"
                                                    {{ request('category') == $genre['slug'] ? 'checked' : '' }}>
                                                <span class="text-light">{{ $genre['icon'] }} {{ $genre['name'] }}</span>
                                                <span class="badge bg-secondary ms-auto" style="font-size: 0.7rem;">{{ $genre['count'] }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <hr class="border-secondary">

                            {{-- Price Filter --}}
                            <div class="filter-group mb-4">
                                <h6 class="fw-bold mb-3 text-white d-flex align-items-center">
                                    <i class="bi bi-currency-dollar me-2" style="color: #60a5fa;"></i>
                                    Rentang Harga
                                </h6>
                                <div class="price-inputs d-flex gap-2 align-items-center">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-dark border-secondary text-secondary">Rp</span>
                                        <input type="number" name="min_price" class="form-control" 
                                            placeholder="Min" value="{{ request('min_price') }}">
                                    </div>
                                    <span class="text-secondary">-</span>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-dark border-secondary text-secondary">Rp</span>
                                        <input type="number" name="max_price" class="form-control" 
                                            placeholder="Max" value="{{ request('max_price') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="filter-actions">
                                <button type="submit" class="btn btn-custom w-100 mb-2">
                                    <i class="bi bi-check-circle me-1"></i>Terapkan
                                </button>
                                <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- MAIN CONTENT --}}
            <div class="col-lg-9">
                {{-- Mobile Filter Toggle Button --}}
                <div class="d-lg-none mb-3">
                    <button class="btn btn-primary w-100" type="button" data-bs-toggle="collapse" data-bs-target="#mobileFilterCollapse">
                        <i class="bi bi-funnel me-2"></i>Filter & Urutkan
                    </button>
                    
                    {{-- Mobile Filter Collapse --}}
                    <div class="collapse mt-2" id="mobileFilterCollapse">
                        <div class="card border-0" style="background: rgba(30, 41, 59, 0.95); border: 1px solid #334155 !important;">
                            <div class="card-body">
                                <form action="{{ route('catalog.index') }}" method="GET" id="mobile-filter-form">
                                    @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif

                                    {{-- Mobile Genre Filter --}}
                                    <div class="filter-group mb-3">
                                        <h6 class="fw-bold mb-2 text-white">Genre</h6>
                                        <div class="d-flex flex-wrap gap-2">
                                            <input type="radio" class="btn-check" name="category" id="mobile_category_all" value="" {{ !request('category') ? 'checked' : '' }}>
                                            <label class="btn btn-sm btn-outline-secondary" for="mobile_category_all">Semua</label>
                                            
                                            @foreach($genreList as $genre)
                                                <input type="radio" class="btn-check" name="category" id="mobile_category_{{ $genre['slug'] }}" value="{{ $genre['slug'] }}" {{ request('category') == $genre['slug'] ? 'checked' : '' }}>
                                                <label class="btn btn-sm btn-outline-secondary" for="mobile_category_{{ $genre['slug'] }}">{{ $genre['icon'] }} {{ $genre['name'] }}</label>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Mobile Price Filter --}}
                                    <div class="filter-group mb-3">
                                        <h6 class="fw-bold mb-2 text-white">Rentang Harga</h6>
                                        <div class="d-flex gap-2">
                                            <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min" value="{{ request('min_price') }}">
                                            <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max" value="{{ request('max_price') }}">
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                                            <i class="bi bi-check-circle me-1"></i>Terapkan
                                        </button>
                                        <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Header dengan Live Search --}}
                <div class="catalog-header mb-3">
                    <div class="row align-items-center">
                        <div class="col-lg-8 mb-2 mb-lg-0">
                            <h4 class="mb-1 text-white d-flex align-items-center">
                                <i class="bi bi-book me-2" style="color: #60a5fa;"></i>
                                Katalog Buku
                            </h4>
                            <small class="text-secondary">
                                @if(request('category'))
                                    @php
                                        $genreName = collect([
                                            'romance' => 'Romance', 'fantasi' => 'Fantasi', 'drama' => 'Drama',
                                            'inspiratif' => 'Inspiratif', 'horor' => 'Horor', 'fiksi-remaja' => 'Fiksi Remaja',
                                            'politik' => 'Politik', 'agama' => 'Agama',
                                        ])->get(request('category'), request('category'));
                                    @endphp
                                    <span class="text-white fw-bold">📚 {{ $genreName }}</span>
                                @else
                                    Temukan buku favoritmu dengan berbagai genre menarik
                                @endif
                            </small>
                        </div>
                        <div class="col-lg-4">
                            {{-- Live Search --}}
                            <div class="search-wrapper position-relative">
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary">
                                        <i class="bi bi-search text-secondary"></i>
                                    </span>
                                    <input type="text" 
                                        name="q" 
                                        id="live-search-input"
                                        class="form-control" 
                                        placeholder="Cari buku..." 
                                        value="{{ request('q', '') }}"
                                        autocomplete="off">
                                </div>
                                {{-- Autocomplete Dropdown --}}
                                <div id="search-suggestions" class="search-suggestions position-absolute w-100 mt-1"></div>
                            </div>
                            {{-- Hidden Form untuk Search --}}
                            <form action="{{ route('catalog.index') }}" method="GET" id="search-form" class="d-none">
                                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                                @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
                                @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif
                                <input type="text" name="q" id="search-q" value="{{ request('q', '') }}">
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Sorting & Result Count --}}
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                    <div class="sort-wrapper d-flex align-items-center gap-2">
                        <label class="text-secondary small mb-0">Urutkan:</label>
                        <form method="GET" class="d-flex">
                            @foreach(request()->except('sort') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()"
                                style="width: auto; background: rgba(30, 41, 59, 0.8); border: 1px solid #334155; color: white; min-width: 150px;">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama: A-Z</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama: Z-A</option>
                            </select>
                        </form>
                    </div>
                    
                    <div class="text-secondary small">
                        @if(request('category'))
                            @php
                                $genreCounts = ['romance' => 17, 'fantasi' => 15, 'drama' => 8, 'inspiratif' => 6, 'horor' => 5, 'fiksi-remaja' => 5, 'politik' => 2, 'agama' => 4];
                                $totalCount = $genreCounts[request('category')] ?? $products->total();
                            @endphp
                            Menampilkan {{ $products->count() }} dari {{ $totalCount }} buku
                        @else
                            Menampilkan {{ $products->count() }} dari {{ $products->total() }} buku
                        @endif
                    </div>
                </div>

                {{-- Active Filters --}}
                @if(request()->hasAny(['category', 'min_price', 'max_price', 'q']))
                    <div class="active-filters mb-3 d-flex flex-wrap gap-2 align-items-center">
                        <span class="text-secondary small">Filter:</span>
                        @if(request('category'))
                            @php $genreName = collect(['romance' => 'Romance', 'fantasi' => 'Fantasi', 'drama' => 'Drama', 'inspiratif' => 'Inspiratif', 'horor' => 'Horor', 'fiksi-remaja' => 'Fiksi Remaja', 'politik' => 'Politik', 'agama' => 'Agama'])->get(request('category'), request('category')); @endphp
                            <span class="badge bg-primary d-flex align-items-center">
                                {{ $genreName }}
                                <a href="{{ route('catalog.index', array_diff_key(request()->all(), ['category' => ''])) }}" class="text-white ms-1 text-decoration-none">&times;</a>
                            </span>
                        @endif
                        @if(request('min_price') || request('max_price'))
                            <span class="badge bg-info text-dark d-flex align-items-center">
                                Rp {{ number_format(request('min_price', 0), 0, ',', '.') }} - Rp {{ number_format(request('max_price', 0), 0, ',', '.') }}
                                <a href="{{ route('catalog.index', array_diff_key(request()->all(), ['min_price' => '', 'max_price' => ''])) }}" class="text-dark ms-1 text-decoration-none">&times;</a>
                            </span>
                        @endif
                        @if(request('q'))
                            <span class="badge bg-warning text-dark d-flex align-items-center">
                                "{{ request('q') }}"
                                <a href="{{ route('catalog.index', array_diff_key(request()->all(), ['q' => ''])) }}" class="text-dark ms-1 text-decoration-none">&times;</a>
                            </span>
                        @endif
                    </div>
                @endif

                {{-- Products Grid --}}
                @if($products->count() > 0)
                    <div class="products-grid row g-3">
                        @foreach($products as $product)
                            <div class="col-6 col-sm-4 col-md-3 col-lg-3">
                                @include('partials.product-card', ['product' => $product])
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="pagination-wrapper mt-4">
                        {{ $products->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="empty-state text-center py-5">
                        <div class="empty-icon mb-3">
                            <i class="bi bi-book text-secondary" style="font-size: 4rem; opacity: 0.3;"></i>
                        </div>
                        <h5 class="text-white mb-2">Buku tidak ditemukan</h5>
                        <p class="text-secondary mb-3">Coba ubah filter atau kata kunci pencarian.</p>
                        <a href="{{ route('catalog.index') }}" class="btn btn-custom">
                            <i class="bi bi-arrow-counterclockwise me-2"></i>Lihat Semua
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- JavaScript untuk Live Search & Filter --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ==================== LIVE SEARCH AUTOCOMPLETE ====================
    const searchInput = document.getElementById('live-search-input');
    const searchSuggestions = document.getElementById('search-suggestions');
    const searchForm = document.getElementById('search-form');
    const searchQ = document.getElementById('search-q');
    let searchTimeout = null;

    if (searchInput && searchSuggestions) {
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            clearTimeout(searchTimeout);
            
            if (query.length < 1) {
                searchSuggestions.style.display = 'none';
                searchQ.value = '';
                return;
            }

            searchTimeout = setTimeout(function() {
                fetchSearchSuggestions(query);
            }, 300);
        });

        searchInput.addEventListener('focus', function() {
            if (this.value.trim().length >= 1) {
                searchSuggestions.style.display = 'block';
            }
        });

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
                searchSuggestions.style.display = 'none';
            }
        });

        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchQ.value = this.value;
                searchForm.submit();
            }
        });
    }

    function fetchSearchSuggestions(query) {
        fetch(`/catalog/search/suggestions?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if (data.products && data.products.length > 0) {
                    renderSuggestions(data.products);
                } else {
                    searchSuggestions.innerHTML = '<div class="p-2 text-secondary small text-center">Tidak ada buku ditemukan</div>';
                    searchSuggestions.style.display = 'block';
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function renderSuggestions(products) {
        let html = '<div class="search-suggestions-list shadow" style="background: rgba(30, 41, 59, 0.98); border: 1px solid #334155; border-radius: 0.5rem;">';
        
        products.forEach(product => {
            const imageUrl = product.image_url || '/images/image-removebg-preview.png';
            html += `
                <a href="${product.url}" class="search-suggestion-item d-flex align-items-center p-2 text-decoration-none">
                    <img src="${imageUrl}" alt="${product.name}" style="width: 40px; height: 50px; object-fit: cover; border-radius: 4px; margin-right: 12px;">
                    <div class="suggestion-info flex-grow-1">
                        <div class="text-white small fw-bold">${product.name}</div>
                        <div class="text-secondary small">${product.price}</div>
                    </div>
                </a>
            `;
        });
        
        html += '</div>';
        searchSuggestions.innerHTML = html;
        searchSuggestions.style.display = 'block';
    }

    // ==================== GENRE SEARCH FILTER (Desktop) ====================
    const genreSearch = document.getElementById('genre-search');
    const genreItems = document.querySelectorAll('.genre-item');
    
    if (genreSearch) {
        genreSearch.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            genreItems.forEach(item => {
                const name = item.dataset.name || '';
                item.style.display = name.includes(query) ? 'flex' : 'none';
            });
        });
    }

    // ==================== AUTO SUBMIT PADA RADIO CHANGE ====================
    const radioButtons = document.querySelectorAll('input[type="radio"][name="category"]');
    radioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('filter-form').submit();
        });
    });
});
</script>

<style>
    /* ========== BASE STYLES ========== */
    .catalog-page {
        min-height: 70vh;
    }
    
    .form-check-input {
        background-color: #1e293b;
        border-color: #3b82f6;
    }
    
    .form-check-input:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }
    
    .form-control,
    .form-select {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid #334155;
        color: white;
    }
    
    .form-control::placeholder {
        color: #64748b;
    }
    
    .form-control:focus,
    .form-select:focus {
        background: rgba(15, 23, 42, 0.8);
        border-color: #3b82f6;
        color: white;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
    }
    
    .form-select option {
        background: #1e293b;
        color: white;
    }
    
    .btn-custom {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-custom:hover {
        background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
        color: white;
    }
    
    .btn-outline-secondary {
        color: #94a3b8;
        border-color: #334155;
        background: transparent;
    }
    
    .btn-outline-secondary:hover {
        background: #334155;
        color: white;
        border-color: #475569;
    }
    
    /* ========== SEARCH SUGGESTIONS ========== */
    .search-suggestions-list {
        max-height: 300px;
        overflow-y: auto;
        z-index: 1000;
    }
    
    .search-suggestion-item:hover {
        background: rgba(59, 130, 246, 0.2);
    }
    
    .search-suggestions::-webkit-scrollbar {
        width: 6px;
    }
    
    .search-suggestions::-webkit-scrollbar-track {
        background: rgba(30, 41, 59, 0.5);
    }
    
    .search-suggestions::-webkit-scrollbar-thumb {
        background: #3b82f6;
        border-radius: 3px;
    }
    
    /* ========== PAGINATION ========== */
    .pagination {
        --bs-pagination-color: #60a5fa;
        --bs-pagination-bg: rgba(30, 41, 59, 0.8);
        --bs-pagination-border-color: #334155;
        --bs-pagination-hover-color: #93c5fd;
        --bs-pagination-hover-bg: #334155;
        --bs-pagination-hover-border-color: #3b82f6;
        --bs-pagination-active-bg: #3b82f6;
        --bs-pagination-active-border-color: #3b82f6;
    }
    
    .page-link {
        border-radius: 0.5rem;
        margin: 0 2px;
        border: 1px solid #334155;
    }
    
    .page-item.disabled .page-link {
        background: rgba(30, 41, 59, 0.6);
        border-color: #334155;
        color: #64748b;
    }
    
    /* ========== RESPONSIVE STYLES ========== */
    
    /* Tablet (576px - 991px) */
    @media (min-width: 576px) and (max-width: 991.98px) {
        .products-grid .col-sm-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
    }
    
    /* Mobile (< 576px) */
    @media (max-width: 575.98px) {
        .catalog-page {
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
        }
        
        .products-grid .col-6 {
            flex: 0 0 50%;
            max-width: 50%;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        
        .products-grid {
            margin-left: -0.5rem;
            margin-right: -0.5rem;
        }
        
        .products-grid .col-6 > div {
            margin-bottom: 0.5rem;
        }
        
        .catalog-header h4 {
            font-size: 1.25rem;
        }
        
        .sort-wrapper {
            width: 100%;
            justify-content: space-between;
        }
        
        .sort-wrapper select {
            min-width: 120px !important;
        }
        
        .active-filters {
            font-size: 0.75rem;
        }
        
        .active-filters .badge {
            font-size: 0.7rem;
            padding: 0.35rem 0.5rem;
        }
        
        .pagination-wrapper {
            margin-top: 1.5rem !important;
        }
        
        .pagination .page-link {
            padding: 0.4rem 0.6rem;
            font-size: 0.8rem;
        }
        
        .empty-icon i {
            font-size: 3rem !important;
        }
        
        .empty-state h5 {
            font-size: 1rem;
        }
    }
    
    /* Very Small Mobile (< 360px) */
    @media (max-width: 359.98px) {
        .products-grid .col-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
        
        .sort-wrapper select {
            min-width: 100px !important;
            font-size: 0.8rem;
        }
    }
</style>
@endsection

