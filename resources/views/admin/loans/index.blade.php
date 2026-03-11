@extends('layouts.admin')
@section('title', 'Kelola Peminjaman')
@section('page-title', 'Kelola Peminjaman')
@section('content')
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #6b7280, #4b5563);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-white opacity-75">Total</small>
                        <h4 class="mb-0 text-white">{{ $stats['total'] ?? 0 }}</h4>
                    </div>
                    <i class="bi bi-collection stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-white opacity-75">Menunggu</small>
                        <h4 class="mb-0 text-white">{{ $stats['pending'] ?? 0 }}</h4>
                    </div>
                    <i class="bi bi-clock-history stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-white opacity-75">Dipinjam</small>
                        <h4 class="mb-0 text-white">{{ $stats['borrowed'] ?? 0 }}</h4>
                    </div>
                    <i class="bi bi-book stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-white opacity-75">Terlambat</small>
                        <h4 class="mb-0 text-white">{{ $stats['overdue'] ?? 0 }}</h4>
                    </div>
                    <i class="bi bi-exclamation-triangle stat-icon"></i>
                </div>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ route('admin.loans.mark-overdue') }}" class="mb-4">
        @csrf
        <button type="submit" class="btn btn-warning">
            <i class="bi bi-exclamation-triangle me-1"></i>Update Status Terlambat
        </button>
    </form>
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-funnel me-2"></i>Filter Peminjaman
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Filter Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending">Menunggu Persetujuan</option>
                        <option value="borrowed">Dipinjam</option>
                        <option value="returned">Dikembalikan</option>
                        <option value="overdue">Terlambat</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama user atau buku...">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i>Cari
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-table me-2"></i>Data Peminjaman</span>
            <span class="text-muted">{{ $loans->total() }} data</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Peminjam</th>
                            <th>Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $loan)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar-sm me-2">
                                            {{ substr($loan->user->name ?? 'U', 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $loan->user->name }}</div>
                                            <small class="text-muted">{{ $loan->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ Str::limit($loan->book->name ?? 'Buku', 30) }}</td>
                                <td>{{ $loan->loan_date->format('d M Y') }}</td>
                                <td class="{{ $loan->is_overdue ? 'text-danger fw-medium' : '' }}">
                                    {{ $loan->due_date->format('d M Y') }}
                                    @if ($loan->is_hours_duration)
                                        <small class="d-block text-muted">({{ $loan->loan_duration_hours }} jam)</small>
                                    @else
                                        <small class="d-block text-muted">({{ $loan->loan_duration }} hari)</small>
                                    @endif
                                    @if ($loan->is_overdue)
                                        <i class="bi bi-exclamation-circle"></i>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'approved' => 'info',
                                            'borrowed' => 'primary',
                                            'returned' => 'success',
                                            'overdue' => 'danger',
                                            'cancelled' => 'secondary',
                                        ];
                                        $statusText = [
                                            'pending' => 'Menunggu',
                                            'approved' => 'Disetujui',
                                            'borrowed' => 'Dipinjam',
                                            'returned' => 'Dikembalikan',
                                            'overdue' => 'Terlambat',
                                            'cancelled' => 'Dibatalkan',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$loan->status] ?? 'secondary' }}">
                                        {{ $statusText[$loan->status] ?? ucfirst($loan->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.loans.show', $loan) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-secondary py-4">
                                    <i class="bi bi-inbox d-block mb-2" style="font-size: 2rem;"></i>
                                    Tidak ada data peminjaman
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-center">
                {{ $loans->links() }}
            </div>
        </div>
    </div>
    <style>
        .stat-card {
            border: none;
            border-radius: 12px;
            padding: 1.25rem;
        }

        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.3;
        }

        .user-avatar-sm {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--primary-color), #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            color: white;
        }
    </style>
@endsection
