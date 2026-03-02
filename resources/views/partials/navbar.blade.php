{{-- ================================================
     FILE: resources/views/partials/navbar.blade.php
     FUNGSI: Navigation bar - Modern & Responsive
     ================================================ --}}

<nav class="navbar navbar-expand-lg navbar-dark sticky-top modern-navbar">
    <div class="container">
        {{-- Logo & Brand --}}
        <a class="navbar-brand d-flex align-items-center fw-bold" href="{{ route('home') }}">
            <img src="{{ asset('images/logo-removebg-preview.png') }}" 
                 alt="Logo" 
                 class="brand-logo me-2">
            <span class="brand-text">
                <span class="text-white">Perpustakaan</span><span class="text-primary">Buku</span>
            </span>
        </a>

        {{-- Desktop Search (Hidden on Mobile) --}}
        <form class="d-none d-lg-flex nav-search mx-3" style="max-width: 350px; width: 100%;"
              action="{{ route('catalog.index') }}" method="GET">
            <div class="input-group search-input-group">
                <input type="text" name="q"
                       class="form-control border-secondary"
                       placeholder="Cari buku..."
                       value="{{ request('q') }}">
                <button class="btn btn-search" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>

        {{-- Right Menu --}}
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto align-items-center nav-menu">
                {{-- Katalog (Desktop) --}}
                <li class="nav-item d-none d-lg-block">
                    <a class="nav-link d-flex align-items-center text-white" href="{{ route('catalog.index') }}">
                        <i class="bi bi-grid-3x3-gap-fill me-1"></i> Katalog
                    </a>
                </li>

                {{-- Flash Sale --}}
                <li class="nav-item">
                    <a class="nav-link nav-flashsale d-flex align-items-center" href="{{ route('flash-sale') }}">
                        <i class="bi bi-lightning-fill me-1"></i>
                        <span class="d-none d-lg-inline">Flash Sale</span>
                        <span class="d-lg-none">Flash Sale</span>
                    </a>
                </li>

                @auth
                    {{-- Wishlist --}}
                    <li class="nav-item">
                        <a class="nav-link position-relative d-flex align-items-center text-white nav-icon-link" href="{{ route('wishlist.index') }}">
                            <i class="bi bi-heart-fill nav-icon" style="color: #f472b6;"></i>
                            @if(auth()->user()->wishlists()->count() > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger nav-badge">
                                    {{ auth()->user()->wishlists()->count() > 9 ? '9+' : auth()->user()->wishlists()->count() }}
                                </span>
                            @endif
                        </a>
                    </li>

                    {{-- Cart --}}
                    <li class="nav-item">
                        <a class="nav-link position-relative d-flex align-items-center text-white nav-icon-link" href="{{ route('cart.index') }}">
                            <i class="bi bi-cart3 nav-icon" style="color: #60a5fa;"></i>
                            @php
                                $cartCount = auth()->user()->cart?->items()->count() ?? 0;
                            @endphp
                            @if($cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success nav-badge">
                                    {{ $cartCount > 9 ? '9+' : $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>

                    {{-- User Dropdown --}}
                    <li class="nav-item dropdown ms-1 ms-lg-2">
                        <a class="nav-link dropdown-toggle d-flex align-items-center user-dropdown-toggle"
                           href="#" id="userDropdown"
                           data-bs-toggle="dropdown">
                            {!! auth()->user()->avatar_html !!}
                            <span class="d-none d-lg-inline ms-2 user-name">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end modern-dropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person-fill me-2"></i> Profil Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('orders.index') }}">
                                    <i class="bi bi-bag-fill me-2"></i> Pesanan Saya
                                </a>
                            </li>
                            @if(auth()->user()->isAdmin())
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-primary" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i> Admin Panel
                                    </a>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    {{-- Guest Links --}}
                    <li class="nav-item d-lg-none">
                        <a class="nav-link text-white" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                        </a>
                    </li>
                    <li class="nav-item d-lg-none">
                        <a class="nav-link text-white" href="{{ route('register') }}">
                            <i class="bi bi-person-plus me-1"></i> Daftar
                        </a>
                    </li>
                    <li class="nav-item d-none d-lg-block ms-2">
                        <a class="btn btn-light btn-sm nav-auth-btn" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                        </a>
                    </li>
                    <li class="nav-item d-none d-lg-block">
                        <a class="btn btn-primary btn-sm ms-2 nav-auth-btn" href="{{ route('register') }}" 
                           style="background: linear-gradient(135deg, #3b82f6, #2563eb); border: none;">
                            <i class="bi bi-person-plus me-1"></i> Daftar
                        </a>
                    </li>
                @endauth
            </ul>
        </div>

        {{-- Mobile Toggle Button --}}
        <button class="navbar-toggler mobile-toggle-btn" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarMain"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>

    {{-- Mobile Search Bar (Visible only on mobile) --}}
    <div class="mobile-search-container d-lg-none">
        <form action="{{ route('catalog.index') }}" method="GET" class="px-3 pb-3">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Cari buku..." value="{{ request('q') }}">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
    </div>
</nav>

<style>
    /* ========== NAVBAR BASE STYLES ========== */
    .modern-navbar {
        background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        border-bottom: 1px solid #334155;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }
    
    .brand-logo {
        width: 36px;
        height: 36px;
        object-fit: contain;
    }
    
    .brand-text {
        font-size: 1.25rem;
        letter-spacing: -0.5px;
    }
    
    .text-primary {
        color: #60a5fa !important;
    }
    
    /* ========== NAVIGATION ITEMS ========== */
    .nav-link {
        transition: all 0.3s ease;
        padding: 0.5rem 0.75rem !important;
        border-radius: 8px;
        margin: 0 2px;
    }
    
    .nav-link:hover {
        background: rgba(59, 130, 246, 0.15);
        color: #60a5fa !important;
    }
    
    .nav-flashsale {
        color: #fbbf24 !important;
        font-weight: 600;
    }
    
    .nav-flashsale:hover {
        color: #fcd34d !important;
    }
    
    .nav-icon-link {
        padding: 0.5rem !important;
    }
    
    .nav-icon {
        font-size: 1.3rem;
    }
    
    .nav-badge {
        font-size: 0.6rem;
        min-width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* ========== SEARCH STYLES ========== */
    .search-input-group {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .search-input-group .form-control {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 1px solid #334155;
        padding: 0.6rem 1rem;
    }
    
    .search-input-group .form-control::placeholder {
        color: #94a3b8;
    }
    
    .search-input-group .form-control:focus {
        background: rgba(255, 255, 255, 0.15);
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
    }
    
    .btn-search {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border: none;
        padding: 0.6rem 1rem;
    }
    
    .btn-search:hover {
        background: linear-gradient(135deg, #60a5fa, #3b82f6);
        color: white;
    }
    
    /* ========== USER DROPDOWN ========== */
    .user-dropdown-toggle {
        padding: 0.35rem !important;
    }
    
    .user-name {
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .modern-dropdown {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
        padding: 0.5rem;
        min-width: 200px;
    }
    
    .modern-dropdown .dropdown-item {
        color: white;
        padding: 0.6rem 1rem;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    
    .modern-dropdown .dropdown-item:hover {
        background: rgba(59, 130, 246, 0.2);
        color: #60a5fa;
    }
    
    .modern-dropdown .dropdown-item i {
        width: 20px;
        text-align: center;
    }
    
    .dropdown-divider {
        border-color: #334155;
        margin: 0.5rem 0;
    }
    
    /* ========== AUTH BUTTONS ========== */
    .nav-auth-btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .nav-auth-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    /* ========== MOBILE TOGGLE ========== */
    .navbar-toggler {
        border: 1px solid #334155;
        padding: 0.5rem;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.1);
    }
    
    .navbar-toggler:focus {
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
    }
    
    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }
    
    /* ========== MOBILE SEARCH CONTAINER ========== */
    .mobile-search-container {
        border-top: 1px solid #334155;
        padding-top: 0.5rem;
    }
    
    .mobile-search-container .form-control {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid #334155;
        color: white;
        border-radius: 8px 0 0 8px;
    }
    
    .mobile-search-container .form-control::placeholder {
        color: #94a3b8;
    }
    
    .mobile-search-container .btn {
        border-radius: 0 8px 8px 0;
    }
    
    /* ========== DESKTOP STYLES (≥992px) ========== */
    @media (min-width: 992px) {
        .modern-navbar {
            padding: 0.75rem 0;
        }
        
        .nav-menu {
            gap: 0.25rem;
        }
    }
    
    /* ========== TABLET (576px - 991px) ========== */
    @media (min-width: 576px) and (max-width: 991.98px) {
        .brand-logo {
            width: 32px;
            height: 32px;
        }
        
        .brand-text {
            font-size: 1.15rem;
        }
        
        .nav-icon {
            font-size: 1.2rem;
        }
    }
    
    /* ========== MOBILE (< 576px) ========== */
    @media (max-width: 575.98px) {
        .modern-navbar {
            padding: 0.5rem 0;
        }
        
        .brand-logo {
            width: 28px;
            height: 28px;
        }
        
        .brand-text {
            font-size: 1.1rem;
        }
        
        .navbar-collapse {
            background: rgba(15, 23, 42, 0.98);
            margin: 0 -0.75rem;
            padding: 1rem;
            border-radius: 0 0 12px 12px;
            border-top: 1px solid #334155;
        }
        
        .nav-menu {
            padding-top: 0.5rem;
        }
        
        .nav-item {
            margin-bottom: 0.25rem;
        }
        
        .nav-link {
            padding: 0.75rem 1rem !important;
            border-radius: 10px;
        }
        
        .nav-icon-link {
            width: fit-content;
        }
        
        .nav-icon {
            font-size: 1.3rem;
        }
        
        .user-dropdown-toggle {
            padding: 0.5rem !important;
        }
        
        .user-name {
            max-width: 150px;
        }
        
        .modern-dropdown {
            min-width: 100%;
        }
    }
    
    /* ========== VERY SMALL MOBILE (< 360px) ========== */
    @media (max-width: 359.98px) {
        .brand-logo {
            width: 24px;
            height: 24px;
        }
        
        .brand-text {
            font-size: 1rem;
        }
        
        .nav-flashsale span {
            font-size: 0.85rem;
        }
    }
</style>

