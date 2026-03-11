@extends('layouts.app')
@section('title', 'Detail Aduan #' . $complaint->id . ' - TokoBuku')
@push('styles')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --dark-bg: rgba(30, 41, 59, 0.95);
            --glass-bg: rgba(255, 255, 255, 0.05);
            --border-color: rgba(255, 255, 255, 0.1);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --accent-color: #818cf8;
        }

        .detail-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        /* Breadcrumb */
        .breadcrumb-custom {
            background: rgba(30, 41, 59, 0.6);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .breadcrumb-item a {
            color: #818cf8;
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb-item a:hover {
            color: #a5b4fc;
        }

        .breadcrumb-item.active {
            color: #94a3b8;
        }

        /* Header Card */
        .detail-header-card {
            background: linear-gradient(145deg, rgba(30, 41, 59, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 1.5rem 2rem;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .detail-header-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #818cf8, #c084fc);
        }

        .header-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .header-subtitle {
            color: #94a3b8;
            margin-bottom: 0;
        }

        /* Badges */
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .priority-badge {
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        /* Message Bubbles */
        .message-card {
            background: linear-gradient(145deg, rgba(30, 41, 59, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            overflow: hidden;
        }

        .message-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .message-header i {
            color: #818cf8;
            font-size: 1.25rem;
        }

        .message-header h6 {
            color: var(--text-primary);
            font-weight: 600;
            margin: 0;
        }

        .message-body {
            padding: 1.5rem;
        }

        .message-bubble {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            position: relative;
            transition: all 0.3s ease;
        }

        .message-bubble:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .message-bubble:last-child {
            margin-bottom: 0;
        }

        .message-bubble.user {
            border-left: 3px solid #818cf8;
        }

        .message-bubble.admin {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.08), rgba(22, 163, 74, 0.08));
            border-left: 3px solid #22c55e;
        }

        .user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            color: white;
        }

        .user-avatar.user {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
        }

        .user-avatar.admin {
            background: linear-gradient(135deg, #22c55e, #16a34a);
        }

        .message-meta {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .message-author {
            color: var(--text-primary);
            font-weight: 600;
        }

        .message-time {
            color: #64748b;
            font-size: 0.85rem;
        }

        .message-content {
            color: #cbd5e1;
            line-height: 1.7;
            white-space: pre-wrap;
        }

        /* Waiting State */
        .waiting-state {
            text-align: center;
            padding: 3rem 1.5rem;
        }

        .waiting-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .waiting-icon i {
            font-size: 2.5rem;
            color: #818cf8;
        }

        .waiting-title {
            color: var(--text-primary);
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .waiting-text {
            color: #64748b;
        }

        /* Info Card */
        .info-card {
            background: linear-gradient(145deg, rgba(30, 41, 59, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            overflow: hidden;
            height: 100%;
        }

        .info-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .info-header i {
            color: #818cf8;
            font-size: 1.25rem;
        }

        .info-header h6 {
            color: var(--text-primary);
            font-weight: 600;
            margin: 0;
        }

        .info-body {
            padding: 1.5rem;
        }

        .info-item {
            margin-bottom: 1.25rem;
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-size: 0.75rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.35rem;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .info-label i {
            font-size: 0.85rem;
            color: #818cf8;
        }

        .info-value {
            color: #e2e8f0;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .info-value .badge {
            font-size: 0.8rem;
            padding: 0.4em 0.8em;
        }

        .divider {
            height: 1px;
            background: var(--border-color);
            margin: 1.5rem 0;
        }

        /* Back Button */
        .btn-back {
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.3);
            color: #818cf8;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-back:hover {
            background: rgba(99, 102, 241, 0.2);
            border-color: rgba(99, 102, 241, 0.5);
            color: #a5b4fc;
            transform: translateX(-3px);
        }

        /* Timeline */
        .status-timeline {
            margin-top: 1.5rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 12px;
        }

        .timeline-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.5rem 0;
        }

        .timeline-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #334155;
            flex-shrink: 0;
        }

        .timeline-dot.active {
            background: #818cf8;
            box-shadow: 0 0 10px rgba(129, 140, 248, 0.5);
        }

        .timeline-dot.completed {
            background: #22c55e;
            box-shadow: 0 0 10px rgba(34, 197, 94, 0.5);
        }

        .timeline-text {
            color: #64748b;
            font-size: 0.85rem;
        }

        .timeline-text.active {
            color: #e2e8f0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .detail-header-card {
                padding: 1.25rem;
            }

            .header-title {
                font-size: 1.25rem;
            }

            .message-body {
                padding: 1rem;
            }

            .message-bubble {
                padding: 1rem;
            }

            .info-body {
                padding: 1rem;
            }
        }
    </style>
@endpush
@section('content')
    <div class="detail-container py-5">
        <!-- Breadcrumb -->
        <nav class="breadcrumb-custom">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">
                        <i class="bi bi-house-door me-1"></i> Home
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('cs.index') }}">Customer Service</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Detail Aduan</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="detail-header-card">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h4 class="header-title">
                        <i class="bi bi-ticket-detailed me-2" style="color: #818cf8;"></i>
                        Aduan #{{ $complaint->id }}
                    </h4>
                    <p class="header-subtitle">{{ $complaint->subject }}</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <span
                        class="status-badge bg-{{ $complaint->status === 'pending' ? 'warning' : ($complaint->status === 'resolved' ? 'success' : 'info') }}">
                        <i
                            class="bi bi-{{ $complaint->status === 'pending' ? 'clock' : ($complaint->status === 'resolved' ? 'check-circle' : 'arrow-repeat') }} me-1"></i>
                        {{ $complaint->status_name }}
                    </span>
                    <span
                        class="priority-badge bg-{{ $complaint->priority === 'urgent' ? 'danger' : ($complaint->priority === 'high' ? 'warning' : 'secondary') }}">
                        <i class="bi bi-flag me-1"></i>
                        {{ $complaint->priority_name }}
                    </span>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Messages Section -->
            <div class="col-lg-8">
                <div class="message-card">
                    <div class="message-header">
                        <i class="bi bi-chat-dots"></i>
                        <h6 class="mb-0">Percakapan</h6>
                    </div>
                    <div class="message-body">
                        <!-- User Message -->
                        <div class="message-bubble user">
                            <div class="message-meta">
                                <div class="user-avatar user">
                                    {{ substr($complaint->user->name, 0, 2) }}
                                </div>
                                <div>
                                    <span class="message-author">{{ $complaint->user->name }}</span>
                                    <span class="message-time">• {{ $complaint->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                            <p class="message-content mb-0">{{ $complaint->message }}</p>
                        </div>

                        <!-- Admin Response -->
                        @if ($complaint->admin_response)
                            <div class="message-bubble admin">
                                <div class="message-meta">
                                    <div class="user-avatar admin">
                                        {{ substr($complaint->responder?->name ?? 'Admin', 0, 2) }}
                                    </div>
                                    <div>
                                        <span class="message-author">{{ $complaint->responder?->name ?? 'Admin' }}</span>
                                        <span class="message-time">•
                                            {{ $complaint->responded_at?->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                                <p class="message-content mb-0">{{ $complaint->admin_response }}</p>
                            </div>
                        @endif

                        <!-- Waiting State -->
                        @if (!$complaint->admin_response && $complaint->status === 'pending')
                            <div class="waiting-state">
                                <div class="waiting-icon">
                                    <i class="bi bi-hourglass-split"></i>
                                </div>
                                <h6 class="waiting-title">Menunggu Respons</h6>
                                <p class="waiting-text">Tim kami sedang meninjau aduan Anda. Mohon tunggu sebentar...</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Info Sidebar -->
            <div class="col-lg-4">
                <div class="info-card">
                    <div class="info-header">
                        <i class="bi bi-info-circle"></i>
                        <h6 class="mb-0">Informasi Aduan</h6>
                    </div>
                    <div class="info-body">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-tag"></i> Jenis
                            </div>
                            <div class="info-value">
                                @if ($complaint->type === 'complaint')
                                    <span class="badge bg-warning">
                                        <i class="bi bi-exclamation-circle me-1"></i> Keluhan
                                    </span>
                                @elseif($complaint->type === 'report')
                                    <span class="badge bg-danger">
                                        <i class="bi bi-flag me-1"></i> Laporan
                                    </span>
                                @else
                                    <span class="badge bg-info">
                                        <i class="bi bi-question-circle me-1"></i> Pertanyaan
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-folder"></i> Kategori
                            </div>
                            <div class="info-value">{{ $complaint->category_name }}</div>
                        </div>

                        @if ($complaint->order_number)
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-receipt"></i> No. Pesanan
                                </div>
                                <div class="info-value">
                                    <code
                                        style="background: rgba(129, 140, 248, 0.1); padding: 0.25rem 0.5rem; border-radius: 6px; color: #818cf8;">
                                        {{ $complaint->order_number }}
                                    </code>
                                </div>
                            </div>
                        @endif

                        <div class="divider"></div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-calendar-plus"></i> Diajukan
                            </div>
                            <div class="info-value">{{ $complaint->created_at->format('d F Y, H:i') }}</div>
                        </div>

                        @if ($complaint->responded_at)
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-calendar-check"></i> Direspons
                                </div>
                                <div class="info-value">{{ $complaint->responded_at->format('d F Y, H:i') }}</div>
                            </div>
                        @endif

                        <!-- Status Timeline -->
                        <div class="status-timeline">
                            <div class="timeline-item">
                                <div class="timeline-dot completed"></div>
                                <span class="timeline-text active">Aduan Dibuat</span>
                            </div>
                            <div class="timeline-item">
                                <div
                                    class="timeline-dot {{ in_array($complaint->status, ['processing', 'resolved']) ? 'completed' : ($complaint->status === 'pending' ? 'active' : '') }}">
                                </div>
                                <span
                                    class="timeline-text {{ $complaint->status !== 'pending' ? 'active' : '' }}">Diproses</span>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-dot {{ $complaint->status === 'resolved' ? 'completed' : '' }}"></div>
                                <span
                                    class="timeline-text {{ $complaint->status === 'resolved' ? 'active' : '' }}">Selesai</span>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <a href="{{ route('cs.index') }}" class="btn btn-back w-100 justify-content-center">
                            <i class="bi bi-arrow-left"></i>
                            Kembali ke CS
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
