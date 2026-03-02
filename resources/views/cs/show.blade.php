{{-- ================================================
     FILE: resources/views/cs/show.blade.php
     FUNGSI: Detail Aduan untuk User
     ================================================ --}}

@extends('layouts.app')

@section('title', 'Detail Aduan #' . $complaint->id . ' - TokoBuku')

@push('styles')
<style>
    .detail-card {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 16px;
        overflow: hidden;
    }
    
    .detail-header {
        padding: 1.5rem;
        border-bottom: 1px solid #334155;
    }
    
    .detail-body {
        padding: 1.5rem;
    }
    
    .message-bubble {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid #334155;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1rem;
    }
    
    .message-bubble.admin {
        background: rgba(34, 197, 94, 0.1);
        border-color: rgba(34, 197, 94, 0.3);
    }
    
    .info-label {
        font-size: 0.8rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    
    .info-value {
        color: #e2e8f0;
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<div class="container py-5" style="max-width: 800px;">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cs.index') }}" class="text-decoration-none">Customer Service</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Aduan</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h4 class="text-white mb-1">Aduan #{{ $complaint->id }}</h4>
            <p class="text-muted mb-0">{{ $complaint->subject }}</p>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-{{ $complaint->status === 'pending' ? 'warning' : ($complaint->status === 'resolved' ? 'success' : 'info') }} fs-6">
                {{ $complaint->status_name }}
            </span>
            <span class="badge bg-{{ $complaint->priority === 'urgent' ? 'danger' : 'secondary' }} fs-6">
                {{ $complaint->priority_name }}
            </span>
        </div>
    </div>

    <div class="row g-4">
        {{-- Detail Aduan --}}
        <div class="col-md-8">
            <div class="detail-card">
                <div class="detail-header">
                    <h6 class="text-white mb-0">
                        <i class="bi bi-chat-dots me-2" style="color: #6366f1;"></i>
                        Percakapan
                    </h6>
                </div>
                <div class="detail-body">
                    {{-- Pesan User --}}
                    <div class="message-bubble">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <div class="user-avatar-sm me-2" style="width: 32px; height: 32px; border-radius: 8px; background: linear-gradient(135deg, #6366f1, #8b5cf6); display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700; color: white;">
                                    {{ substr($complaint->user->name, 0, 2) }}
                                </div>
                                <span class="text-white fw-medium">{{ $complaint->user->name }}</span>
                            </div>
                            <small class="text-muted">{{ $complaint->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <p class="text-secondary mb-0" style="white-space: pre-wrap;">{{ $complaint->message }}</p>
                    </div>

                    {{-- Pesan Admin (Jika ada) --}}
                    @if($complaint->admin_response)
                        <div class="message-bubble admin">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm me-2" style="width: 32px; height: 32px; border-radius: 8px; background: linear-gradient(135deg, #22c55e, #16a34a); display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700; color: white;">
                                        {{ substr($complaint->responder?->name ?? 'Admin', 0, 2) }}
                                    </div>
                                    <span class="text-white fw-medium">{{ $complaint->responder?->name ?? 'Admin' }}</span>
                                </div>
                                <small class="text-muted">{{ $complaint->responded_at?->format('d/m/Y H:i') }}</small>
                            </div>
                            <p class="text-secondary mb-0" style="white-space: pre-wrap;">{{ $complaint->admin_response }}</p>
                        </div>
                    @endif

                    {{-- Info jika aduan belum direspons --}}
                    @if(!$complaint->admin_response && $complaint->status === 'pending')
                        <div class="text-center py-4">
                            <i class="bi bi-clock display-4 text-muted"></i>
                            <p class="text-muted mt-2 mb-0">Tim kami sedang meninjau aduan Anda...</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Info Sidebar --}}
        <div class="col-md-4">
            <div class="detail-card">
                <div class="detail-header">
                    <h6 class="text-white mb-0">
                        <i class="bi bi-info-circle me-2" style="color: #6366f1;"></i>
                        Informasi
                    </h6>
                </div>
                <div class="detail-body">
                    <div class="mb-3">
                        <div class="info-label">Jenis</div>
                        <div class="info-value">
                            @if($complaint->type === 'complaint')
                                <i class="bi bi-exclamation-circle text-warning me-1"></i> Keluhan
                            @elseif($complaint->type === 'report')
                                <i class="bi bi-flag text-danger me-1"></i> Laporan
                            @else
                                <i class="bi bi-question-circle text-info me-1"></i> Pertanyaan
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="info-label">Kategori</div>
                        <div class="info-value">{{ $complaint->category_name }}</div>
                    </div>

                    @if($complaint->order_number)
                        <div class="mb-3">
                            <div class="info-label">No. Pesanan</div>
                            <div class="info-value">{{ $complaint->order_number }}</div>
                        </div>
                    @endif

                    <div class="mb-3">
                        <div class="info-label">Diajukan</div>
                        <div class="info-value">{{ $complaint->created_at->format('d F Y, H:i') }}</div>
                    </div>

                    @if($complaint->responded_at)
                        <div class="mb-3">
                            <div class="info-label">Direspons</div>
                            <div class="info-value">{{ $complaint->responded_at->format('d F Y, H:i') }}</div>
                        </div>
                    @endif

                    <hr style="border-color: #334155;">

                    <a href="{{ route('cs.index') }}" class="btn btn-outline-primary w-100">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke CS
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

