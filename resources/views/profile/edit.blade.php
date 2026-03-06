@extends('layouts.app')

@section('content')
<div class="profile-page py-4">
    <div class="container">
        {{-- Page Header --}}
        <div class="profile-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <h1 class="page-title text-white mb-1">
                        <i class="bi bi-person-fill me-2" style="color: #60a5fa;"></i>
                        Pengaturan Profil
                    </h1>
                    <p class="page-subtitle text-secondary mb-0">
                        Kelola informasi profil dan akun Anda
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-house-door me-1"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Profile Cards Grid --}}
        <div class="row g-4">
            {{-- Avatar Section --}}
            <div class="col-lg-6">
                <div class="profile-card">
                    <div class="card-header">
                        <i class="bi bi-image me-2"></i>Foto Profil
                    </div>
                    <div class="card-body text-center">
                        {!! $user->profile_avatar_html !!}
                        <h4 class="mt-3 mb-1 text-white">{{ $user->name }}</h4>
                        <p class="text-secondary small mb-3">{{ $user->email }}</p>
                        @include('profile.partials.update-avatar-form')
                    </div>
                </div>
            </div>

            {{-- Profile Info Section --}}
            <div class="col-lg-6">
                <div class="profile-card">
                    <div class="card-header">
                        <i class="bi bi-person-badge me-2"></i>Informasi Profil
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            {{-- Password Section --}}
            <div class="col-lg-6">
                <div class="profile-card">
                    <div class="card-header">
                        <i class="bi bi-shield-lock me-2"></i>Ubah Password
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            {{-- Connected Accounts Section --}}
          
            {{-- Admin Access Section --}}
            @if($user->isAdmin())
            <div class="col-12">
                <div class="profile-card border-primary">
                    <div class="card-header bg-primary">
                        <i class="bi bi-speedometer2 me-2"></i>Akses Admin
                    </div>
                    <div class="card-body">
                        <p class="text-secondary mb-3">Anda memiliki akses administrator. Klik tombol di bawah untuk masuk ke panel admin.</p>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                            <i class="bi bi-grid me-2"></i>Masuk ke Admin Panel
                        </a>
                    </div>
                </div>
            </div>
            @endif

            {{-- Logout Section --}}
            <div class="col-lg-6">
                <div class="profile-card border-warning">
                    <div class="card-header bg-warning text-dark">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </div>
                    <div class="card-body">
                        <p class="text-secondary mb-3">Klik tombol di bawah untuk keluar dari akun Anda.</p>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Delete Account Section --}}
            <div class="col-lg-6">
                <div class="profile-card border-danger">
                    <div class="card-header bg-danger text-white">
                        <i class="bi bi-trash3 me-2"></i>Hapus Akun
                    </div>
                    <div class="card-body">
                        <p class="text-secondary mb-3">Tindakan ini tidak dapat dibatalkan. Semua data Anda akan dihapus permanen.</p>
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* ========== PAGE HEADER ========== */
    .profile-page {
        min-height: 70vh;
    }
    
    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
    }
    
    .page-subtitle {
        font-size: 0.95rem;
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
    
    .btn-primary {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        border: none;
        color: white;
        padding: 0.6rem 1.25rem;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #60a5fa, #3b82f6);
        color: white;
        transform: translateY(-2px);
    }
    
    .btn-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border: none;
        color: white;
        padding: 0.6rem 1.25rem;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .btn-warning:hover {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: white;
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        border: none;
        color: white;
        padding: 0.6rem 1.25rem;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .btn-danger:hover {
        background: linear-gradient(135deg, #f87171, #ef4444);
        color: white;
    }
    
    /* ========== PROFILE CARD ========== */
    .profile-card {
        background: rgba(30, 41, 59, 0.9);
        border: 1px solid #334155;
        border-radius: 16px;
        overflow: hidden;
        height: 100%;
        transition: all 0.3s ease;
    }
    
    .profile-card:hover {
        border-color: #3b82f6;
        box-shadow: 0 10px 30px rgba(59, 130, 246, 0.2);
    }
    
    .profile-card .card-header {
        background: rgba(15, 23, 42, 0.8);
        border-bottom: 1px solid #334155;
        padding: 1rem 1.25rem;
        font-weight: 600;
        color: white;
        display: flex;
        align-items: center;
    }
    
    .profile-card .card-header i {
        color: #60a5fa;
    }
    
    .profile-card .card-body {
        padding: 1.25rem;
    }
    
    .profile-card.border-primary {
        border-color: #3b82f6;
    }
    
    .profile-card.border-warning {
        border-color: #f59e0b;
    }
    
    .profile-card.border-warning .card-header {
        border-bottom-color: #f59e0b;
    }
    
    .profile-card.border-danger {
        border-color: #ef4444;
    }
    
    .profile-card.border-danger .card-header {
        border-bottom-color: #ef4444;
    }
    
    /* ========== FORM STYLES ========== */
    .form-label {
        color: #e0e0e0;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .form-control {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid #334155;
        color: white;
        padding: 0.6rem 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        background: rgba(15, 23, 42, 0.8);
        border-color: #3b82f6;
        color: white;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
    }
    
    .form-control::placeholder {
        color: #64748b;
    }
    
    .form-select {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid #334155;
        color: white;
        padding: 0.6rem 1rem;
        border-radius: 8px;
    }
    
    .form-select:focus {
        background: rgba(15, 23, 42, 0.8);
        border-color: #3b82f6;
        color: white;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
    }
    
    .form-select option {
        background: #1e293b;
        color: white;
    }
    
    .form-text {
        color: #64748b;
        font-size: 0.8rem;
    }
    
    .alert-success {
        background: rgba(34, 197, 94, 0.15);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: #22c55e;
        border-radius: 10px;
    }
    
    /* ========== RESPONSIVE STYLES ========== */
    
    /* Tablet (576px - 991px) */
    @media (min-width: 576px) and (max-width: 991.98px) {
        .page-title {
            font-size: 1.5rem;
        }
        
        .profile-card .card-body {
            padding: 1rem;
        }
    }
    
    /* Mobile (< 576px) */
    @media (max-width: 575.98px) {
        .profile-page {
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
        }
        
        .page-title {
            font-size: 1.35rem;
        }
        
        .page-subtitle {
            font-size: 0.85rem;
        }
        
        .profile-header .row {
            text-align: center;
        }
        
        .col-md-4.text-md-end {
            text-align: center !important;
        }
        
        .profile-header .btn {
            margin-top: 0.5rem;
        }
        
        .profile-card {
            border-radius: 12px;
        }
        
        .profile-card .card-header {
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
        }
        
        .profile-card .card-body {
            padding: 1rem;
        }
        
        .profile-card .mb-3 {
            margin-bottom: 1rem !important;
        }
        
        .profile-card .mb-4 {
            margin-bottom: 1.25rem !important;
        }
        
        .col-lg-6 {
            margin-bottom: 1rem;
        }
        
        .row.g-4 {
            gap: 1rem !important;
        }
        
        .form-label {
            font-size: 0.9rem;
        }
        
        .form-control,
        .form-select {
            padding: 0.55rem 0.85rem;
            font-size: 0.9rem;
        }
        
        .btn {
            padding: 0.55rem 1rem;
            font-size: 0.9rem;
        }
        
        .btn-sm {
            padding: 0.4rem 0.75rem;
            font-size: 0.8rem;
        }
    }
    
    /* Very Small Mobile (< 360px) */
    @media (max-width: 359.98px) {
        .page-title {
            font-size: 1.2rem;
        }
        
        .profile-card .card-header {
            font-size: 0.9rem;
            padding: 0.6rem 0.75rem;
        }
        
        .profile-card .card-body {
            padding: 0.75rem;
        }
    }
</style>
@endsection