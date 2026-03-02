{{-- ================================================
     FILE: resources/views/admin/complaints/show.blade.php
     FUNGSI: Detail Aduan untuk Admin dengan fitur Reply
     ================================================ --}}

@extends('layouts.admin')

@section('title', 'Detail Aduan #' . $complaint->id . ' - TokoBuku Admin')
@section('page-title', 'Detail Aduan')

@section('content')
{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.complaints.index') }}">Aduan</a></li>
        <li class="breadcrumb-item active">#{{ $complaint->id }}</li>
    </ol>
</nav>

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

<div class="row g-4">
    {{-- Detail Aduan --}}
    <div class="col-lg-8">
        {{-- User Message --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <span class="badge bg-{{ $complaint->type === 'complaint' ? 'warning' : ($complaint->type === 'report' ? 'danger' : 'info') }} me-2">
                        {{ ucfirst($complaint->type) }}
                    </span>
                    <span class="text-white fw-medium">{{ $complaint->subject }}</span>
                </div>
                <small class="text-muted">{{ $complaint->created_at->format('d F Y, H:i') }}</small>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-start mb-3">
                    <div class="user-avatar-sm me-3" style="width: 44px; height: 44px; border-radius: 10px; background: linear-gradient(135deg, #6366f1, #8b5cf6); display: flex; align-items: center; justify-content: center; font-weight: 700; color: white; flex-shrink: 0;">
                        {{ substr($complaint->user->name, 0, 2) }}
                    </div>
                    <div>
                        <h6 class="text-white mb-0">{{ $complaint->user->name }}</h6>
                        <small class="text-muted">{{ $complaint->user->email }}</small>
                    </div>
                </div>
                <div class="p-3 rounded" style="background: rgba(255, 255, 255, 0.05); border: 1px solid #334155;">
                    <p class="text-secondary mb-0" style="white-space: pre-wrap;">{{ $complaint->message }}</p>
                </div>
                @if($complaint->order_number)
                    <div class="mt-2">
                        <span class="badge bg-secondary">No. Pesanan: {{ $complaint->order_number }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Admin Response --}}
        @if($complaint->admin_response)
            <div class="card mb-4 border-success">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-check-circle me-2"></i>
                        Tanggapan Admin
                    </div>
                    <small>{{ $complaint->responded_at?->format('d F Y, H:i') }}</small>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start mb-3">
                        <div class="user-avatar-sm me-3" style="width: 44px; height: 44px; border-radius: 10px; background: linear-gradient(135deg, #22c55e, #16a34a); display: flex; align-items: center; justify-content: center; font-weight: 700; color: white; flex-shrink: 0;">
                            {{ substr($complaint->responder?->name ?? 'Admin', 0, 2) }}
                        </div>
                        <div>
                            <h6 class="text-white mb-0">{{ $complaint->responder?->name ?? 'Admin' }}</h6>
                            <small class="text-muted">Administrator</small>
                        </div>
                    </div>
                    <div class="p-3 rounded" style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3);">
                        <p class="text-secondary mb-0" style="white-space: pre-wrap;">{{ $complaint->admin_response }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Response Form --}}
        <div class="card">
            <div class="card-header">
                <i class="bi bi-reply me-2"></i>
                {{ $complaint->admin_response ? 'Edit Tanggapan' : 'Berikan Tanggapan' }}
            </div>
            <div class="card-body">
                <form action="{{ route('admin.complaints.update', $complaint) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="pending" {{ $complaint->status === 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="in_progress" {{ $complaint->status === 'in_progress' ? 'selected' : '' }}>Diproses</option>
                                <option value="resolved" {{ $complaint->status === 'resolved' ? 'selected' : '' }}>Selesai</option>
                                <option value="closed" {{ $complaint->status === 'closed' ? 'selected' : 'closed' }}>Ditutup</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Prioritas</label>
                            <select name="priority" class="form-select @error('priority') is-invalid @enderror">
                                <option value="low" {{ $complaint->priority === 'low' ? 'selected' : '' }}>Rendah</option>
                                <option value="normal" {{ $complaint->priority === 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="high" {{ $complaint->priority === 'high' ? 'selected' : '' }}>Tinggi</option>
                                <option value="urgent" {{ $complaint->priority === 'urgent' ? 'selected' : '' }}>Mendesak</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggapan <span class="text-danger">*</span></label>
                        <textarea name="admin_response" class="form-control @error('admin_response') is-invalid @enderror" 
                                  rows="5" placeholder="Berikan tanggapan untuk pengguna..." required>{{ old('admin_response', $complaint->admin_response) }}</textarea>
                        @error('admin_response')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="form-text">Minimal 10 karakter</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ $complaint->admin_response ? 'Perbarui Tanggapan' : 'Kirim Tanggapan' }}
                        </button>
                        <a href="{{ route('admin.complaints.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Info Sidebar --}}
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-info-circle me-2"></i>Informasi Aduan
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted small">Kategori</div>
                    <div class="text-white fw-medium">{{ $complaint->category_name }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted small">Status Saat Ini</div>
                    <span class="badge bg-{{ $complaint->status === 'pending' ? 'warning' : ($complaint->status === 'resolved' ? 'success' : 'info') }}">
                        {{ $complaint->status_name }}
                    </span>
                </div>
                <div class="mb-3">
                    <div class="text-muted small">Prioritas</div>
                    <span class="badge bg-{{ $complaint->priority === 'urgent' ? 'danger' : ($complaint->priority === 'high' ? 'warning' : 'secondary') }}">
                        {{ $complaint->priority_name }}
                    </span>
                </div>
                <div class="mb-3">
                    <div class="text-muted small">Diajukan</div>
                    <div class="text-white">{{ $complaint->created_at->format('d F Y, H:i') }}</div>
                </div>
                @if($complaint->responded_at)
                    <div class="mb-3">
                        <div class="text-muted small">Terakhir Direspons</div>
                        <div class="text-white">{{ $complaint->responded_at->format('d F Y, H:i') }}</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-lightning me-2"></i>Aksi Cepat
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <form action="{{ route('admin.complaints.quick-update', $complaint) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="in_progress">
                        <button type="submit" class="btn btn-outline-info w-100">
                            <i class="bi bi-gear me-2"></i>Tandai Diproses
                        </button>
                    </form>
                    <form action="{{ route('admin.complaints.quick-update', $complaint) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="resolved">
                        <button type="submit" class="btn btn-outline-success w-100">
                            <i class="bi bi-check-circle me-2"></i>Tandai Selesai
                        </button>
                    </form>
                    <hr style="border-color: #334155;">
                    <form action="{{ route('admin.complaints.destroy', $complaint) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus aduan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash me-2"></i>Hapus Aduan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- User Info --}}
        <div class="card">
            <div class="card-header">
                <i class="bi bi-person me-2"></i>Info Pengguna
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="user-avatar-sm me-3" style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #6366f1, #8b5cf6); display: flex; align-items: center; justify-content: center; font-weight: 700; color: white; font-size: 1rem;">
                        {{ substr($complaint->user->name, 0, 2) }}
                    </div>
                    <div>
                        <h6 class="text-white mb-0">{{ $complaint->user->name }}</h6>
                        <small class="text-muted">{{ $complaint->user->email }}</small>
                    </div>
                </div>
                <div class="small">
                    <div class="d-flex justify-content-between py-2 border-bottom" style="border-color: #334155;">
                        <span class="text-muted">Total Pesanan</span>
                        <span class="text-white">{{ $complaint->user->orders()->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom" style="border-color: #334155;">
                        <span class="text-muted">Bergabung</span>
                        <span class="text-white">{{ $complaint->user->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted">Total Aduan</span>
                        <span class="text-white">{{ $complaint->user->complaints()->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card.border-success {
        border-color: rgba(34, 197, 94, 0.3) !important;
    }
</style>
@endpush

