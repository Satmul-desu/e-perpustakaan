@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    {{-- Hero Section --}}
    <section class="hero-section py-5">
        <!-- Video Background with Frame -->
        <div class="hero-video-frame">
            <video class="hero-video" autoplay muted loop playsinline>
                <source src="{{ asset('vidios.mp4') }}" type="video/mp4">
            </video>
        </div>
       
        <div class="hero-overlay" style="background: rgba(0, 0, 0, 0.6);"></div>
        
        <div class="container position-relative" style="padding-top: 80px;">
            <div class="row justify-content-center text-center">
                <div class="col-lg-10 col-md-12">
                    <h1 class="hero-title mb-4">
                        <span class="text-white">Selamat Datang di</span><br>
                        <span class="text-primary">Perpustakaan Buku</span><br>
                        <span class="text-white">Online</span>
                    </h1>
                    <p class="hero-subtitle mb-4">
                        Temukan dan pinjam buku favorit Anda dengan mudah. 
                        Nikmati pengalaman membaca tanpa batas!
                    </p>
                    <div class="trust-badges mt-4 d-flex flex-wrap justify-content-center gap-4">
                        <div class="trust-badge">
                            <i class="bi bi-book text-success"></i>
                            <span>Koleksi Lengkap</span>
                        </div>
                        <div class="trust-badge">
                            <i class="bi bi-clock-history text-info"></i>
                            <span>Peminjaman Cepat</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-12 mt-4">
                    <img src="{{ asset('images/logo-removebg-preview.png') }}"
                         alt="Perpustakaan"
                         class="hero-image img-fluid">
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
    </section>

    {{-- Produk Unggulan Section --}}
    <section class="featured-section py-5">
        <div class="container">
            <div class="section-header text-center mb-4">
                <h2 class="section-title text-white mb-2">
                    <i class="bi bi-star-fill me-2 text-warning"></i>
                    Koleksi Unggulan
                </h2>
                <p class="section-subtitle text-secondary">Buku-buku pilihan yang tersedia untuk dipinjam</p>
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
                        <i class="bi bi-grid me-2"></i>Lihat Semua Buku
                    </a>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-secondary" style="font-size: 4rem;"></i>
                    <p class="text-secondary mt-3">Belum ada buku tersedia.</p>
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
                            <i class="bi bi-book me-2 text-primary"></i>
                            Mulai Membaca Hari Ini!
                        </h3>
                        <p class="text-secondary mb-0">Pinjam buku favorit Anda dengan mudah dan nyaman. Bergabung sekarang!</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('catalog.index') }}" class="btn btn-hero-primary btn-lg">
                            <i class="bi bi-search me-2"></i>Jelajahi Koleksi
                        </a>
                    </div>
            </div>
    </section>

    <style>
        /* ========== HERO SECTION ========== */
        .hero-section {
            position: relative;
            overflow: hidden;
        }
        
        .hero-video {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            transform: translate(-50%, -50%);
            z-index: 0;
            object-fit: cover;
        }
        
        /* Video Frame/Bingkai */
        .hero-video-frame {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
        }
        
        .hero-video-frame::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            z-index: 1;
            pointer-events: none;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.3), inset 0 0 30px rgba(0, 0, 0, 0.2);
        }
        
        /* Responsive Video Frame */
        @media (max-width: 575.98px) {
            .hero-video-frame::before {
                top: 10px;
                left: 10px;
                right: 10px;
                bottom: 10px;
                border-width: 2px;
                border-radius: 12px;
            }
        }
        
        @media (max-width: 359.98px) {
            .hero-video-frame::before {
                top: 8px;
                left: 8px;
                right: 8px;
                bottom: 8px;
                border-width: 2px;
                border-radius: 10px;
            }
        }
        
        .hero-video-frame::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 30%, transparent 70%, rgba(255,255,255,0.05) 100%);
            z-index: 2;
            pointer-events: none;
        }
        
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 58, 138, 0.85) 50%, rgba(15, 23, 42, 0.9) 100%);
            z-index: 1;
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
            z-index: 2;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.8), 0 0 40px rgba(59, 130, 246, 0.6), 3px 3px 10px rgba(0, 0, 0, 0.8);
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            opacity: 1;
            line-height: 1.6;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8);
        }
        
        .hero-image {
            max-height: 600px;
            animation: float 3s ease-in-out infinite;
            filter: drop-shadow(0 0 30px rgba(255, 255, 255, 0.5)) drop-shadow(0 8px 25px rgba(0, 0, 0, 0.8));
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
        
        .trust-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .trust-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            color: #ffffff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
            background: rgba(255, 255, 255, 0.15);
            padding: 8px 16px;
            border-radius: 25px;
            backdrop-filter: blur(5px);
        }
        
        .trust-badge i {
            font-size: 1.3rem;
            filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.5));
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
                font-size: 2.5rem;
            }
            
            .hero-image {
                max-height: 500px;
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
                padding: 3rem 0 !important;
            }
            
            .hero-title {
                font-size: 2rem;
                text-align: center;
            }
            
            .hero-subtitle {
                font-size: 1rem;
                text-align: center;
            }
            
            .hero-image {
                max-height: 400px;
                margin-bottom: 1.5rem;
            }
            
            .trust-badges {
                justify-content: center;
            }
            
            .trust-badge {
                font-size: 0.85rem;
                padding: 6px 12px;
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
            
            .slider-btn {
                width: 36px;
                height: 36px;
            }
            
            .slider-btn i {
                font-size: 0.9rem;
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
