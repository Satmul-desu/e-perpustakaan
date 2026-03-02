{{-- ================================================
     FILE: resources/views/cs/index.blade.php
     FUNGSI: Halaman Customer Service (CS) dengan form Aduan/Laporan
     ================================================ --}}

@extends('layouts.app')

@section('title', 'Customer Service - TokoBuku')

@push('styles')
<style>
    .cs-container {
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .cs-header {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .cs-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .cs-header h1 {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .cs-header p {
        color: #94a3b8;
        margin-bottom: 0;
    }
    
    .cs-card {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 16px;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .cs-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
    }
    
    .cs-card-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .cs-card-icon.primary {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(139, 92, 246, 0.2));
        color: #818cf8;
    }
    
    .cs-card-icon.success {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(22, 163, 74, 0.2));
        color: #4ade80;
    }
    
    .cs-card-icon.warning {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(234, 88, 12, 0.2));
        color: #fbbf24;
    }
    
    .cs-card-icon.danger {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.2));
        color: #f87171;
    }
    
    .form-card {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 16px;
        padding: 2rem;
    }
    
    .form-label {
        font-weight: 500;
        color: #e2e8f0;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid #334155;
        color: #f1f5f9;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
    }
    
    .form-control:focus, .form-select:focus {
        background: rgba(255, 255, 255, 0.1);
        border-color: #6366f1;
        color: #f1f5f9;
        box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
    }
    
    .form-control::placeholder {
        color: #64748b;
    }
    
    .form-control option, .form-select option {
        background: #1e293b;
        color: #f1f5f9;
    }
    
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
        padding: 1.25rem;
        background: rgba(255, 255, 255, 0.03);
        border: 2px solid #334155;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .type-option label:hover {
        background: rgba(255, 255, 255, 0.05);
        border-color: #475569;
    }
    
    .type-option input:checked + label {
        border-color: #6366f1;
        background: rgba(99, 102, 241, 0.1);
    }
    
    .type-option label i {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        color: #94a3b8;
    }
    
    .type-option input:checked + label i {
        color: #6366f1;
    }
    
    .type-option label span {
        font-size: 0.875rem;
        font-weight: 500;
        color: #e2e8f0;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        border: none;
        font-weight: 600;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        transition: all 0.2s ease;
        width: 100%;
    }
    
    .btn-submit:hover {
        background: linear-gradient(135deg, #4f46e5, #4338ca);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
    }
    
    .complaint-history {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 16px;
        overflow: hidden;
    }
    
    .complaint-item {
        padding: 1.25rem;
        border-bottom: 1px solid #334155;
        transition: background 0.2s ease;
    }
    
    .complaint-item:last-child {
        border-bottom: none;
    }
    
    .complaint-item:hover {
        background: rgba(255, 255, 255, 0.02);
    }
    
    .complaint-item a {
        text-decoration: none;
        color: inherit;
    }
    
    .complaint-badge {
        font-size: 0.7rem;
        padding: 0.35em 0.75em;
        border-radius: 20px;
        font-weight: 600;
    }
    
    .whatsapp-btn {
        background: #25d366;
        color: white;
        border: none;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .whatsapp-btn:hover {
        background: #128c7e;
        color: white;
        transform: translateY(-1px);
    }
    
    @media (max-width: 768px) {
        .type-selector {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="cs-container py-5">
    {{-- Header --}}
    <div class="cs-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="text-white">Customer Service</h1>
                <p class="mb-0">Kami siap membantu Anda! Gunakan form di bawah untuk menyampaikan keluhan, laporan, atau pertanyaan.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="https://wa.me/6282129939458" target="_blank" class="whatsapp-btn">
                    <i class="bi bi-whatsapp"></i>
                    Chat WhatsApp
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Form Aduan/Laporan --}}
        <div class="col-lg-8">
            <div class="form-card">
                <h5 class="text-white mb-4">
                    <i class="bi bi-pencil-square me-2" style="color: #6366f1;"></i>
                    Form Aduan/Laporan
                </h5>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('cs.store') }}" method="POST" id="complaintForm">
                    @csrf

                    {{-- Tipe Aduan --}}
                    <div class="type-selector">
                        <div class="type-option">
                            <input type="radio" name="type" id="type-complaint" value="complaint" {{ old('type') === 'complaint' ? 'checked' : '' }} required>
                            <label for="type-complaint">
                                <i class="bi bi-exclamation-circle"></i>
                                <span>Keluhan</span>
                            </label>
                        </div>
                        <div class="type-option">
                            <input type="radio" name="type" id="type-report" value="report" {{ old('type') === 'report' ? 'checked' : '' }}>
                            <label for="type-report">
                                <i class="bi bi-flag"></i>
                                <span>Laporan</span>
                            </label>
                        </div>
                        <div class="type-option">
                            <input type="radio" name="type" id="type-question" value="question" {{ old('type') === 'question' ? 'checked' : '' }}>
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
                        {{-- Kategori --}}
                        <div class="col-md-6">
                            <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                <option value="">Pilih Kategori...</option>
                                <option value="order" {{ old('category') === 'order' ? 'selected' : '' }}>Pesanan</option>
                                <option value="product" {{ old('category') === 'product' ? 'selected' : '' }}>Produk</option>
                                <option value="payment" {{ old('category') === 'payment' ? 'selected' : '' }}>Pembayaran</option>
                                <option value="shipping" {{ old('category') === 'shipping' ? 'selected' : '' }}>Pengiriman</option>
                                <option value="other" {{ old('category') === 'other' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Prioritas --}}
                        <div class="col-md-6">
                            <label for="priority" class="form-label">Prioritas</label>
                            <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority">
                                <option value="normal" {{ old('priority') === 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>Tinggi</option>
                                <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Mendesak</option>
                                <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Rendah</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- No. Pesanan (Opsional) --}}
                        <div class="col-12">
                            <label for="order_number" class="form-label">No. Pesanan (Jika terkait pesanan)</label>
                            <input type="text" class="form-control @error('order_number') is-invalid @enderror" 
                                   id="order_number" name="order_number" 
                                   placeholder="Contoh: ORD-2024-001"
                                   value="{{ old('order_number') }}">
                            @error('order_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Subjek --}}
                        <div class="col-12">
                            <label for="subject" class="form-label">Subjek <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                   id="subject" name="subject" 
                                   placeholder="Ringkasan masalah Anda"
                                   value="{{ old('subject') }}" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Pesan --}}
                        <div class="col-12">
                            <label for="message" class="form-label">Pesan Detail <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" name="message" rows="5" 
                                      placeholder="Jelaskan masalah Anda secara detail..."
                                      required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="text-muted small mt-1">Minimal 10 karakter</div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-submit mt-4">
                        <i class="bi bi-send me-2"></i>Kirim Aduan/Laporan
                    </button>
                </form>
            </div>
        </div>

        {{-- Informasi & Riwayat --}}
        <div class="col-lg-4">
            {{-- Info Cards --}}
            <div class="row g-3 mb-4">
                <div class="col-6">
                    <div class="cs-card p-3">
                        <div class="cs-card-icon primary">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <h6 class="text-white mb-1" style="font-size: 0.9rem;">Respon Cepat</h6>
                        <p class="text-muted small mb-0">Kami merespons dalam 1x24 jam</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="cs-card p-3">
                        <div class="cs-card-icon success">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h6 class="text-white mb-1" style="font-size: 0.9rem;">Aman & Privasi</h6>
                        <p class="text-muted small mb-0">Data Anda aman bersama kami</p>
                    </div>
                </div>
            </div>

            {{-- Riwayat Aduan --}}
            <div class="complaint-history">
                <div class="p-3 border-bottom" style="border-color: #334155;">
                    <h6 class="text-white mb-0">
                        <i class="bi bi-clock-history me-2" style="color: #6366f1;"></i>
                        Riwayat Aduan Saya
                    </h6>
                </div>
                
                @forelse($complaints as $complaint)
                    <a href="{{ route('cs.show', $complaint) }}" class="complaint-item d-block">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge bg-{{ $complaint->status === 'pending' ? 'warning' : ($complaint->status === 'resolved' ? 'success' : 'info') }} complaint-badge">
                                    {{ $complaint->status_name }}
                                </span>
                                <span class="badge bg-{{ $complaint->priority === 'urgent' ? 'danger' : 'secondary' }} complaint-badge ms-1">
                                    {{ $complaint->priority_name }}
                                </span>
                            </div>
                            <small class="text-muted">{{ $complaint->created_at->diffForHumans() }}</small>
                        </div>
                        <h6 class="text-white mb-1" style="font-size: 0.9rem;">{{ $complaint->subject }}</h6>
                        <p class="text-muted small mb-0 text-truncate">{{ $complaint->message }}</p>
                    </a>
                @empty
                    <div class="p-4 text-center">
                        <i class="bi bi-inbox display-4 text-muted"></i>
                        <p class="text-muted mt-2 mb-0">Belum ada riwayat aduan</p>
                    </div>
                @endforelse
                
                @if($complaints->hasPages())
                    <div class="p-3 border-top" style="border-color: #334155;">
                        {{ $complaints->links() }}
                    </div>
                @endif
            </div>

            {{-- Kontak Lainnya --}}
            <div class="cs-card p-3 mt-4">
                <h6 class="text-white mb-3" style="font-size: 0.9rem;">
                    <i class="bi bi-telephone me-2" style="color: #6366f1;"></i>
                    Kontak Lainnya
                </h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="bi bi-telephone me-2 text-muted"></i>
                        <span class="text-secondary">0821-2993-9458</span>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-envelope me-2 text-muted"></i>
                        <span class="text-secondary">cs@tokobuku.com</span>
                    </li>
                    <li>
                        <i class="bi bi-geo-alt me-2 text-muted"></i>
                        <span class="text-secondary">Rancamanyar, Bandung</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

