@extends('layouts.admin')

@section('title', 'Laporan Peminjaman')
@section('page-title', 'Laporan Peminjaman')

@section('content')
{{-- Header Actions --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Laporan Peminjaman Perpustakaan</h4>
        <p class="text-muted mb-0">Kelola dan export data peminjaman buku</p>
    </div>
    <a href="{{ route('admin.reports.export-loans') }}" class="btn btn-success">
        <i class="bi bi-download me-2"></i>Export CSV
    </a>
</div>

{{-- Statistics Cards - Full Width 4 Kolom --}}
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="bi bi-journal-bookmark"></i>
            </div>
            <div class="stat-label">Total Peminjaman</div>
            <div class="stat-value">{{ number_format($stats['total']) }}</div>
            <div class="stat-change">
                <span>Semua status</span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="stat-label">Menunggu Persetujuan</div>
            <div class="stat-value">{{ number_format($stats['pending']) }}</div>
            <div class="stat-change">
                <span>Belum disetujui</span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon info">
                <i class="bi bi-book"></i>
            </div>
            <div class="stat-label">Sedang Dipinjam</div>
            <div class="stat-value">{{ number_format($stats['borrowed']) }}</div>
            <div class="stat-change">
                <span>Buku aktif</span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-label">Dikembalikan</div>
            <div class="stat-value">{{ number_format($stats['returned']) }}</div>
            <div class="stat-change">
                <span>Selesai</span>
            </div>
        </div>
    </div>
</div>

{{-- Filter Card --}}
<div class="card mb-4">
    <div class="card-header">
        <i class="bi bi-funnel me-2"></i>Filter Laporan
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending">Menunggu Persetujuan</option>
                    <option value="approved">Disetujui</option>
                    <option value="borrowed">Dipinjam</option>
                    <option value="returned">Dikembalikan</option>
                    <option value="overdue">Terlambat</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-2"></i>Cari
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Loans Table --}}
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
                        <th>Kategori</th>
                        <th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th>Durasi</th>
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
                                        <div class="fw-medium">{{ $loan->user->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $loan->user->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ Str::limit($loan->book->name ?? 'Buku', 25) }}</td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $loan->book->category->name ?? '-' }}
                                </span>
                            </td>
                            <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                            <td class="{{ $loan->is_overdue ? 'text-danger fw-medium' : '' }}">
                                {{ $loan->due_date->format('d/m/Y') }}
                                @if($loan->is_overdue)
                                    <i class="bi bi-exclamation-circle-fill"></i>
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
                                        'cancelled' => 'secondary'
                                    ];
                                    $statusText = [
                                        'pending' => 'Menunggu',
                                        'approved' => 'Disetujui',
                                        'borrowed' => 'Dipinjam',
                                        'returned' => 'Dikembalikan',
                                        'overdue' => 'Terlambat',
                                        'cancelled' => 'Dibatalkan'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$loan->status] ?? 'secondary' }}">
                                    {{ $statusText[$loan->status] ?? ucfirst($loan->status) }}
                                </span>
                            </td>
                            <td>{{ $loan->loan_duration ?? 7 }} hari</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox display-4 text-muted"></i>
                                <p class="mt-2 text-muted">Tidak ada data peminjaman</p>
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
@endsection

