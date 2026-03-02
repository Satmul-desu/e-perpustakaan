@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    {{-- Hero Section --}}
    <section class="hero-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 order-2 order-lg-1">
                    <h1 class="hero-title mb-3">
                        <span class="text-white">Selamat Datang Di</span><br>
                        <span class="text-primary">Perpustakaan Buku Online</span><br>
                        <span class="text-white"> Ternyaman</span>
                    </h1>
                    <p class="hero-subtitle mb-4">
                        Temukan berbagai buku berkualitas dengan harga terbaik.
                        menanti Anda!
                    </p>
                    {{-- Trust Badges --}}
                    <div class="trust-badges mt-4 d-flex flex-wrap gap-3">
                        <div class="trust-badge">
                            <i class="bi bi-shield-check text-success"></i>
                            <span>100% Ori</span>
                        </div>
                        <div class="trust-badge">
                            <i class="bi bi-arrow-repeat text-info"></i>
                            <span>fasilitas mewah</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2 text-center mb-4 mb-lg-0">
                    <img src="{{ asset('images/logo-removebg-preview.png') }}"
                         alt="Toko Buku"
                         class="hero-image img-fluid">
                </div>
            </div>
        </div>
    </section>

    {{-- Promo Banner Section --}}
    <section class="promo-section py-4">
        <div class="container">
            <div class="row g-3">
                <div class="col-md-6">
                        <div class="promo-decoration">
                            <i class="bi bi-clock-history"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="promo-card promo-welcome h-100"
                         style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                        <div class="promo-content">
                            <div class="promo-icon mb-2">
                                <i class="bi bi-gift-fill"></i>
                            </div>
                            <h3 class="fw-bold text-white mb-1">Member Baru?</h3>
                            <p class="text-white opacity-90 mb-3">Voucher Rp 50.000 untuk pembelian pertama</p>
                            <a href="{{ route('register') }}" class="btn btn-light btn-sm">
                                Daftar Sekarang <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="promo-decoration">
                            <i class="bi bi-person-plus-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Kategori Section --}}
    <section class="category-section py-5">
        <div class="container">
            <div class="section-header text-center mb-4">
                <h2 class="section-title text-white mb-2">
                    <i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i>
                    Semua Genre
                </h2>
                <p class="section-subtitle text-secondary">Pilih genre buku favoritmu</p>
            </div>
            
            {{-- Category Slider --}}
            <div class="category-slider-wrapper position-relative">
                <button class="slider-btn slider-btn-prev position-absolute top-50 start-0 translate-middle-y" 
                        onclick="scrollSlider('categorySlider', -1)">
                    <i class="bi bi-chevron-left"></i>
                </button>
                
                <div class="category-slider d-flex gap-3 overflow-x-auto pb-3" 
                     id="categorySlider">
                    
                    @php
                        $genres = [
                            ['name' => 'Romance', 'slug' => 'romance', 'icon' => '💕', 'count' => 12],
                            ['name' => 'Drama', 'slug' => 'drama', 'icon' => '🎭', 'count' => 8],
                            ['name' => 'Fiksi Remaja', 'slug' => 'fiksi-remaja', 'icon' => '📖', 'count' => 5],
                            ['name' => 'Fantasi', 'slug' => 'fantasi', 'icon' => '✨', 'count' => 10],
                            ['name' => 'Horor', 'slug' => 'horor', 'icon' => '👻', 'count' => 3],
                            ['name' => 'Politik', 'slug' => 'politik', 'icon' => '⚖️', 'count' => 2],
                            ['name' => 'Agama', 'slug' => 'agama', 'icon' => '🕊️', 'count' => 4],
                            ['name' => 'Inspiratif', 'slug' => 'inspiratif', 'icon' => '🌟', 'count' => 6],
                        ];
                    @endphp
                    
                    @foreach($genres as $genre)
                        <div class="category-slide flex-shrink-0">
                            <a href="{{ route('catalog.index', ['category' => $genre['slug']]) }}"
                               class="text-decoration-none d-block h-100">
                                <div class="category-card h-100">
                                    <div class="category-icon mb-2">{{ $genre['icon'] }}</div>
                                    <h6 class="category-name text-white mb-1">{{ $genre['name'] }}</h6>
                                    <small class="category-count text-secondary">{{ $genre['count'] }} Buku</small>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                
                <button class="slider-btn slider-btn-next position-absolute top-50 end-0 translate-middle-y" 
                        onclick="scrollSlider('categorySlider', 1)">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>

    {{-- Coming Soon Section --}}
    <section class="coming-soon-section py-5">
        <div class="container">
            <div class="section-header text-center mb-4">
                <h2 class="section-title text-white mb-2">
                    <i class="bi bi-clock-history me-2 text-warning"></i>
                    Coming Soon
                </h2>
                <p class="section-subtitle text-secondary">Buku-buku yang akan segera hadir</p>
            </div>
            
            <div class="coming-soon-grid">
                @php
                    $comingSoonBooks = [
                        ['id' => 101, 'name' => 'Nightbooks', 'author' => 'J.A WHITE', 'image' => 'book1.jpeg', 'price' => 45000, 'category' => 'Horor'],
                        ['id' => 102, 'name' => 'Twisted Love', 'author' => 'ANA HUANG', 'image' => 'book2.jpeg', 'price' => 55000, 'category' => 'Romance'],
                        ['id' => 103, 'name' => 'They Both Die...', 'author' => 'ADAM SILVERA', 'image' => 'book3.jpeg', 'price' => 60000, 'category' => 'Fiksi'],
                        ['id' => 104, 'name' => 'Shadow Girl', 'author' => 'LIANA LIU', 'image' => 'book4.jpeg', 'price' => 52000, 'category' => 'Romance'],
                        ['id' => 105, 'name' => 'Tentang Kamu', 'author' => 'TERE LIYE', 'image' => 'book5.jpeg', 'price' => 48000, 'category' => 'Romance'],
                    ];
                @endphp
                
                @foreach($comingSoonBooks as $book)
                    <div class="coming-soon-item">
                        @php
                            $mockProduct = new \stdClass();
                            $mockProduct->id = $book['id'];
                            $mockProduct->name = $book['name'];
                            $mockProduct->slug = \Illuminate\Support\Str::slug($book['name']);
                            $mockProduct->price = $book['price'];
                            $mockProduct->discount_price = null;
                            $mockProduct->stock = 0;
                            $mockProduct->has_discount = false;
                            $mockProduct->image_url = asset('images/books/' . $book['image']);
                            $mockProduct->description = 'Buku yang akan segera hadir.';
                            
                            $mockCategory = new \stdClass();
                            $mockCategory->name = $book['category'];
                            $mockProduct->category = $mockCategory;
                            
                            $mockProduct->formatted_price = 'Rp ' . number_format($book['price'], 0, ',', '.');
                        @endphp
                        
                        @include('partials.product-card', [
                            'product' => $mockProduct,
                            'author' => $book['author'],
                            'description' => 'Segera hadir!',
                            'isComingSoon' => true
                        ])
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-4">
                <a href="#" class="btn btn-outline-primary">
                    Lihat Semua Coming Soon <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- Produk Unggulan Section --}}
    <section class="featured-section py-5">
        <div class="container">
            <div class="section-header text-center mb-4">
                <h2 class="section-title text-white mb-2">
                    <i class="bi bi-star-fill me-2 text-warning"></i>
                    Produk Unggulan
                </h2>
                <p class="section-subtitle text-secondary">Koleksi buku terbaik pilihan kami</p>
            </div>
            
            @if($featuredProducts->count() > 0)
                <div class="featured-grid">
                    @foreach($featuredProducts->take(8) as $product)
                        <div class="featured-item">
                            @include('partials.product-card', [
                                'product' => $product,
                                'author' => $product->author ?? 'Penulis Umum',
                                'description' => $product->description ?? 'Buku berkualitas.',
                                'isComingSoon' => false
                            ])
                        </div>
                    @endforeach
                </div>
                
                <div class="text-center mt-4">
                    <a href="{{ route('catalog.index') }}" class="btn btn-hero-primary">
                        <i class="bi bi-grid me-2"></i>Lihat Semua Produk
                    </a>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-secondary" style="font-size: 4rem;"></i>
                    <p class="text-secondary mt-3">Belum ada produk unggulan.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="cta-section py-5">
        <div class="container">
            <div class="cta-card">
                <div class="row align-items-center">
                    <div class="col-lg-8 mb-3 mb-lg-0">
                        <h3 class="text-white fw-bold mb-2">
                            <i class="bi bi-bag-check-fill me-2 text-primary"></i>
                            Siap Memulai Petualangan Membaca?
                        </h3>
                        <p class="text-secondary mb-0">Temukan ribuan buku menarik dengan harga terjangkau. Bergabung sekarang!</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('catalog.index') }}" class="btn btn-hero-primary btn-lg">
                            <i class="bi bi-shop me-2"></i>Jelajahi Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* ========== HERO SECTION ========== */
        .hero-section {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1e40af 100%);
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }
        
        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1.2;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
            opacity: 0.85;
            line-height: 1.6;
        }
        
        .hero-image {
            max-height: 350px;
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        
        .btn-hero-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .btn-hero-primary:hover {
            background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        }
        
        .btn-hero-outline {
            background: transparent;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .btn-hero-outline:hover {
            border-color: #60a5fa;
            color: #60a5fa;
            background: rgba(96, 165, 250, 0.1);
        }
        
        .trust-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .trust-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: #94a3b8;
        }
        
        .trust-badge i {
            font-size: 1.1rem;
        }
        
        /* ========== PROMO SECTION ========== */
        .promo-card {
            position: relative;
            border-radius: 16px;
            padding: 1.5rem;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }
        
        .promo-card:hover {
            transform: translateY(-5px);
        }
        
        .promo-content {
            position: relative;
            z-index: 2;
        }
        
        .promo-icon {
            font-size: 2rem;
        }
        
        .promo-decoration {
            position: absolute;
            bottom: -20px;
            right: -20px;
            font-size: 6rem;
            opacity: 0.15;
            transform: rotate(-15deg);
        }
        
        /* ========== CATEGORY SECTION ========== */
        .category-section {
            background: rgba(15, 23, 42, 0.5);
        }
        
        .section-header {
            margin-bottom: 2rem;
        }
        
        .section-title {
            font-size: 1.75rem;
            font-weight: 700;
        }
        
        .section-subtitle {
            font-size: 0.95rem;
        }
        
        .category-slider-wrapper {
            padding: 0 2.5rem;
        }
        
        .category-slider {
            scroll-behavior: smooth;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
        }
        
        .category-slide {
            scroll-snap-align: start;
            min-width: 140px;
        }
        
        .category-card {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid #334155;
            border-radius: 16px;
            padding: 1.25rem 1rem;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .category-card:hover {
            transform: translateY(-5px);
            border-color: #3b82f6;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.2);
        }
        
        .category-icon {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
        }
        
        .category-name {
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .category-count {
            font-size: 0.75rem;
        }
        
        .slider-btn {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border: none;
            border-radius: 50%;
            width: 44px;
            height: 44px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }
        
        .slider-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5);
        }
        
        /* ========== COMING SOON SECTION ========== */
        .coming-soon-section {
            background: rgba(15, 23, 42, 0.3);
        }
        
        .coming-soon-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1.25rem;
        }
        
        .coming-soon-item {
            transition: transform 0.3s ease;
        }
        
        .coming-soon-item:hover {
            transform: translateY(-5px);
        }
        
        /* ========== FEATURED SECTION ========== */
        .featured-section {
            background: rgba(15, 23, 42, 0.5);
        }
        
        .featured-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
        }
        
        .featured-item {
            transition: transform 0.3s ease;
        }
        
        .featured-item:hover {
            transform: translateY(-5px);
        }
        
        /* ========== CTA SECTION ========== */
        .cta-section {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }
        
        .cta-card {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 20px;
            padding: 2rem;
        }
        
        /* ========== RESPONSIVE STYLES ========== */
        
        /* Tablet (576px - 991px) */
        @media (min-width: 576px) and (max-width: 991.98px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-image {
                max-height: 280px;
            }
            
            .coming-soon-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .featured-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .category-slide {
                min-width: 120px;
            }
            
            .category-icon {
                font-size: 2rem;
            }
        }
        
        /* Mobile (< 576px) */
        @media (max-width: 575.98px) {
            .hero-section {
                padding: 2rem 0 !important;
            }
            
            .hero-title {
                font-size: 1.5rem;
                text-align: center;
            }
            
            .hero-subtitle {
                font-size: 0.95rem;
                text-align: center;
            }
            
            .hero-actions {
                justify-content: center;
            }
            
            .hero-image {
                max-height: 200px;
                margin-bottom: 1.5rem;
            }
            
            .trust-badges {
                justify-content: center;
            }
            
            .trust-badge {
                font-size: 0.75rem;
            }
            
            .promo-card {
                padding: 1.25rem;
                border-radius: 12px;
            }
            
            .promo-icon {
                font-size: 1.5rem;
            }
            
            .promo-decoration {
                font-size: 4rem;
            }
            
            .section-title {
                font-size: 1.35rem;
            }
            
            .category-slider-wrapper {
                padding: 0 2rem;
            }
            
            .category-slide {
                min-width: 110px;
            }
            
            .category-card {
                padding: 1rem 0.75rem;
            }
            
            .category-icon {
                font-size: 1.75rem;
            }
            
            .category-name {
                font-size: 0.8rem;
            }
            
            .category-count {
                font-size: 0.7rem;
            }
            
            .slider-btn {
                width: 36px;
                height: 36px;
            }
            
            .slider-btn i {
                font-size: 0.9rem;
            }
            
            .coming-soon-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
            }
            
            .featured-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
            }
            
            .cta-card {
                padding: 1.5rem;
                text-align: center;
            }
            
            .cta-card h3 {
                font-size: 1.1rem;
            }
            
            .btn-lg {
                padding: 0.6rem 1.25rem;
                font-size: 0.9rem;
            }
        }
        
        /* Very Small Mobile (< 360px) */
        @media (max-width: 359.98px) {
            .hero-title {
                font-size: 1.25rem;
            }
            
            .coming-soon-grid,
            .featured-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
            }
            
            .category-slide {
                min-width: 100px;
            }
        }
    </style>

    <script>
        function scrollSlider(sliderId, direction) {
            const slider = document.getElementById(sliderId);
            const scrollAmount = slider.querySelector('.category-slide')?.offsetWidth + 12 || 160;
            slider.scrollBy({
                left: direction * scrollAmount,
                behavior: 'smooth'
            });
        }
    </script>
@endsection

