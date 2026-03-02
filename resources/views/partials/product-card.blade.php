@php
    use Illuminate\Support\Str;
    
    // Default values untuk backward compatibility
    $author = $author ?? null;
    $description = $description ?? ($product->description ?? '');
    $imageUrl = $product->image_url ?? asset('images/image-removebg-preview.png');
    $isComingSoon = $isComingSoon ?? ($product->stock == 0);
    $isFlashSale = $isFlashSale ?? false;
@endphp

<div class="product-card-wrapper h-100">
    <div class="card product-card h-100 border-0 shadow-sm" style="background: rgba(30, 41, 59, 0.95); border: 1px solid #334155 !important;">
        
        {{-- Product Image --}}
        <div class="product-image-wrapper position-relative overflow-hidden">
            <a href="{{ route('catalog.show', $product->slug) }}" class="d-block" style="height: 100%;">
                <img
                    src="{{ $imageUrl }}"
                    class="card-img-top"
                    alt="{{ $product->name }}"
                >
            </a>

            {{-- Discount Badge --}}
            @if($product->has_discount)
                <span class="badge position-absolute top-0 start-0 m-2 discount-badge">
                    -{{ $product->discount_percentage }}%
                </span>
            @endif

            {{-- Flash Sale Badge --}}
            @if($isFlashSale)
                <span class="badge position-absolute top-0 start-0 m-2 flash-badge">
                    <i class="bi bi-lightning-fill me-1"></i>FLASH
                </span>
            @endif

            {{-- Wishlist Button --}}
            @auth
                <button onclick="toggleWishlist({{ $product->id }})"
                        class="wishlist-btn-{{ $product->id }} btn btn-light btn-sm rounded-circle position-absolute top-0 end-0 m-2 shadow-sm wishlist-btn"
                        style="z-index: 10;">
                    <i class="bi {{ Auth::check() && Auth::user()->hasInWishlist($product) ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
                </button>
            @endauth

            {{-- Coming Soon / Stock Badge --}}
            @if($isComingSoon)
                <div class="overlay-badge position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                    <div class="text-center">
                        <i class="bi bi-clock-history text-warning"></i>
                        <span class="badge bg-warning text-dark d-block mt-1">Coming Soon</span>
                    </div>
                </div>
            @elseif($product->stock == 0)
                <div class="overlay-badge position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                    <span class="badge bg-danger">Stok Habis</span>
                </div>
            @endif
        </div>

        {{-- Card Body --}}
        <div class="card-body pb-2 px-2">
            {{-- Category --}}
            <div class="product-category mb-1">
                <small class="text-custom text-uppercase">
                    {{ optional($product->category)->name ?? 'Buku' }}
                </small>
            </div>

            {{-- Product Name --}}
            <h6 class="product-title mb-1">
                <a href="{{ route('catalog.show', $product->slug) }}" class="text-decoration-none text-white fw-bold">
                    {{ Str::limit($product->name, 45) }}
                </a>
            </h6>

            {{-- Author --}}
            @if($author)
                <div class="product-author mb-1">
                    <small class="text-secondary">
                        <i class="bi bi-person-fill me-1"></i>
                        {{ Str::limit($author, 25) }}
                    </small>
                </div>
            @endif

            {{-- Price Section --}}
            <div class="price-section mb-2 mt-auto">
                @if($product->has_discount)
                    <small class="text-secondary text-decoration-line-through d-block original-price">
                        {{ $product->formatted_original_price }}
                    </small>
                    <span class="fw-bold discounted-price">
                        {{ $product->formatted_final_price }}
                    </span>
                @elseif(!$isComingSoon)
                    <span class="fw-bold regular-price">
                        {{ $product->formatted_price }}
                    </span>
                @endif
            </div>

            {{-- Stock Info (非 Coming Soon) --}}
            @if(!$isComingSoon && $product->stock > 0)
                <div class="stock-info mb-2">
                    <small class="{{ $product->stock <= 5 ? 'text-warning' : 'text-success' }}">
                        <i class="bi bi-box-seam me-1"></i>
                        Stok: {{ $product->stock }}
                    </small>
                </div>
            @endif
        </div>

        {{-- Card Footer --}}
        <div class="card-footer border-0 pt-0 pb-2 px-2" style="background: transparent;">
            @if(!$isComingSoon)
                <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">

                    <button
                        type="submit"
                        class="btn btn-custom w-100"
                        @if($product->stock == 0) disabled @endif
                    >
                        <i class="bi bi-cart-plus me-1"></i>
                        {{ $product->stock == 0 ? 'Habis' : 'Tambah' }}
                    </button>
                </form>
            @else
                <button class="btn btn-outline-secondary w-100" disabled>
                    <i class="bi bi-bell me-1"></i>
                    Coming Soon
                </button>
            @endif
        </div>
    </div>
</div>

<style>
    /* ========== DESKTOP & LAPTOP (≥992px) ========== */
    .product-card-wrapper {
        height: 100%;
        perspective: 1000px;
    }
    
    .product-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        transform-style: preserve-3d;
    }
    
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(59, 130, 246, 0.2) !important;
        border-color: #3b82f6 !important;
    }
    
    .product-image-wrapper {
        height: 220px;
        position: relative;
    }
    
    .product-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    
    .product-card:hover .product-image-wrapper img {
        transform: scale(1.08);
    }
    
    .discount-badge {
        background: #ef4444;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .flash-badge {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        font-size: 0.65rem;
        font-weight: 700;
    }
    
    .wishlist-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .wishlist-btn i {
        font-size: 1rem;
    }
    
    .wishlist-btn:hover {
        transform: scale(1.1);
        background: #fee2e2;
    }
    
    .overlay-badge {
        background: rgba(0, 0, 0, 0.6);
        z-index: 5;
    }
    
    .overlay-badge i {
        font-size: 2rem;
    }
    
    .product-category {
        min-height: 18px;
    }
    
    .text-custom {
        color: #60a5fa;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
    }
    
    .product-title {
        min-height: 40px;
        line-height: 1.4;
    }
    
    .product-title a {
        font-size: 0.95rem;
        transition: color 0.2s ease;
    }
    
    .product-title a:hover {
        color: #60a5fa !important;
    }
    
    .product-author i {
        font-size: 0.75rem;
        color: #64748b;
    }
    
    .product-author small {
        font-size: 0.75rem;
    }
    
    .price-section {
        min-height: 28px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .original-price {
        font-size: 0.75rem;
    }
    
    .discounted-price,
    .regular-price {
        font-size: 1.1rem;
        color: #60a5fa;
    }
    
    .stock-info i {
        font-size: 0.75rem;
    }
    
    .stock-info small {
        font-size: 0.7rem;
    }
    
    .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 0.75rem;
    }
    
    .card-footer {
        flex-shrink: 0;
        padding: 0 0.75rem 0.75rem;
    }
    
    .btn-custom {
        font-size: 0.8rem;
        padding: 0.5rem 0.75rem;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: none;
        border-radius: 8px;
    }
    
    .btn-custom:hover:not(:disabled) {
        background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
    }
    
    .btn-custom:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    /* ========== TABLET (576px - 991px) ========== */
    @media (min-width: 576px) and (max-width: 991.98px) {
        .product-image-wrapper {
            height: 180px;
        }
        
        .discounted-price,
        .regular-price {
            font-size: 1rem;
        }
        
        .product-title a {
            font-size: 0.9rem;
        }
        
        .btn-custom {
            font-size: 0.75rem;
            padding: 0.45rem 0.65rem;
        }
        
        .wishlist-btn {
            width: 28px;
            height: 28px;
        }
        
        .wishlist-btn i {
            font-size: 0.9rem;
        }
    }
    
    /* ========== MOBILE (< 576px) ========== */
    @media (max-width: 575.98px) {
        .product-card-wrapper {
            perspective: none;
        }
        
        .product-card {
            transform: none !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3) !important;
            border-radius: 12px !important;
        }
        
        .product-card:hover {
            transform: none !important;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25) !important;
            border-color: #3b82f6 !important;
        }
        
        .product-image-wrapper {
            height: 150px !important;
            border-radius: 12px 12px 0 0;
        }
        
        .product-image-wrapper img {
            border-radius: 12px 12px 0 0;
            transform: none !important;
        }
        
        .product-card:hover .product-image-wrapper img {
            transform: none !important;
        }
        
        .discount-badge {
            font-size: 0.65rem;
            padding: 0.25rem 0.5rem;
        }
        
        .flash-badge {
            font-size: 0.6rem;
            padding: 0.2rem 0.4rem;
        }
        
        .wishlist-btn {
            width: 26px;
            height: 26px;
            m-1 !important;
            right: 8px !important;
            top: 8px !important;
        }
        
        .wishlist-btn i {
            font-size: 0.85rem;
        }
        
        .overlay-badge i {
            font-size: 1.5rem;
        }
        
        .overlay-badge .badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }
        
        .card-body {
            padding: 0.6rem !important;
        }
        
        .product-category {
            min-height: auto;
            margin-bottom: 0.25rem !important;
        }
        
        .text-custom {
            font-size: 0.6rem;
        }
        
        .product-title {
            min-height: auto;
            margin-bottom: 0.25rem !important;
        }
        
        .product-title a {
            font-size: 0.8rem;
            line-height: 1.3;
        }
        
        .product-author {
            display: none !important;
        }
        
        .price-section {
            min-height: auto;
            margin-bottom: 0.5rem !important;
            flex-direction: row;
            align-items: baseline;
            gap: 0.5rem;
        }
        
        .original-price {
            font-size: 0.7rem;
        }
        
        .discounted-price,
        .regular-price {
            font-size: 0.9rem;
        }
        
        .stock-info {
            margin-bottom: 0.5rem !important;
        }
        
        .stock-info small {
            font-size: 0.65rem;
        }
        
        .stock-info i {
            font-size: 0.7rem;
        }
        
        .card-footer {
            padding: 0 0.6rem 0.6rem !important;
        }
        
        .btn-custom {
            font-size: 0.75rem;
            padding: 0.4rem 0.5rem;
            border-radius: 6px;
        }
        
        .btn-custom i {
            margin-right: 0.25rem !important;
        }
    }
    
    /* ========== VERY SMALL MOBILE (< 360px) ========== */
    @media (max-width: 359.98px) {
        .product-image-wrapper {
            height: 130px !important;
        }
        
        .product-title a {
            font-size: 0.75rem;
        }
        
        .discounted-price,
        .regular-price {
            font-size: 0.85rem;
        }
        
        .btn-custom {
            font-size: 0.7rem;
            padding: 0.35rem 0.4rem;
        }
    }
</style>

