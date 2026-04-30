<nav class="navbar navbar-expand-lg navbar-dark sticky-top modern-navbar">
    <div class="container-fluid px-4 px-lg-5 position-relative">
        
        {{-- Mobile Logo & Brand (Hidden on Desktop) --}}
        <a class="navbar-brand d-flex align-items-center fw-bold d-lg-none" href="{{ route('home') }}">
            <img src="{{ asset('images/logo-removebg-preview.png') }}" alt="Logo" class="brand-logo" style="width: 100px; height: 100px; object-fit: contain; margin-top: -25px; margin-bottom: -25px; margin-right: -15px;">
            <span class="brand-text" style="font-size: 1.3rem;">
                <span class="text-white">E-</span><span class="text-primary">Perpus</span>
            </span>
        </a>

        {{-- Desktop Center Section (Logo + Search Group) --}}
        <div class="d-none d-lg-flex align-items-center mx-auto" style="gap: 2.5rem;">
            <a class="navbar-brand d-flex align-items-center fw-bold m-0" href="{{ route('home') }}" style="position: relative; z-index: 1040;">
                <img src="{{ asset('images/logo-removebg-preview.png') }}" alt="Logo" class="brand-logo" style="width: 200px; height: 200px; object-fit: contain; margin-top: -90px; margin-bottom: -90px; margin-right: -35px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3));">
                <span class="brand-text" style="font-size: 1.8rem; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
                    <span class="text-white">E-</span><span class="text-primary">Perpustakaan</span>
                </span>
            </a>

            <form class="nav-search" style="width: 450px;" action="{{ route('catalog.index') }}" method="GET">
                <div class="input-group search-input-group shadow-sm">
                    <input type="text" name="q" class="form-control border-secondary" placeholder="Cari buku..."
                        value="{{ request('q') }}" style="padding: 0.5rem 1rem; font-size: 1rem;">
                    <button class="btn btn-search px-3" type="submit">
                        <i class="bi bi-search fs-6"></i>
                    </button>
                </div>
            </form>
        </div>

        {{-- Right Menu --}}
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto align-items-center nav-menu">
                {{-- Katalog (Desktop) --}}
                <li class="nav-item d-none d-lg-block mx-2">
                    <a class="nav-link d-flex flex-column align-items-center justify-content-center text-white py-1 px-2" href="{{ route('catalog.index') }}">
                        <i class="bi bi-grid-3x3-gap-fill mb-1" style="font-size: 1.4rem;"></i>
                        <span style="font-size: 0.85rem; line-height: 1; font-weight: 500;">Katalog</span>
                    </a>
                </li>

                @auth
                    {{-- Wishlist --}}
                    <li class="nav-item mx-2">
                        <a class="nav-link position-relative d-flex flex-column align-items-center justify-content-center text-white py-1 px-2"
                            href="{{ route('wishlist.index') }}">
                            <div class="position-relative mb-1">
                                <i class="bi bi-heart-fill nav-icon" style="font-size: 1.4rem; color: #f472b6;"></i>
                                @if (auth()->user()->wishlists()->count() > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger nav-badge" style="font-size: 0.65rem; min-width: 15px; height: 15px;">
                                        {{ auth()->user()->wishlists()->count() > 9 ? '9+' : auth()->user()->wishlists()->count() }}
                                    </span>
                                @endif
                            </div>
                            <span style="font-size: 0.85rem; line-height: 1; font-weight: 500;">Wishlist</span>
                        </a>
                    </li>

                    {{-- Loans / Peminjaman --}}
                    <li class="nav-item mx-2">
                        <a class="nav-link position-relative d-flex flex-column align-items-center justify-content-center text-white py-1 px-2"
                            href="{{ route('loans.index') }}">
                            <div class="position-relative mb-1">
                                <i class="bi bi-book nav-icon" style="font-size: 1.4rem; color: #60a5fa;"></i>
                                @php
                                    $loanCount =
                                        auth()
                                            ->user()
                                            ->loans()
                                            ->whereIn('status', ['pending', 'approved', 'borrowed'])
                                            ->count() ?? 0;
                                @endphp
                                @if ($loanCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning nav-badge text-dark" style="font-size: 0.65rem; min-width: 15px; height: 15px;">
                                        {{ $loanCount > 9 ? '9+' : $loanCount }}
                                    </span>
                                @endif
                            </div>
                            <span style="font-size: 0.85rem; line-height: 1; font-weight: 500;">Peminjaman</span>
                        </a>
                    </li>

                    {{-- Theme Toggle --}}
                    <li class="nav-item mx-2 d-none d-lg-block">
                        <button class="btn btn-link nav-link position-relative d-flex flex-column align-items-center justify-content-center text-white py-1 px-2 border-0" onclick="toggleTheme()">
                            <i class="bi bi-sun-fill nav-icon text-warning" id="theme-icon-sun"></i>
                            <i class="bi bi-moon-stars-fill nav-icon text-white d-none" id="theme-icon-moon"></i>
                            <span style="font-size: 0.85rem; line-height: 1; font-weight: 500;">Tema</span>
                        </button>
                    </li>

                    {{-- User Dropdown --}}
                    <li class="nav-item dropdown ms-2 ms-lg-3">
                        <a class="nav-link dropdown-toggle d-flex flex-column align-items-center justify-content-center user-dropdown-toggle py-1 px-2" href="#"
                            id="userDropdown" data-bs-toggle="dropdown">
                            <span style="transform: scale(1.1); margin-bottom: 2px; display: inline-block;">
                                {!! auth()->user()->avatar_html !!}
                            </span>
                            <span class="d-none d-lg-block mt-1 user-name fw-bold" style="font-size: 0.9rem; max-width: 150px;">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end modern-dropdown mt-2" style="font-size: 1.1rem;">
                            <li class="px-3 py-2 border-bottom mb-2 text-center">
                                <span class="badge bg-{{ auth()->user()->badge_color }} rounded-pill border border-{{ auth()->user()->badge_color }} bg-opacity-10 text-{{ auth()->user()->badge_color === 'warning' ? 'warning' : 'white' }} w-100 mb-1">
                                    {{ auth()->user()->badge_name }}
                                </span>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person-fill me-2"></i> Profil Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('loans.index') }}">
                                    <i class="bi bi-book me-2"></i> Peminjaman Saya
                                </a>
                            </li>
                            @if (auth()->user()->isAdmin())
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-primary" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i> Admin Panel
                                    </a>
                                </li>
                            @endif
                            <li>
                                <hr class="dropdown-divider">
                            </li>
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
        <button class="navbar-toggler mobile-toggle-btn" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>

    {{-- Mobile Search Bar (Visible only on mobile) --}}
    <div class="mobile-search-container d-lg-none">
        <form action="{{ route('catalog.index') }}" method="GET" class="px-3 pb-3">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Cari buku..."
                    value="{{ request('q') }}">
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
        padding-top: 0;
        padding-bottom: 0;
        position: relative;
        z-index: 1030;
    }

    .brand-logo {
        width: 70px;
        height: 70px;
        object-fit: contain;
    }

    .brand-text {
        font-size: 1.5rem;
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

    .nav-icon-link {
        padding: 0.5rem !important;
    }

    .nav-icon {
        font-size: 1.8rem;
    }

    .nav-badge {
        font-size: 0.75rem;
        min-width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
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

    /* Dropdown Container - PENTING untuk fungsi dropdown */
    .nav-item.dropdown {
        position: relative;
    }

    /* Dropdown Menu - styling dan positioning */
    .modern-dropdown {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        z-index: 1050;
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
        padding: 0.5rem;
        min-width: 200px;
        margin-top: 0.5rem;
    }

    /* Show dropdown when active */
    .modern-dropdown.show {
        display: block;
        animation: dropdownFadeIn 0.2s ease-out;
    }

    @keyframes dropdownFadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modern-dropdown .dropdown-item {
        color: white;
        padding: 0.6rem 1rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        cursor: pointer;
        text-decoration: none;
        display: block;
        width: 100%;
        border: none;
        background: none;
        font-size: 1rem;
        text-align: left;
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

    /* Dropdown arrow rotation */
    .user-dropdown-toggle[aria-expanded="true"] .dropdown-arrow {
        transform: rotate(180deg);
    }

    .dropdown-arrow {
        transition: transform 0.3s ease;
        font-size: 0.75rem;
        margin-left: 0.25rem;
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
            padding: 0 0;
        }

        .nav-menu {
            gap: 0.25rem;
        }
    }

    /* ========== TABLET (576px - 991px) ========== */
    @media (min-width: 576px) and (max-width: 991.98px) {
        .brand-logo {
            width: 50px;
            height: 50px;
        }

        .brand-text {
            font-size: 1.3rem;
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
            width: 45px;
            height: 45px;
        }

        .brand-text {
            font-size: 1.25rem;
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
            width: 40px;
            height: 40px;
        }

        .brand-text {
            font-size: 1.15rem;
        }
    }
</style>

<script>
    // ========== DROPDOWN PROFIL FIX ==========
    document.addEventListener('DOMContentLoaded', function() {
        var userDropdown = document.getElementById('userDropdown');
        var dropdownMenu = document.querySelector('.modern-dropdown');

        if (userDropdown && dropdownMenu) {
            // Toggle dropdown on click
            userDropdown.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var isShown = dropdownMenu.classList.contains('show');

                // Close all dropdowns first
                document.querySelectorAll('.modern-dropdown.show').forEach(function(dropdown) {
                    dropdown.classList.remove('show');
                });

                // Toggle current dropdown
                if (!isShown) {
                    dropdownMenu.classList.add('show');
                    userDropdown.setAttribute('aria-expanded', 'true');
                } else {
                    userDropdown.setAttribute('aria-expanded', 'false');
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!userDropdown.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.remove('show');
                    userDropdown.setAttribute('aria-expanded', 'false');
                }
            });

            // Close dropdown on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    dropdownMenu.classList.remove('show');
                    userDropdown.setAttribute('aria-expanded', 'false');
                }
            });

            // Prevent dropdown from closing when clicking inside
            dropdownMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    });
</script>
