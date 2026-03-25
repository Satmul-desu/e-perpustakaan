<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'E-Perpustakaan') }} - Portofolio Web App</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 50%, #16213e 100%);
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-custom {
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar-custom .nav-link {
            color: #e2e8f0;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .navbar-custom .nav-link:hover {
            color: #60a5fa;
            transform: translateY(-2px);
        }
        
        .btn-solid-primary {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-solid-primary:hover {
            background: #2563eb;
            color: white;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }

        .hero-section {
            padding: 70px 0 45px;
            text-align: center;
        }

        .hero-title {
            font-size: 3.6rem;
            font-weight: 800;
            margin-bottom: 20px;
            color: #ffffff;
            text-shadow: none;
        }

        .hero-subtitle {
            font-size: 1.45rem;
            color: #ffffff;
            font-weight: 500;
            max-width: 600px;
            margin: 0 auto 35px;
            text-shadow: none;
        }

        .feature-card {
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            height: 100%;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            background: rgba(30, 41, 59, 0.9);
            border-color: #3b82f6;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.2);
        }

        .feature-icon {
            font-size: 3rem;
            color: #60a5fa;
            margin-bottom: 20px;
        }

        section {
            padding: 60px 0;
        }
        
        main {
            flex: 1;
        }
    </style>
</head>

<body>

    <!-- Navbar Khusus Landing Page -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center fw-bold" href="{{ url('/') }}">
                <img src="{{ asset('images/logo-removebg-preview.png') }}" alt="Logo" class="me-2 object-fit-contain" style="width: 100px; height:  100px; margin-top: -15px; margin-bottom: -15px;">
                <span>E-<span class="text-primary">Perpustakaan</span></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#landingNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="landingNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang-kami">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang-app">Tentang E-Perpustakaan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang-pembuat">Tentang Pembuat</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    @auth
                        <a href="{{ route('home') }}" class="btn btn-solid-primary">Masuk Aplikasi <i class="bi bi-arrow-right"></i></a>
                    @else
                        <a href="{{ route('login') }}" class="nav-link text-white">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-solid-primary">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main>
        <!-- Hero Section -->
        <section class="hero-section" style="background: transparent; box-shadow: none; position: relative; z-index: 10;">
            <div class="container">
                <img src="{{ asset('images/logo-removebg-preview.png') }}" alt="E-Perpus Logo" class="img-fluid" style="max-height: 420px; margin-top: -65px; margin-bottom: -45px; animation: float 3s ease-in-out infinite; position: relative; z-index: 10;">
                <h1 class="hero-title" style="color: #60a5fa !important;">E-Perpustakaan System</h1>
                <p class="hero-subtitle">Platform manajemen perpustakaan digital modern yang memudahkan proses peminjaman, pengelolaan inventaris, dan memberikan pengalaman membaca tanpa batas untuk semua anggota.</p>
                @guest
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('login') }}" class="btn btn-solid-primary btn-lg px-4">Mulai Sekarang</a>
                    <a href="#tentang-app" class="btn btn-outline-light btn-lg px-4" style="border-radius: 8px;">Pelajari Lebih Lanjut</a>
                </div>
                @endguest
            </div>
        </section>

        <!-- Tentang E-Perpustakaan (Fitur) -->
        <section id="tentang-app" class="bg-dark-custom" style="background: rgba(15, 23, 42, 0.4);">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="text-white fw-bold">Fitur Utama</h2>
                    <p class="text-secondary">Kenapa menggunakan platform E-Perpustakaan ini?</p>
                </div>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="feature-card">
                            <i class="bi bi-book feature-icon"></i>
                            <h4 class="text-white mb-3">Katalog Lengkap</h4>
                            <p class="text-secondary mb-0">Ribuan koleksi buku digital dari berbagai genre yang dapat dieksplorasi dengan mudah dan cepat melalui fitur pencarian canggih.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card">
                            <i class="bi bi-arrow-repeat feature-icon"></i>
                            <h4 class="text-white mb-3">Peminjaman Digital</h4>
                            <p class="text-secondary mb-0">Sistem sirkulasi peminjaman dan pengembalian buku yang terotomatisasi penuh dengan notifikasi masa pinjam.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card">
                            <i class="bi bi-shield-check feature-icon"></i>
                            <h4 class="text-white mb-3">Admin Dashboard</h4>
                            <p class="text-secondary mb-0">Panel kelola komprehensif untuk pengurus perpustakaan memantau data peminjam, persetujuan, dan statistik bulanan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Tentang Kami & Pembuat -->
        <section id="tentang-kami">
            <div class="container">
                <div class="row align-items-center mb-5 pb-5 border-bottom border-secondary">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <div class="p-4 rounded-4" style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2);">
                            <h3 class="text-white fw-bold mb-3"><i class="bi bi-building me-2 text-primary"></i> Tentang Kami</h3>
                            <p class="text-light" style="line-height: 1.8;">
                                Kami adalah tim inovator yang bertekad membawa pengalaman perpustakaan tradisional ke era digital. 
                                Dengan visi "Membaca Tanpa Batas Ruang dan Waktu," platform E-Perpustakaan didedikasikan untuk 
                                institusi pendidikan, perusahaan, maupun komunitas membaca publik.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6" id="tentang-pembuat">
                        <div class="p-4 rounded-4" style="background: rgba(168, 85, 247, 0.1); border: 1px solid rgba(168, 85, 247, 0.2);">
                            <h3 class="text-white fw-bold mb-3"><i class="bi bi-person-badge me-2" style="color: #a855f7;"></i> Tentang Pembuat</h3>
                            <p class="text-light" style="line-height: 1.8;">
                                Proyek portofolio Web Developer ini dikembangkan dengan menggunakan tumpukan teknologi modern (Laravel, Bootstrap). 
                                Dibuat dengan passion terhadap User Interface dan User Experience yang responsif, minimalis, dan elegan.
                            </p>
                            <a href="https://github.com/satmul-desu" class="btn btn-sm btn-outline-light mt-2" style="border-radius: 8px;"><i class="bi bi-github me-1"></i> Lihat Portofolio Lain</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
    </style>
</body>
</html>
