@extends('layouts.app')
@section('title', 'Flash Sale - Diskon Spesial')
@section('content')
    <section class="flash-hero py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="flash-badge mb-3">
                        <span class="badge flash-badge-main px-3 py-2">
                            <i class="bi bi-lightning-fill me-2"></i>FLASH SALE
                        </span>
                    </div>
                    <h1 class="flash-title text-white mb-2">
                        Diskon Spesial!
                    </h1>
                    <p class="flash-subtitle text-white opacity-90 mb-3">
                        Beli buku favoritmu dengan harga terjangkau. Stok terbatas!
                    </p>
                    <div class="countdown-wrapper">
                        <p class="countdown-label text-white small mb-2">
                            <i class="bi bi-clock-history me-1"></i>
                            Promo berakhir dalam:
                        </p>
                        <div class="countdown-timer d-flex justify-content-center gap-2">
                            <div class="timer-box">
                                <div class="timer-value" id="hours">00</div>
                                <div class="timer-label">Jam</div>
                            </div>
                            <div class="timer-separator">:</div>
                            <div class="timer-box">
                                <div class="timer-value" id="minutes">00</div>
                                <div class="timer-label">Menit</div>
                            </div>
                            <div class="timer-separator">:</div>
                            <div class="timer-box">
                                <div class="timer-value" id="seconds">00</div>
                                <div class="timer-label">Detik</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="flash-products py-4">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
                <h2 class="section-title text-white mb-0 d-flex align-items-center">
                    <i class="bi bi-fire me-2 text-danger"></i>
                    Flash Sale Products
                    <span class="badge bg-danger ms-2">{{ $flashSaleProducts->total() }}</span>
                </h2>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-secondary small">Urutkan:</span>
                    <select class="form-select form-select-sm" style="width: auto; background: rgba(30, 41, 59, 0.8); color: white; border: 1px solid #334155;" onchange="window.location.href = this.value">
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ !request('sort') || request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price-low']) }}" {{ request('sort') == 'price-low' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price-high']) }}" {{ request('sort') == 'price-high' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'name']) }}" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
                    </select>
                </div>
            </div>
            @if($flashSaleProducts->count() > 0)
                <div class="row g-3">
                    @foreach($flashSaleProducts as $product)
                        @php
                            $discountPercentage = 0;
                            if ($product->discount_price && $product->discount_price > 0) {
                                $discountPercentage = round((($product->price - $product->discount_price) / $product->price) * 100);
                            }
                            $imageUrl = $product->primaryImage 
                                ? asset('storage/products/' . $product->primaryImage->image_path)
                                : asset('images/image-removebg-preview.png');
                            $author = $product->author ?? 'Penulis Umum';
                            $description = $product->description ?? 'Buku berkualitas dengan harga terjangkau.';
                        @endphp
                        <div class="col-6 col-md-4 col-lg-3">
                            @include('partials.product-card', [
                                'product' => $product,
                                'author' => $author,
                                'description' => $description,
                                'isFlashSale' => true
                            ])
                        </div>
                    @endforeach
                </div>
                @if($flashSaleProducts->hasPages())
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        @if ($flashSaleProducts->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" style="background: rgba(30, 41, 59, 0.8); border: 1px solid #334155; color: white;">
                                    <i class="bi bi-chevron-left"></i>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $flashSaleProducts->previousPageUrl() }}" style="background: rgba(30, 41, 59, 0.8); border: 1px solid #334155; color: white;">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            </li>
                        @endif
                        @foreach ($flashSaleProducts->links()->elements[0] as $page => $url)
                            @if ($page == $flashSaleProducts->currentPage())
                                <li class="page-item active">
                                    <span class="page-link" style="background: #dc2626; border-color: #dc2626;">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}" style="background: rgba(30, 41, 59, 0.8); border: 1px solid #334155; color: white;">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                        @if ($flashSaleProducts->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $flashSaleProducts->nextPageUrl() }}" style="background: rgba(30, 41, 59, 0.8); border: 1px solid #334155; color: white;">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link" style="background: rgba(30, 41, 59, 0.8); border: 1px solid #334155; color: white;">
                                    <i class="bi bi-chevron-right"></i>
                                </span>
                            </li>
                        @endif
                    </ul>
                </nav>
                @endif
            @else
                <div class="empty-state text-center py-5">
                    <div class="empty-icon mb-3">
                        <i class="bi bi-inbox text-secondary" style="font-size: 4rem; opacity: 0.3;"></i>
                    </div>
                    <h5 class="text-white mb-2">Tidak ada produk flash sale</h5>
                    <p class="text-secondary mb-3">Silakan kembali lagi nanti untuk melihat produk flash sale terbaru.</p>
                    <a href="{{ route('home') }}" class="btn btn-custom">
                        <i class="bi bi-house me-2"></i>Kembali ke Beranda
                    </a>
                </div>
            @endif
        </div>
    </section>
    <section class="flash-info py-4">
        <div class="container">
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <div class="info-card text-center h-100">
                        <div class="info-icon mb-2">
                            <i class="bi bi-truck"></i>
                        </div>
                        <h6 class="text-white fw-bold mb-1">Gratis Ongkir</h6>
                        <p class="text-secondary small mb-0">Pembelian Rp 100rb+</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="info-card text-center h-100">
                        <div class="info-icon mb-2">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h6 class="text-white fw-bold mb-1">Garansi Asli</h6>
                        <p class="text-secondary small mb-0">Produk 100% Original</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="info-card text-center h-100">
                        <div class="info-icon mb-2">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <h6 class="text-white fw-bold mb-1">7 Hari Retur</h6>
                        <p class="text-secondary small mb-0">Garansi Pengembalian</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="info-card text-center h-100">
                        <div class="info-icon mb-2">
                            <i class="bi bi-headset"></i>
                        </div>
                        <h6 class="text-white fw-bold mb-1">24/7 Support</h6>
                        <p class="text-secondary small mb-0">Customer Service</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="flash-cta py-4">
        <div class="container">
            <div class="cta-card">
                <div class="row align-items-center">
                    <div class="col-lg-8 mb-3 mb-lg-0">
                        <h4 class="text-white fw-bold mb-1">
                            <i class="bi bi-book me-2 text-primary"></i>
                            Tidak menemukan yang dicari?
                        </h4>
                        <p class="text-secondary mb-0">Jelajahi katalog lengkap kami untuk menemukan lebih banyak buku menarik.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('catalog.index') }}" class="btn btn-custom btn-lg">
                            <i class="bi bi-grid me-2"></i>Lihat Katalog
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <style>
        .flash-hero {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 50%, #7f1d1d 100%);
            position: relative;
            overflow: hidden;
        }
        .flash-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http:
        }
        .flash-badge-main {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: #1e293b;
            font-size: 1rem;
            font-weight: 700;
            border-radius: 50px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .flash-title {
            font-size: 2.5rem;
            font-weight: 700;
        }
        .flash-subtitle {
            font-size: 1.1rem;
        }
        .countdown-wrapper {
            max-width: 400px;
            margin: 0 auto;
        }
        .countdown-label {
            opacity: 0.9;
        }
        .countdown-timer {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
        }
        .timer-box {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            min-width: 70px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .timer-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: white;
            line-height: 1;
        }
        .timer-label {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.8);
            text-transform: uppercase;
            margin-top: 0.25rem;
        }
        .timer-separator {
            font-size: 1.5rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.5);
        }
        .section-title {
            font-size: 1.35rem;
            font-weight: 600;
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
            transform: translateY(-2px);
        }
        .flash-info {
            background: rgba(15, 23, 42, 0.5);
        }
        .info-card {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid #334155;
            border-radius: 16px;
            padding: 1.25rem 1rem;
            transition: all 0.3s ease;
        }
        .info-card:hover {
            transform: translateY(-5px);
            border-color: #3b82f6;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.2);
        }
        .info-icon {
            font-size: 2rem;
            color: #dc2626;
        }
        .flash-cta {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }
        .cta-card {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 20px;
            padding: 1.5rem;
        }
        .pagination {
            --bs-pagination-color: #60a5fa;
            --bs-pagination-bg: rgba(30, 41, 59, 0.8);
            --bs-pagination-border-color: #334155;
            --bs-pagination-hover-color: #93c5fd;
            --bs-pagination-hover-bg: #334155;
            --bs-pagination-hover-border-color: #3b82f6;
            --bs-pagination-active-bg: #dc2626;
            --bs-pagination-active-border-color: #dc2626;
        }
        .page-link {
            border-radius: 0.5rem;
            margin: 0 2px;
            padding: 0.5rem 0.75rem;
            border: 1px solid #334155;
        }
        .page-item.active .page-link {
            background: #dc2626;
            border-color: #dc2626;
        }
        .empty-icon i {
            opacity: 0.3;
        }
        @media (min-width: 576px) and (max-width: 991.98px) {
            .flash-title {
                font-size: 2rem;
            }
            .timer-box {
                min-width: 60px;
                padding: 0.5rem 0.75rem;
            }
            .timer-value {
                font-size: 1.5rem;
            }
        }
        @media (max-width: 575.98px) {
            .flash-hero {
                padding: 2rem 0 !important;
            }
            .flash-badge-main {
                font-size: 0.85rem;
                padding: 0.5rem 1rem !important;
            }
            .flash-title {
                font-size: 1.5rem;
            }
            .flash-subtitle {
                font-size: 0.9rem;
            }
            .countdown-timer {
                gap: 0.25rem;
            }
            .timer-box {
                min-width: 55px;
                padding: 0.5rem 0.6rem;
                border-radius: 10px;
            }
            .timer-value {
                font-size: 1.25rem;
            }
            .timer-label {
                font-size: 0.6rem;
            }
            .timer-separator {
                font-size: 1.1rem;
            }
            .section-title {
                font-size: 1.1rem;
            }
            .section-title .badge {
                font-size: 0.7rem;
                padding: 0.3rem 0.5rem;
            }
            .form-select-sm {
                font-size: 0.8rem;
                padding: 0.35rem 2rem 0.35rem 0.5rem;
            }
            .info-card {
                padding: 1rem 0.75rem;
                border-radius: 12px;
            }
            .info-icon {
                font-size: 1.5rem;
            }
            .info-card h6 {
                font-size: 0.9rem;
            }
            .info-card p {
                font-size: 0.75rem;
            }
            .cta-card {
                padding: 1.25rem;
                text-align: center;
            }
            .cta-card h4 {
                font-size: 1.1rem;
            }
            .btn-lg {
                padding: 0.6rem 1.25rem;
                font-size: 0.9rem;
            }
        }
        @media (max-width: 359.98px) {
            .timer-box {
                min-width: 48px;
                padding: 0.4rem 0.5rem;
            }
            .timer-value {
                font-size: 1.1rem;
            }
            .timer-separator {
                display: none;
            }
        }
    </style>
    <script>
        (function() {
            const endTime = new Date();
            endTime.setHours(endTime.getHours() + 24);
            function updateTimer() {
                const now = new Date().getTime();
                const distance = endTime.getTime() - now;
                if (distance < 0) {
                    document.getElementById('hours').textContent = '00';
                    document.getElementById('minutes').textContent = '00';
                    document.getElementById('seconds').textContent = '00';
                    return;
                }
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
                document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
                document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
            }
            updateTimer();
            setInterval(updateTimer, 1000);
        })();
    </script>
@endsection