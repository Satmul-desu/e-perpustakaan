@extends('layouts.app')
@section('title', 'Customer Service - TokoBuku')
@push('styles')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --dark-bg: rgba(30, 41, 59, 0.95);
            --glass-bg: rgba(255, 255, 255, 0.05);
            --border-color: rgba(255, 255, 255, 0.1);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --accent-color: #818cf8;
        }

        .cs-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Hero Section */
        .cs-hero {
            background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 50%, #1e1b4b 100%);
            border-radius: 24px;
            padding: 3rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .cs-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(129, 140, 248, 0.15) 0%, transparent 70%);
            animation: pulse-glow 4s ease-in-out infinite;
        }

        .cs-hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.1) 0%, transparent 70%);
            animation: pulse-glow 4s ease-in-out infinite reverse;
        }

        @keyframes pulse-glow {

            0%,
            100% {
                opacity: 0.5;
                transform: scale(1);
            }

            50% {
                opacity: 0.8;
                transform: scale(1.1);
            }
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #fff 0%, #a5b4fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .hero-subtitle {
            color: #94a3b8;
            font-size: 1.1rem;
            max-width: 500px;
        }

        /* Glass Cards */
        .glass-card {
            background: var(--dark-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border-color: rgba(129, 140, 248, 0.3);
        }

        /* Feature Cards */
        .feature-card {
            background: linear-gradient(145deg, rgba(30, 41, 59, 0.9) 0%, rgba(15, 23, 42, 0.9) 100%);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: var(--accent-color);
            box-shadow: 0 15px 30px rgba(129, 140, 248, 0.15);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin: 0 auto 1rem;
            transition: all 0.3s ease;
        }

        .feature-icon.primary {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(139, 92, 246, 0.2));
            color: #818cf8;
        }

        .feature-icon.success {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(22, 163, 74, 0.2));
            color: #4ade80;
        }

        .feature-icon.info {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.2));
            color: #60a5fa;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }

        /* Form Styles */
        .form-container {
            background: linear-gradient(145deg, rgba(30, 41, 59, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 2rem;
            backdrop-filter: blur(10px);
        }

        .form-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .form-title i {
            color: #818cf8;
        }

        .form-label {
            font-weight: 500;
            color: #e2e8f0;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-control,
        .form-select {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #f1f5f9;
            border-radius: 12px;
            padding: 0.875rem 1rem;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .form-control:focus,
        .form-select:focus {
            background: rgba(15, 23, 42, 0.8);
            border-color: #6366f1;
            color: #f1f5f9;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        }

        .form-control::placeholder {
            color: #64748b;
        }

        .form-control option,
        .form-select option {
            background: #1e293b;
            color: #f1f5f9;
        }

        /* Type Selector */
        .type-selector {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .type-option {
            position: relative;
        }

        .type-option input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .type-option label {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.5rem 1rem;
            background: rgba(255, 255, 255, 0.03);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .type-option label:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .type-option input:checked+label {
            border-color: #818cf8;
            background: rgba(129, 140, 248, 0.1);
            box-shadow: 0 10px 30px rgba(129, 140, 248, 0.2);
        }

        .type-option label i {
            font-size: 2rem;
            margin-bottom: 0.75rem;
            color: #64748b;
            transition: all 0.3s ease;
        }

        .type-option input:checked+label i {
            color: #818cf8;
            transform: scale(1.1);
        }

        .type-option label span {
            font-size: 0.95rem;
            font-weight: 600;
            color: #e2e8f0;
        }

        /* Submit Button */
        .btn-submit {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
            font-weight: 600;
            padding: 1rem 2rem;
            border-radius: 14px;
            transition: all 0.3s ease;
            width: 100%;
            font-size: 1rem;
            position: relative;
            overflow: hidden;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-submit:hover::before {
            left: 100%;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.4);
        }

        /* Complaint History */
        .history-container {
            background: linear-gradient(145deg, rgba(30, 41, 59, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            overflow: hidden;
        }

        .history-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .history-header i {
            color: #818cf8;
            font-size: 1.25rem;
        }

        .history-header h6 {
            color: var(--text-primary);
            font-weight: 600;
            margin: 0;
        }

        .complaint-item {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .complaint-item:last-child {
            border-bottom: none;
        }

        .complaint-item:hover {
            background: rgba(129, 140, 248, 0.05);
        }

        .complaint-item a {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .complaint-badge {
            font-size: 0.7rem;
            padding: 0.35em 0.75em;
            border-radius: 20px;
            font-weight: 600;
        }

        /* Contact Card */
        .contact-card {
            background: linear-gradient(145deg, rgba(30, 41, 59, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 1.5rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            margin-bottom: 0.75rem;
            transition: all 0.3s ease;
        }

        .contact-item:last-child {
            margin-bottom: 0;
        }

        .contact-item:hover {
            background: rgba(129, 140, 248, 0.1);
            transform: translateX(5px);
        }

        .contact-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .contact-icon.phone {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(22, 163, 74, 0.2));
            color: #4ade80;
        }

        .contact-icon.email {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.2));
            color: #60a5fa;
        }

        .contact-icon.location {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.2), rgba(126, 34, 206, 0.2));
            color: #c084fc;
        }

        .contact-text {
            color: #94a3b8;
            font-size: 0.9rem;
        }

        .contact-text strong {
            color: #e2e8f0;
        }

        /* WhatsApp Button */
        .whatsapp-btn {
            background: linear-gradient(135deg, #25d366, #128c7e);
            color: white;
            border: none;
            font-weight: 600;
            padding: 0.875rem 1.5rem;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
        }

        .whatsapp-btn:hover {
            background: linear-gradient(135deg, #128c7e, #075e54);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 211, 102, 0.4);
        }

        /* Alert Styles */
        .alert {
            border-radius: 12px;
            padding: 1rem 1.25rem;
            border: none;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.15), rgba(22, 163, 74, 0.15));
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(220, 38, 38, 0.15));
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        /* Section Titles */
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title i {
            color: #818cf8;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.5s ease forwards;
        }

        .delay-1 {
            animation-delay: 0.1s;
        }

        .delay-2 {
            animation-delay: 0.2s;
        }

        .delay-3 {
            animation-delay: 0.3s;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .cs-hero {
                padding: 2rem;
            }

            .hero-title {
                font-size: 2rem;
            }

            .type-selector {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .cs-hero {
                padding: 1.5rem;
                border-radius: 16px;
            }

            .hero-title {
                font-size: 1.75rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .form-container,
            .history-container,
            .contact-card {
                padding: 1.25rem;
                border-radius: 16px;
            }
        }
    </style>
@endpush
@section('content')
    <div class="cs-container py-5">
        <!-- Hero Section -->
        <div class="cs-hero animate-fade-in">
            <div class="hero-content">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h1 class="hero-title">
                            <i class="bi bi-headset me-3" style="-webkit-text-fill-color: #818cf8;"></i>
                            Customer Service
                        </h1>
                        <p class="hero-subtitle">
                            Kami siap membantu Anda! Sampaikan keluhan, laporan, atau pertanyaan Anda melalui form di bawah.
                            Tim kami akan merespons dalam waktu 1x24 jam.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                        <a href="https://wa.me/6282129939458" target="_blank" class="whatsapp-btn">
                            <i class="bi bi-whatsapp fs-5"></i>
                            Chat WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feature Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-4 animate-fade-in delay-1">
                <div class="feature-card">
                    <div class="feature-icon primary">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h6 class="text-white mb-2" style="font-weight: 600;">Respon Cepat</h6>
                    <p class="text-muted small mb-0">Tim kami siap merespons dalam 1x24 jam pada hari kerja</p>
                </div>
            </div>
            <div class="col-md-4 animate-fade-in delay-2">
                <div class="feature-card">
                    <div class="feature-icon success">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h6 class="text-white mb-2" style="font-weight: 600;">Aman & Terpercaya</h6>
                    <p class="text-muted small mb-0">Data dan privasi Anda aman bersama kami</p>
                </div>
            </div>
            <div class="col-md-4 animate-fade-in delay-3">
                <div class="feature-card">
                    <div class="feature-icon info">
                        <i class="bi bi-chat-heart"></i>
                    </div>
                    <h6 class="text-white mb-2" style="font-weight: 600;">Layanan Ramah</h6>
                    <p class="text-muted small mb-0">Kami siap membantu dengan penuh keramahan</p>
                </div>
            </div>
        </div>

        <div class="row justify-content-center g-4">
            <!-- Form Section -->
            <div class="col-lg-10 col-xl-8">
                <div class="form-container">
                    <h5 class="form-title">
                        <i class="bi bi-pencil-square"></i>
                        Form Aduan/Laporan
                    </h5>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('cs.store') }}" method="POST" id="complaintForm">
                        @csrf

                        <!-- Type Selector -->
                        <div class="type-selector">
                            <div class="type-option">
                                <input type="radio" name="type" id="type-complaint" value="complaint"
                                    {{ old('type') === 'complaint' ? 'checked' : '' }} required>
                                <label for="type-complaint">
                                    <i class="bi bi-exclamation-circle"></i>
                                    <span>Keluhan</span>
                                </label>
                            </div>
                            <div class="type-option">
                                <input type="radio" name="type" id="type-report" value="report"
                                    {{ old('type') === 'report' ? 'checked' : '' }}>
                                <label for="type-report">
                                    <i class="bi bi-flag"></i>
                                    <span>Laporan</span>
                                </label>
                            </div>
                            <div class="type-option">
                                <input type="radio" name="type" id="type-question" value="question"
                                    {{ old('type') === 'question' ? 'checked' : '' }}>
                                <label for="type-question">
                                    <i class="bi bi-question-circle"></i>
                                    <span>Pertanyaan</span>
                                </label>
                            </div>
                        </div>

                        @error('type')
                            <div class="text-danger mb-3" style="font-size: 0.875rem;">{{ $message }}</div>
                        @enderror

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="category" class="form-label">
                                    <i class="bi bi-tag me-1"></i> Kategori <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('category') is-invalid @enderror" id="category"
                                    name="category" required>
                                    <option value="">Pilih Kategori...</option>
                                    <option value="order" {{ old('category') === 'order' ? 'selected' : '' }}>📦 Pesanan
                                    </option>
                                    <option value="product" {{ old('category') === 'product' ? 'selected' : '' }}>📚 Produk
                                    </option>
                                    <option value="payment" {{ old('category') === 'payment' ? 'selected' : '' }}>💳
                                        Pembayaran</option>
                                    <option value="shipping" {{ old('category') === 'shipping' ? 'selected' : '' }}>🚚
                                        Pengiriman</option>
                                    <option value="other" {{ old('category') === 'other' ? 'selected' : '' }}>📋 Lainnya
                                    </option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="priority" class="form-label">
                                    <i class="bi bi-flag me-1"></i> Prioritas
                                </label>
                                <select class="form-select @error('priority') is-invalid @enderror" id="priority"
                                    name="priority">
                                    <option value="normal" {{ old('priority') === 'normal' ? 'selected' : '' }}>Normal
                                    </option>
                                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>Tinggi
                                    </option>
                                    <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Mendesak
                                    </option>
                                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Rendah
                                    </option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="order_number" class="form-label">
                                    <i class="bi bi-receipt me-1"></i> No. Pesanan (Jika terkait pesanan)
                                </label>
                                <input type="text" class="form-control @error('order_number') is-invalid @enderror"
                                    id="order_number" name="order_number" placeholder="Contoh: ORD-2024-001"
                                    value="{{ old('order_number') }}">
                                @error('order_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="subject" class="form-label">
                                    <i class="bi bi-card-heading me-1"></i> Subjek <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('subject') is-invalid @enderror"
                                    id="subject" name="subject" placeholder="Ringkasan masalah Anda"
                                    value="{{ old('subject') }}" required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="message" class="form-label">
                                    <i class="bi bi-chat-text me-1"></i> Pesan Detail <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5"
                                    placeholder="Jelaskan masalah Anda secara detail..." required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="text-muted small mt-2">
                                    <i class="bi bi-info-circle me-1"></i> Minimal 10 karakter
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4 w-100" style="padding: 1rem 2rem; border-radius: 14px; font-weight: 600;">
                            <i class="bi bi-send me-2"></i>Kirim Aduan/Laporan
                        </button>
                    </form>
                </div>
            </div>

            <!-- History Section Below Form -->
            <div class="col-lg-10 col-xl-8 mt-5">
                <!-- Complaint History -->
                <div class="history-container mb-4">
                    <div class="history-header">
                        <i class="bi bi-clock-history"></i>
                        <h6 class="mb-0">Riwayat Aduan Saya</h6>
                    </div>

                    @forelse($complaints as $complaint)
                        <a href="{{ route('cs.show', $complaint) }}" class="complaint-item d-block">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex gap-1 flex-wrap">
                                    <span
                                        class="badge bg-{{ $complaint->status === 'pending' ? 'warning' : ($complaint->status === 'resolved' ? 'success' : 'info') }} complaint-badge">
                                        {{ $complaint->status_name }}
                                    </span>
                                    <span
                                        class="badge bg-{{ $complaint->priority === 'urgent' ? 'danger' : 'secondary' }} complaint-badge">
                                        {{ $complaint->priority_name }}
                                    </span>
                                </div>
                                <small class="text-muted">{{ $complaint->created_at->diffForHumans() }}</small>
                            </div>
                            <h6 class="text-white mb-1" style="font-size: 0.95rem; font-weight: 600;">
                                {{ $complaint->subject }}</h6>
                            <p class="text-muted small mb-0 text-truncate">{{ $complaint->message }}</p>
                        </a>
                    @empty
                        <div class="p-4 text-center">
                            <i class="bi bi-inbox display-4 text-muted" style="opacity: 0.5;"></i>
                            <p class="text-muted mt-3 mb-0">Belum ada riwayat aduan</p>
                            <p class="text-muted small">Kirim aduan pertama Anda sekarang!</p>
                        </div>
                    @endforelse

                    @if ($complaints->hasPages())
                        <div class="p-3 border-top" style="border-color: var(--border-color) !important;">
                            {{ $complaints->links() }}
                        </div>
                    @endif
                </div>

                <!-- Contact Info -->
                <div class="contact-card">
                    <h6 class="section-title">
                        <i class="bi bi-telephone"></i>
                        Kontak Lainnya
                    </h6>

                    <div class="contact-item">
                        <div class="contact-icon phone">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div class="contact-text">
                            <strong>0821-2993-9458</strong>
                            <div class="small">WhatsApp Only</div>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon email">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="contact-text">
                            <strong>cs@tokobuku.com</strong>
                            <div class="small">Email Customer Service</div>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon location">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div class="contact-text">
                            <strong>Rancamanyar, Bandung</strong>
                            <div class="small">Alamat Toko</div>
                        </div>
                    </div>

                    <hr style="border-color: var(--border-color); margin: 1.5rem 0;">

                    <div class="text-center">
                        <p class="text-muted small mb-3">Jam Operasional</p>
                        <div class="d-flex justify-content-between text-secondary small">
                            <span>Senin - Jumat</span>
                            <span>08:00 - 17:00</span>
                        </div>
                        <div class="d-flex justify-content-between text-secondary small mt-1">
                            <span>Sabtu</span>
                            <span>08:00 - 12:00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
