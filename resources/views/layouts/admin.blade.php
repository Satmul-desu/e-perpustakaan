{{-- ================================================
     FILE: resources/views/layouts/admin.blade.php
     FUNGSI: Master layout untuk halaman admin - SIDEBAR STYLE (Kembali ke Versi Asli)
     ================================================ --}}

<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

                    <title>@yield('title') - Perpustakaan Buku</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            --sidebar-width: 280px;
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --secondary-color: #64748b;
            --success-color: #22c55e;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            
            --bg-dark: #0f172a;
            --bg-sidebar: #1e1b4b;
            --bg-card: #1e293b;
            --bg-card-hover: #334155;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --border-color: #334155;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--bg-dark);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--secondary-color);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--bg-sidebar) 0%, #0f0a2e 100%);
            z-index: 1000;
            transition: all 0.3s ease;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
        }

        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            background: rgba(0, 0, 0, 0.2);
        }

        .sidebar-brand .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-primary);
        }

        .sidebar-brand .logo-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--primary-color), #8b5cf6);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }

        .sidebar-brand .logo-text {
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        /* Navigation */
        .sidebar-nav {
            padding: 1rem 0;
            height: calc(100vh - 200px);
            overflow-y: auto;
        }

        .nav-section {
            padding: 0.5rem 1.25rem;
            margin-top: 0.5rem;
        }

        .nav-section-title {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--secondary-color);
            margin-bottom: 0.75rem;
        }

        .nav-item {
            margin: 2px 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 1.25rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(255,255,255,0.05) 0%, transparent 100%);
            transition: left 0.3s ease;
        }

        .nav-link:hover::before {
            left: 0;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--text-primary);
            background: rgba(99, 102, 241, 0.1);
            border-left-color: var(--primary-color);
        }

        .nav-link.active {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.15) 0%, transparent 100%);
        }

        .nav-link i {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        .nav-link .badge {
            margin-left: auto;
            padding: 0.35em 0.65em;
            font-size: 0.7rem;
            font-weight: 600;
            border-radius: 20px;
        }

        .nav-link .badge-notif {
            background: var(--danger-color);
            color: white;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        /* User Section */
        .sidebar-user {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1rem 1.25rem;
            background: rgba(0, 0, 0, 0.3);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-color), #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            color: white;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .user-details {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--success-color);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .user-role::before {
            content: '';
            width: 6px;
            height: 6px;
            background: var(--success-color);
            border-radius: 50%;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: var(--bg-dark);
            width: calc(100% - var(--sidebar-width));
        }

        /* Page Content - Full Width */
        .page-content {
            padding: 2rem;
            width: 100%;
            max-width: 100%;
        }

        /* Header */
        .top-header {
            background: rgba(30, 27, 75, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
            width: 100%;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .breadcrumb {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-top: 4px;
        }

        .breadcrumb a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .btn-icon:hover {
            background: var(--bg-card-hover);
            color: var(--text-primary);
            border-color: var(--primary-color);
        }

        .btn-icon .badge {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: var(--danger-color);
            color: white;
            font-size: 0.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Cards */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Stat Cards */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-icon.primary {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(139, 92, 246, 0.2));
            color: #818cf8;
        }

        .stat-icon.success {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(22, 163, 74, 0.2));
            color: #4ade80;
        }

        .stat-icon.warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(234, 88, 12, 0.2));
            color: #fbbf24;
        }

        .stat-icon.danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.2));
            color: #f87171;
        }

        .stat-icon.info {
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(8, 145, 178, 0.2));
            color: #22d3ee;
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .stat-change {
            font-size: 0.8rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .stat-change.positive {
            color: var(--success-color);
        }

        .stat-change.negative {
            color: var(--danger-color);
        }

        /* Tables */
        .table {
            color: var(--text-secondary);
            margin-bottom: 0;
        }

        .table thead th {
            background: rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid var(--border-color);
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem 1.5rem;
        }

        .table tbody td {
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.5rem;
            vertical-align: middle;
        }

        .table tbody tr {
            transition: background 0.2s ease;
        }

        .table tbody tr:hover {
            background: rgba(99, 102, 241, 0.05);
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #4f46e5);
            border: none;
            font-weight: 600;
            padding: 0.625rem 1.25rem;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4f46e5, #4338ca);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }

        .btn-outline-primary {
            border-color: var(--primary-color);
            color: var(--primary-color);
            font-weight: 500;
            border-radius: 10px;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-success {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border: none;
            font-weight: 600;
            border-radius: 10px;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border: none;
            font-weight: 600;
            border-radius: 10px;
        }

        /* Badges */
        .badge {
            padding: 0.4em 0.8em;
            font-weight: 600;
            border-radius: 6px;
        }

        /* Page Content - Full Width */
        .page-content {
            padding: 2rem;
            width: 100%;
            max-width: 100%;
        }

        /* Container Fluid for Full Width */
        .page-content .container-fluid,
        .page-content > .row {
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 0;
        }

        /* Ensure cards take full width */
        .page-content .card {
            width: 100%;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease forwards;
        }

        .animate-delay-1 { animation-delay: 0.1s; }
        .animate-delay-2 { animation-delay: 0.2s; }
        .animate-delay-3 { animation-delay: 0.3s; }
        .animate-delay-4 { animation-delay: 0.4s; }

        /* Dropdown Styles */
        .dropdown-container {
            position: relative;
        }

        .dropdown-menu-custom {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 8px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
            min-width: 320px;
            max-width: 360px;
            z-index: 1001;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s ease;
        }

        .dropdown-menu-custom.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-header-custom {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dropdown-header-custom h6 {
            margin: 0;
            font-weight: 600;
            color: var(--text-primary);
        }

        .dropdown-header-custom .badge {
            font-size: 0.7rem;
            padding: 0.3em 0.6em;
        }

        .dropdown-body-custom {
            max-height: 320px;
            overflow-y: auto;
        }

        .dropdown-item-custom {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 1rem;
            text-decoration: none;
            color: var(--text-secondary);
            transition: all 0.15s ease;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        }

        .dropdown-item-custom:hover {
            background: rgba(99, 102, 241, 0.1);
            color: var(--text-primary);
        }

        .dropdown-item-custom:last-child {
            border-bottom: none;
        }

        .dropdown-item-custom .icon-wrapper {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .dropdown-item-custom .icon-wrapper.complaint {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(234, 88, 12, 0.2));
            color: #fbbf24;
        }

        .dropdown-item-custom .icon-wrapper.order {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(22, 163, 74, 0.2));
            color: #4ade80;
        }

        .dropdown-item-custom .content {
            flex: 1;
            min-width: 0;
        }

        .dropdown-item-custom .title {
            font-weight: 500;
            font-size: 0.85rem;
            color: var(--text-primary);
            margin-bottom: 2px;
        }

        .dropdown-item-custom .description {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        .dropdown-item-custom .time {
            font-size: 0.7rem;
            color: var(--secondary-color);
            white-space: nowrap;
        }

        .dropdown-footer-custom {
            padding: 0.75rem;
            border-top: 1px solid var(--border-color);
            text-align: center;
        }

        .dropdown-footer-custom a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .dropdown-footer-custom a:hover {
            text-decoration: underline;
        }

        /* Settings Dropdown */
        .settings-dropdown .dropdown-item-custom {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .settings-dropdown .dropdown-item-custom i {
            width: 20px;
            text-align: center;
        }

        .settings-dropdown .dropdown-item-custom:hover i {
            color: var(--primary-color);
        }

        /* Notification badge pulse */
        .notification-badge-pulse {
            animation: pulse 2s infinite;
        }

        /* Overlay for closing dropdown */
        .dropdown-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1000;
            display: none;
        }

        .dropdown-overlay.show {
            display: block;
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="d-flex">
        {{-- Sidebar --}}
        <aside class="sidebar d-flex flex-column">
            {{-- Brand --}}
            <div class="sidebar-brand">
                <a href="{{ route('admin.dashboard') }}" class="logo">
                    <div class="logo-icon">
                        <i class="bi bi-book"></i>
                    </div>
                    <span>Perpustakaan</span>
                </a>
            </div>

            {{-- Navigation --}}
            <nav class="sidebar-nav flex-grow-1">
                <div class="nav-section">
                    <span class="nav-section-title">Menu Utama</span>
                </div>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-grid-1x2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.products.index') }}"
                           class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <i class="bi bi-book-half"></i>
                            <span>Buku</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.categories.index') }}"
                           class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="bi bi-folder"></i>
                            <span>Kategori</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.loans.index') }}"
                           class="nav-link {{ request()->routeIs('admin.loans.*') ? 'active' : '' }}">
                            <i class="bi bi-journal-bookmark"></i>
                            <span>Peminjaman</span>
                            @if(isset($pendingCount) && $pendingCount > 0)
                                <span class="badge badge-notif">{{ $pendingCount }}</span>
                            @endif
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.complaints.index') }}"
                           class="nav-link {{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
                            <i class="bi bi-headset"></i>
                            <span>Aduan CS</span>
                            @if(isset($complaintCount) && $complaintCount > 0)
                                <span class="badge badge-notif">{{ $complaintCount }}</span>
                            @endif
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}"
                           class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="bi bi-people"></i>
                            <span>Anggota</span>
                        </a>
                    </li>
                </ul>

                <div class="nav-section">
                    <span class="nav-section-title">Laporan & Analitik</span>
                </div>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.reports.loans') }}"
                           class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <i class="bi bi-graph-up-arrow"></i>
                            <span>Laporan Peminjaman</span>
                        </a>
                    </li>
                </ul>
            </nav>

            {{-- User Info --}}
            <div class="sidebar-user">
                <div class="user-info">
                    <div class="user-avatar">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </div>
                    <div class="user-details">
                        <div class="user-name" title="{{ auth()->user()->name }}">
                            {{ auth()->user()->name }}
                        </div>
                        <div class="user-role">Administrator</div>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn-icon" title="Logout" onclick="return confirm('Yakin ingin logout?')">
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </form>
                </div>
        </aside>

        {{-- Main Content --}}
        <div class="main-content">
            {{-- Top Bar --}}
            <header class="top-header d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                    <nav class="breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">Admin</a>
                        <span class="mx-2">/</span>
                        <span>@yield('page-title', 'Dashboard')</span>
                    </nav>
                </div>
                <div class="header-actions">
                    <a href="{{ route('home') }}" class="btn btn-outline-primary" target="_blank">
                        <i class="bi bi-box-arrow-up-right me-2"></i>Lihat Perpustakaan
                    </a>

                    {{-- Notification Dropdown --}}
                    <div class="dropdown-container">
                        <button class="btn-icon dropdown-toggle-custom" id="notificationDropdown" title="Notifikasi" onclick="toggleDropdown('notificationDropdown', 'notificationMenu')">
                            <i class="bi bi-bell"></i>
                            @if((isset($complaintCount) && $complaintCount > 0) || (isset($pendingCount) && $pendingCount > 0))
                                <span class="badge notification-badge-pulse">{{ ($complaintCount ?? 0) + ($pendingCount ?? 0) }}</span>
                            @endif
                        </button>
                        <div class="dropdown-menu-custom" id="notificationMenu">
                            <div class="dropdown-header-custom">
                                <h6>Notifikasi</h6>
                                @if(($complaintCount ?? 0) > 0 || ($pendingCount ?? 0) > 0)
                                    <span class="badge bg-warning text-dark">{{ ($complaintCount ?? 0) + ($pendingCount ?? 0) }} baru</span>
                                @endif
                            </div>
                            <div class="dropdown-body-custom">
                                {{-- Complaint Notifications --}}
                                @if(isset($recentComplaints) && $recentComplaints->count() > 0)
                                    @foreach($recentComplaints as $complaint)
                                        <a href="{{ route('admin.complaints.show', $complaint) }}" class="dropdown-item-custom">
                                            <div class="icon-wrapper complaint">
                                                <i class="bi bi-headset"></i>
                                            </div>
                                            <div class="content">
                                                <div class="title">Aduan dari {{ $complaint->user->name }}</div>
                                                <div class="description">{{ Str::limit($complaint->message, 50) }}</div>
                                            </div>
                                            <div class="time">{{ $complaint->created_at->diffForHumans() }}</div>
                                        </a>
                                    @endforeach
                                @else
                                    <div class="dropdown-item-custom" style="cursor: default;">
                                        <div class="icon-wrapper" style="background: rgba(99, 102, 241, 0.1); color: #818cf8;">
                                            <i class="bi bi-bell-slash"></i>
                                        </div>
                                        <div class="content">
                                            <div class="title">Tidak ada notifikasi</div>
                                            <div class="description">Semua sudah terproses</div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Pending Loans --}}
                                @if(isset($pendingCount) && $pendingCount > 0)
                                    <a href="{{ route('admin.loans.index', ['status' => 'pending']) }}" class="dropdown-item-custom">
                                        <div class="icon-wrapper order">
                                            <i class="bi bi-journal-bookmark"></i>
                                        </div>
                                        <div class="content">
                                            <div class="title">{{ $pendingCount }} Peminjaman Menunggu</div>
                                            <div class="description">Menunggu persetujuan</div>
                                        </div>
                                        <div class="time">Baru</div>
                                    </a>
                                @endif
                            </div>
                            <div class="dropdown-footer-custom">
                                <a href="{{ route('admin.complaints.index') }}">Lihat Semua Aduan</a>
                            </div>
                        </div>
                    </div>

                    {{-- Settings Dropdown --}}
                    <div class="dropdown-container">
                        <button class="btn-icon dropdown-toggle-custom" id="settingsDropdown" title="Pengaturan" onclick="toggleDropdown('settingsDropdown', 'settingsMenu')">
                            <i class="bi bi-gear"></i>
                        </button>
                        <div class="dropdown-menu-custom settings-dropdown" id="settingsMenu">
                            <div class="dropdown-header-custom">
                                <h6>Pengaturan</h6>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="dropdown-item-custom">
                                <i class="bi bi-person"></i>
                                <div class="content">
                                    <div class="title">Profil Saya</div>
                                    <div class="description">Kelola akun Anda</div>
                                </div>
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="dropdown-item-custom">
                                <i class="bi bi-people"></i>
                                <div class="content">
                                    <div class="title">Kelola Anggota</div>
                                    <div class="description">Admin & Anggota</div>
                                </div>
                            </a>
                            <div class="dropdown-footer-custom" style="border-top: 1px solid var(--border-color); padding: 0.75rem;">
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item-custom" style="width: 100%; justify-content: center; color: var(--danger-color);">
                                        <i class="bi bi-box-arrow-right"></i>
                                        <span class="title">Keluar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Flash Messages --}}
            <div class="px-4 pt-3">
                @include('partials.flash-messages')
            </div>

            {{-- Page Content --}}
            <main class="page-content">
                @yield('content')
            </main>
        </div>

    {{-- Dropdown Overlay --}}
    <div class="dropdown-overlay" id="dropdownOverlay" onclick="closeAllDropdowns()"></div>

    {{-- Dropdown Toggle Script --}}
    <script>
        function toggleDropdown(buttonId, menuId) {
            event.stopPropagation();
            const button = document.getElementById(buttonId);
            const menu = document.getElementById(menuId);
            const overlay = document.getElementById('dropdownOverlay');

            // Close all other dropdowns first
            document.querySelectorAll('.dropdown-menu-custom').forEach(el => {
                if (el.id !== menuId) {
                    el.classList.remove('show');
                }
            });

            // Toggle current dropdown
            menu.classList.toggle('show');
            overlay.classList.toggle('show', document.querySelectorAll('.dropdown-menu-custom.show').length > 0);
        }

        function closeAllDropdowns() {
            document.querySelectorAll('.dropdown-menu-custom').forEach(el => {
                el.classList.remove('show');
            });
            document.getElementById('dropdownOverlay').classList.remove('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.dropdown-container')) {
                closeAllDropdowns();
            }
        });

        // Close dropdown on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeAllDropdowns();
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
