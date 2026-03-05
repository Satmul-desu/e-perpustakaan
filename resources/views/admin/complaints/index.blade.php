{{-- ================================================
     FILE: resources/views/admin/complaints/index.blade.php
     FUNGSI: Halaman list aduan untuk admin
     ================================================ --}}

@extends('layouts.admin')

@section('title', 'Manajemen Aduan - TokoBuku Admin')
@section('page-title', 'Manajemen Aduan')

@section('content')
{{-- Stats Cards - Full Width --}}
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="bi bi-clock"></i>
            </div>
            <div class="stat-label">Menunggu</div>
            <div class="stat-value">{{ $stats['pending'] }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon info">
                <i class="bi bi-gear"></i>
            </div>
            <div class="stat-label">Diproses</div>
            <div class="stat-value">{{ $stats['in_progress'] }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-label">Selesai</div>
            <div class="stat-value">{{ $stats['resolved'] }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon danger">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="stat-label">Mendesak</div>
            <div class="stat-value">{{ $stats['urgent'] }}</div>
        </div>
    </div>
</div>

{{-- Filter & Search --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.complaints.index') }}" class="row g-3">
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Diproses</option>
                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Selesai</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Ditutup</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="type" class="form-select" onchange="this.form.submit()">
                    <option value="all" {{ request('type', 'all') === 'all' ? 'selected' : '' }}>Semua Jenis</option>
                    <option value="complaint" {{ request('type') === 'complaint' ? 'selected' : '' }}>Keluhan</option>
                    <option value="report" {{ request('type') === 'report' ? 'selected' : '' }}>Laporan</option>
                    <option value="question" {{ request('type') === 'question' ? 'selected' : '' }}>Pertanyaan</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="priority" class="form-select" onchange="this.form.submit()">
                    <option value="all" {{ request('priority', 'all') === 'all' ? 'selected' : '' }}>Semua Prioritas</option>
                    <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Mendesak</option>
                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>Tinggi</option>
                    <option value="normal" {{ request('priority') === 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Rendah</option>
                </select>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari subjek, pesan, user..." value="{{ request('search') }}">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-1">
                <a href="{{ route('admin.complaints.index') }}" class="btn btn-outline-secondary w-100" title="Reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Complaints Table --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Pengguna</th>
                        <th>Jenis</th>
                        <th>Subjek</th>
                        <th>Status</th>
                        <th>Prioritas</th>
                        <th>Tanggal</th>
                        <th style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($complaints as $complaint)
                        <tr class="{{ $complaint->priority === 'urgent' ? 'table-warning' : '' }}">
                            <td>
                                <span class="fw-medium">#{{ $complaint->id }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm me-2">
                                        {{ substr($complaint->user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="fw-medium text-white">{{ $complaint->user->name }}</div>
                                        <small class="text-muted">{{ $complaint->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($complaint->type === 'complaint')
                                    <span class="badge bg-warning">Keluhan</span>
                                @elseif($complaint->type === 'report')
                                    <span class="badge bg-danger">Laporan</span>
                                @else
                                    <span class="badge bg-info">Pertanyaan</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-white">{{ $complaint->subject }}</div>
                                @if($complaint->order_number)
                                    <small class="text-muted">Order: {{ $complaint->order_number }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $complaint->status === 'pending' ? 'warning' : ($complaint->status === 'resolved' ? 'success' : 'info') }}">
                                    {{ $complaint->status_name }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $complaint->priority === 'urgent' ? 'danger' : ($complaint->priority === 'high' ? 'warning' : 'secondary') }}">
                                    {{ $complaint->priority_name }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $complaint->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.complaints.show', $complaint) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox display-4 text-muted"></i>
                                <p class="mt-2 text-muted mb-0">Tidak ada aduan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- Pagination --}}
    @if($complaints->hasPages())
        <div class="card-footer">
            {{ $complaints->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .table-warning {
        background-color: rgba(234, 179, 8, 0.1) !important;
    }
    
    .user-avatar-sm {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--primary-color), #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 700;
        color: white;
    }
</style>
@endpush

