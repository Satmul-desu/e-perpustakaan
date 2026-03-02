@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="orders-page py-4">
    <div class="container">
        {{-- Page Header --}}
        <div class="orders-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <h1 class="page-title text-white mb-1">
                        <i class="bi bi-bag-check-fill me-2" style="color: #60a5fa;"></i>
                        Pesanan Saya
                    </h1>
                    <p class="page-subtitle text-secondary mb-0">
                        Pantau status dan riwayat belanja Anda
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-house-door me-1"></i>Beranda
                    </a>
                </div>
            </div>
        </div>

        @if($orders->isEmpty())
            {{-- Empty State --}}
            <div class="empty-state-card">
                <div class="empty-icon-wrapper mb-4">
                    <div class="empty-icon-bg">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
                <h3 class="empty-title text-white mb-2">Belum Ada Pesanan</h3>
                <p class="empty-text text-secondary mb-4">
                    Sepertinya Anda belum melakukan pemesanan apapun.<br>
                    Mulai belanja sekarang dan temukan buku-buku menarik!
                </p>
                <a href="{{ url('/') }}" class="btn btn-custom btn-lg">
                    <i class="bi bi-bag me-2"></i>Mulai Belanja
                </a>
            </div>
        @else
            {{-- Orders List --}}
            <div class="orders-list">
                @foreach($orders as $order)
                    <div class="order-card mb-3">
                        <div class="order-header">
                            <div class="row align-items-center">
                                <div class="col-lg-3 mb-2 mb-lg-0">
                                    <div class="order-info">
                                        <span class="order-label">Order</span>
                                        <span class="order-number">#{{ $order->order_number }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-3 mb-2 mb-lg-0">
                                    <div class="order-info">
                                        <span class="order-label">Tanggal</span>
                                        <span class="order-value">{{ $order->created_at->format('d M Y') }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-3 mb-2 mb-lg-0">
                                    <div class="order-info">
                                        <span class="order-label">Total</span>
                                        <span class="order-value fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-3 text-lg-end">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-warning text-dark',
                                            'processing' => 'bg-info text-white',
                                            'shipped' => 'bg-primary text-white',
                                            'delivered' => 'bg-success text-white',
                                            'cancelled' => 'bg-danger text-white',
                                        ];
                                        $badgeClass = $statusClasses[$order->status] ?? 'bg-secondary text-white';
                                        
                                        $paymentStatusClasses = [
                                            'paid' => 'bg-success text-white',
                                            'unpaid' => 'bg-danger text-white',
                                            'pending' => 'bg-warning text-dark',
                                        ];
                                        $paymentBadgeClass = $paymentStatusClasses[$order->payment_status] ?? 'bg-secondary text-white';
                                    @endphp
                                    <div class="order-badges d-flex gap-2 justify-content-lg-end flex-wrap">
                                        <span class="badge rounded-pill {{ $badgeClass }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                        <span class="badge rounded-pill {{ $paymentBadgeClass }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="order-body">
                            <div class="row align-items-center">
                                <div class="col-md-8 mb-3 mb-md-0">
                                    {{-- Order Items Preview --}}
                                    <div class="order-items-preview">
                                        @if($order->orderItems->count() > 0)
                                            <div class="item-thumb">
                                                <img src="{{ $order->orderItems->first()->product_image ?? asset('images/image-removebg-preview.png') }}" 
                                                     alt="{{ $order->orderItems->first()->product_name }}"
                                                     class="rounded">
                                            </div>
                                            <div class="item-info">
                                                <span class="item-name">{{ $order->orderItems->first()->product_name }}</span>
                                                @if($order->orderItems->count() > 1)
                                                    <span class="item-count text-secondary">
                                                        +{{ $order->orderItems->count() - 1 }} produk lainnya
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-secondary">Tidak ada item</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="col-12">
                                        <span class="text-secondary">Tidak ada item dalam pesanan ini</span>
                                    </div>
                                @endif
                                
                                <div class="col-md-4 text-md-end">
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye me-1"></i>Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($orders->hasPages())
            <div class="pagination-wrapper mt-4">
                <nav>
                    <ul class="pagination justify-content-center mb-0">
                        {{ $orders->links() }}
                    </ul>
                </nav>
            </div>
            @endif
        @endif
    </div>
</div>

<style>
    /* ========== PAGE HEADER ========== */
    .orders-page {
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
    
    /* ========== ORDER CARD ========== */
    .order-card {
        background: rgba(30, 41, 59, 0.9);
        border: 1px solid #334155;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .order-card:hover {
        border-color: #3b82f6;
        box-shadow: 0 10px 30px rgba(59, 130, 246, 0.2);
    }
    
    .order-header {
        background: rgba(15, 23, 42, 0.5);
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #334155;
    }
    
    .order-info {
        display: flex;
        flex-direction: column;
    }
    
    .order-label {
        font-size: 0.75rem;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 0.25rem;
    }
    
    .order-number {
        font-weight: 600;
        color: white;
        font-size: 0.95rem;
    }
    
    .order-value {
        color: #e0e0e0;
        font-size: 0.9rem;
    }
    
    .order-badges .badge {
        font-size: 0.75rem;
        padding: 0.4rem 0.75rem;
    }
    
    .order-body {
        padding: 1rem 1.25rem;
    }
    
    /* ========== ORDER ITEMS PREVIEW ========== */
    .order-items-preview {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .item-thumb {
        width: 60px;
        height: 70px;
        flex-shrink: 0;
    }
    
    .item-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border: 1px solid #334155;
    }
    
    .item-info {
        display: flex;
        flex-direction: column;
    }
    
    .item-name {
        color: white;
        font-weight: 500;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }
    
    .item-count {
        font-size: 0.8rem;
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
        margin: 0 auto 1.5rem;
    }
    
    .empty-icon-bg {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, rgba(96, 165, 250, 0.2), rgba(59, 130, 246, 0.1));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .empty-icon-bg i {
        font-size: 3rem;
        color: #60a5fa;
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
        color: white;
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
        
        .order-header {
            padding: 0.75rem 1rem;
        }
        
        .order-body {
            padding: 0.75rem 1rem;
        }
        
        .item-thumb {
            width: 50px;
            height: 60px;
        }
    }
    
    /* Mobile (< 576px) */
    @media (max-width: 575.98px) {
        .orders-page {
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
        }
        
        .page-title {
            font-size: 1.35rem;
        }
        
        .page-subtitle {
            font-size: 0.85rem;
        }
        
        .orders-header .row {
            text-align: center;
        }
        
        .col-md-4.text-md-end {
            text-align: center !important;
        }
        
        .orders-header .btn {
            margin-top: 0.5rem;
        }
        
        .order-card {
            border-radius: 12px;
        }
        
        .order-header {
            padding: 0.75rem;
        }
        
        .order-info {
            text-align: center;
        }
        
        .order-badges {
            justify-content: center !important;
            margin-top: 0.5rem;
        }
        
        .order-badges .badge {
            font-size: 0.7rem;
            padding: 0.3rem 0.6rem;
        }
        
        .order-body {
            padding: 0.75rem;
        }
        
        .order-items-preview {
            flex-direction: column;
            text-align: center;
            gap: 0.75rem;
        }
        
        .item-thumb {
            width: 50px;
            height: 60px;
        }
        
        .item-info {
            align-items: center;
        }
        
        .order-body .col-md-4 {
            text-align: center !important;
            margin-top: 0.75rem;
        }
        
        .order-body .btn {
            width: 100%;
        }
        
        .empty-state-card {
            padding: 2.5rem 1.5rem;
            border-radius: 16px;
        }
        
        .empty-icon-bg {
            width: 80px;
            height: 80px;
        }
        
        .empty-icon-bg i {
            font-size: 2.5rem;
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
    }
    
    /* Very Small Mobile (< 360px) */
    @media (max-width: 359.98px) {
        .page-title {
            font-size: 1.2rem;
        }
        
        .order-badges {
            gap: 0.25rem !important;
        }
        
        .empty-state-card {
            padding: 2rem 1rem;
        }
    }
</style>
@endsection

