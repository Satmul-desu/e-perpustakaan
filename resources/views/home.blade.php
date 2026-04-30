@extends('layouts.app')
@section('title', 'Beranda')
@section('content')
    <section class="hero-section py-5">
        <div class="hero-video-frame" style="border: none !important; box-shadow: none !important;">
            <video class="hero-video" autoplay muted loop playsinline style="opacity: 0.15; filter: none !important;">
                <source src="{{ asset('vidios.mp4') }}" type="video/mp4">
            </video>
        </div>
        <div class="container position-relative" style="padding-top: 80px; z-index: 10;">
            <div class="row justify-content-center text-center">
                <div class="col-lg-12">
                    <img src="{{ asset('images/logo-removebg-preview.png') }}" alt="Perpustakaan"
                        class="hero-image img-fluid" style="max-height: 480px; margin-top: -85px; margin-bottom: -65px; filter: brightness(1.2) drop-shadow(0 0 15px rgba(255,255,255,0.5));">
                </div>
                
                <div class="col-lg-10 col-md-12">
                    <h1 class="hero-title mb-3">
                        <span class="text-hero-main">Selamat Datang di</span><br>
                        <span style="color: #60a5fa !important; text-shadow: none;">Perpustakaan Buku</span><br>
                        <span class="text-hero-main">Online</span>
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
            </div>
        </div>
    </section>

    {{-- Kategori Section --}}
    <section class="category-section py-5">
        <div class="container">
            <div class="section-header text-center mb-4">
                <h2 class="section-title mb-2">
                    <i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i>
                    Semua Genre
                </h2>
                <p class="section-subtitle opacity-75">Pilih genre buku favoritmu</p>
            </div>

            {{-- Category Slider --}}
            <div class="category-slider-wrapper position-relative">
                <button class="slider-btn slider-btn-prev position-absolute top-50 start-0 translate-middle-y"
                    onclick="scrollSlider('categorySlider', -1)">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <div class="category-slider d-flex gap-3 overflow-x-auto pb-3" id="categorySlider">

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

                    @foreach ($genres as $genre)
                        <div class="category-slide flex-shrink-0">
                            <a href="{{ route('catalog.index', ['category' => $genre['slug']]) }}"
                                class="text-decoration-none d-block h-100">
                                <div class="category-card h-100">
                                    <div class="category-icon mb-2">{{ $genre['icon'] }}</div>
                                    <h6 class="category-name mb-1">{{ $genre['name'] }}</h6>
                                    <small class="category-count opacity-75">{{ $genre['count'] }} Buku</small>
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

    {{-- Produk Unggulan Section --}}
    <section class="featured-section py-5">
        <div class="container">
            <div class="section-header text-center mb-4">
                <h2 class="section-title mb-2">
                    <i class="bi bi-star-fill me-2 text-warning"></i>
                    Koleksi Unggulan
                </h2>
                <p class="section-subtitle opacity-75">Buku-buku pilihan yang tersedia untuk dipinjam</p>
            </div>

            @if ($featuredProducts->count() > 0)
                <div class="featured-grid">
                    @foreach ($featuredProducts->take(8) as $product)
                        <div class="featured-item">
                            @include('partials.product-card', [
                                'product' => $product,
                                'author' => $product->author ?? 'Penulis Umum',
                                'description' => $product->description ?? 'Buku berkualitas.',
                                'isComingSoon' => false,
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
                    <i class="bi bi-inbox opacity-50" style="font-size: 4rem;"></i>
                    <p class="opacity-75 mt-3">Belum ada buku tersedia.</p>
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
                        <h3 class="fw-bold mb-2">
                            <i class="bi bi-book me-2 text-primary"></i>
                            Mulai Membaca Hari Ini!
                        </h3>
                        <p class="opacity-75 mb-0">Pinjam buku favorit Anda dengan mudah dan nyaman. Bergabung sekarang!
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('catalog.index') }}" class="btn btn-hero-primary btn-lg">
                            <i class="bi bi-search me-2"></i>Jelajahi Koleksi
                        </a>
                    </div>
                </div>
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
            opacity: 0.75 !important; /* Ditingkatkan agar video lebih kelihatan (transparan tapi jelas) */
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
            border: 2px solid rgba(255, 255, 255, 0.15); /* Lebih tipis dan transparan */
            border-radius: 20px;
            z-index: 1;
            pointer-events: none;
            box-shadow: none; /* Menghapus shadow agar lebih transparan/bersih */
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
            display: none;
        }

        .hero-section::before {
            display: none;
        }

        .hero-title {
            font-size: 3.6rem;
            font-weight: 800;
            line-height: 1.2;
            text-shadow: none !important;
            color: #ffffff;
        }

        .hero-subtitle {
            font-size: 1.45rem;
            font-weight: 500;
            color: #ffffff;
            line-height: 1.6;
            text-shadow: none !important;
        }

        .hero-image {
            max-height: 600px;
            animation: float 3s ease-in-out infinite;
            filter: brightness(1.2) drop-shadow(0 0 40px rgba(255, 255, 255, 0.8));
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
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
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 16px;
            border-radius: 25px;
            backdrop-filter: blur(5px);
        }

        .trust-badge i {
            font-size: 1.3rem;
            filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.5));
        }

        /* ========== LIGHT THEME OVERRIDES ========== */
        [data-bs-theme="light"] .text-hero-main,
        [data-bs-theme="light"] .hero-title,
        [data-bs-theme="light"] .hero-subtitle,
        [data-bs-theme="light"] .trust-badge,
        [data-bs-theme="light"] .section-title,
        [data-bs-theme="light"] .section-subtitle,
        [data-bs-theme="light"] .category-name,
        [data-bs-theme="light"] .category-count,
        [data-bs-theme="light"] .cta-card h3,
        [data-bs-theme="light"] .cta-card p,
        [data-bs-theme="light"] .text-white,
        [data-bs-theme="light"] .text-white-50 {
            color: #0f172a !important;
            text-shadow: none !important;
        }


        [data-bs-theme="light"] .trust-badge {
            background: rgba(15, 23, 42, 0.05);
            border: 1px solid rgba(15, 23, 42, 0.1);
        }

        [data-bs-theme="light"] .hero-video-frame::before {
            border-color: rgba(0, 0, 0, 0.05);
            background: transparent;
        }

        [data-bs-theme="light"] .category-section,
        [data-bs-theme="light"] .featured-section {
            background-color: #ffffff !important;
        }

        [data-bs-theme="light"] .category-card {
            background: #ffffff;
            border-color: #f1f5f9;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        [data-bs-theme="light"] .cta-section {
            background: #f8fafc;
        }

        [data-bs-theme="light"] .cta-card {
            background: #ffffff;
            border-color: #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        [data-bs-theme="light"] .category-section,
        [data-bs-theme="light"] .featured-section {
            background: #ffffff;
        }

        [data-bs-theme="light"] .category-card {
            background: #f8fafc;
            border-color: #e2e8f0;
        }

        [data-bs-theme="light"] .cta-section {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        [data-bs-theme="light"] .cta-card {
            background: rgba(59, 130, 246, 0.05);
            border-color: rgba(59, 130, 246, 0.1);
        }

        [data-bs-theme="light"] .bi-inbox {
            color: #64748b;
        }
        
        .text-hero-main { color: inherit; }

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
            color: #ffffff;
        }

        .section-subtitle {
            font-size: 0.95rem;
            color: #ffffff;
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
