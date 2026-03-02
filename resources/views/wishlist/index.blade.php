@extends('layouts.app')

@section('title', 'Wishlist Saya')

@section('content')
<div class="wishlist-page py-4">
    <div class="container">
        {{-- Page Header --}}
        <div class="wishlist-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <h1 class="page-title text-white mb-1">
                        <i class="bi bi-heart-fill me-2" style="color: #f472b6;"></i>
                        Wishlist Saya
                    </h1>
                    <p class="page-subtitle text-secondary mb-0">
                        @if($products->count())
                            {{ $products->count() }} buku tersimpan di wishlist Anda
                        @else
                            Simpan buku yang Anda sukai di sini
                        @endif
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    @if($products->count())
                        <a href="{{ route('catalog.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Lagi
                        </a>
                    @endif
                </div>
            </div>
        </div>

        @if($products->count())
            {{-- Wishlist Grid --}}
            <div class="row g-3 mb-4">
                @foreach($products as $wishlist)
                    <div class="col-6 col-md-4 col-lg-3">
                        @include('partials.product-card', [
                            'product' => $wishlist->product
                        ])
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="pagination-wrapper">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>

            {{-- Action Buttons --}}
            <div class="action-buttons mt-4 text-center">
                <a href="{{ route('catalog.index') }}" class="btn btn-custom">
                    <i class="bi bi-bag me-2"></i>Lanjut Belanja
                </a>
            </div>
        @else
            {{-- Empty State --}}
            <div class="empty-state-card">
                <div class="empty-icon-wrapper mb-4">
                    <div class="empty-icon-bg">
                        <i class="bi bi-heart"></i>
                    </div>
                </div>
                <h3 class="empty-title text-white mb-2">Wishlist Kosong</h3>
                <p class="empty-text text-secondary mb-4">
                    Anda belum menyimpan buku apapun ke wishlist.<br>
                    Jelajahi katalog dan temukan buku favoritmu!
                </p>
                <a href="{{ route('catalog.index') }}" class="btn btn-custom btn-lg">
                    <i class="bi bi-search me-2"></i>Jelajahi Katalog
                </a>
            </div>
        @endif
    </div>
</div>

<style>
    /* ========== PAGE HEADER ========== */
    .wishlist-page {
        min-height: 60vh;
    }
    
    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
    }
    
    .page-subtitle {
        font-size: 0.95rem;
    }
    
    .btn-custom {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .btn-custom:hover {
        background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
    }
    
    .btn-outline-primary {
        color: #60a5fa;
        border-color: #3b82f6;
        background: transparent;
        padding: 0.5rem 1rem;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .btn-outline-primary:hover {
        background: rgba(59, 130, 246, 0.2);
        border-color: #60a5fa;
        color: #60a5fa;
    }
    
    /* ========== EMPTY STATE ========== */
    .empty-state-card {
        background: rgba(30, 41, 59, 0.8);
        border: 1px solid #334155;
        border-radius: 24px;
        padding: 4rem 2rem;
        text-align: center;
        max-width: 500px;
        margin: 2rem auto;
    }
    
    .empty-icon-wrapper {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto;
    }
    
    .empty-icon-bg {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, rgba(244, 114, 182, 0.2), rgba(236, 72, 153, 0.1));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    
    .empty-icon-bg i {
        font-size: 3rem;
        color: #f472b6;
    }
    
    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
    }
    
    .empty-text {
        font-size: 0.95rem;
        line-height: 1.6;
    }
    
    .btn-lg {
        padding: 0.85rem 2rem;
        font-size: 1rem;
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
        padding: 0.5rem 0.75rem;
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
        .page-title {
            font-size: 1.5rem;
        }
        
        .empty-state-card {
            padding: 3rem 1.5rem;
        }
        
        .empty-icon-bg {
            width: 80px;
            height: 80px;
        }
        
        .empty-icon-bg i {
            font-size: 2.5rem;
        }
    }
    
    /* Mobile (< 576px) */
    @media (max-width: 575.98px) {
        .wishlist-page {
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
        }
        
        .page-title {
            font-size: 1.35rem;
        }
        
        .page-subtitle {
            font-size: 0.85rem;
        }
        
        .wishlist-header .row {
            text-align: center;
        }
        
        .col-md-4.text-md-end {
            text-align: center !important;
        }
        
        .wishlist-header .btn {
            margin-top: 0.5rem;
        }
        
        .row.g-3 .col-6 {
            flex: 0 0 50%;
            max-width: 50%;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        
        .row.g-3 {
            margin-left: -0.5rem;
            margin-right: -0.5rem;
        }
        
        .row.g-3 > div {
            margin-bottom: 0.5rem;
        }
        
        .empty-state-card {
            padding: 2.5rem 1.5rem;
            border-radius: 16px;
        }
        
        .empty-icon-wrapper {
            width: 80px;
            height: 80px;
        }
        
        .empty-icon-bg {
            width: 80px;
            height: 80px;
        }
        
        .empty-icon-bg i {
            font-size: 2rem;
        }
        
        .empty-title {
            font-size: 1.25rem;
        }
        
        .empty-text {
            font-size: 0.85rem;
        }
        
        .pagination-wrapper {
            margin-top: 1.5rem !important;
        }
        
        .pagination .page-link {
            padding: 0.4rem 0.6rem;
            font-size: 0.8rem;
        }
        
        .action-buttons .btn {
            width: 100%;
        }
    }
    
    /* Very Small Mobile (< 360px) */
    @media (max-width: 359.98px) {
        .page-title {
            font-size: 1.2rem;
        }
        
        .empty-state-card {
            padding: 2rem 1rem;
        }
    }
</style>
@endsection

