@extends('layouts.app')
@section('title', 'Peminjaman Saya')
@section('content')
<div class="container-fluid py-4 px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white mb-0">
            <i class="bi bi-book me-2"></i>Peminjaman Saya
        </h2>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card stat-card h-100" style="background: linear-gradient(135deg, #f59e0b, #d97706); border: none;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-white opacity-75">Menunggu</small>
                            <h4 class="mb-0 text-white fw-bold">{{ $stats['pending'] ?? 0 }}</h4>
                        </div>
                        <i class="bi bi-clock-history stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100" style="background: linear-gradient(135deg, #3b82f6, #2563eb); border: none;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-white opacity-75">Dipinjam</small>
                            <h4 class="mb-0 text-white fw-bold">{{ $stats['borrowed'] ?? 0 }}</h4>
                        </div>
                        <i class="bi bi-book stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100" style="background: linear-gradient(135deg, #10b981, #059669); border: none;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-white opacity-75">Dikembalikan</small>
                            <h4 class="mb-0 text-white fw-bold">{{ $stats['returned'] ?? 0 }}</h4>
                        </div>
                        <i class="bi bi-check-circle stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100" style="background: linear-gradient(135deg, #ef4444, #dc2626); border: none;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-white opacity-75">Terlambat</small>
                            <h4 class="mb-0 text-white fw-bold">{{ $stats['overdue'] ?? 0 }}</h4>
                        </div>
                        <i class="bi bi-exclamation-triangle stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4" style="background: rgba(30, 41, 59, 0.95); border: 1px solid #334155; border-radius: 16px;">
        <div class="card-body py-3">
            <form method="GET" class="row g-3 align-items-center">
                <div class="col-md-4">
                    <label class="form-label text-secondary mb-1">Filter Status</label>
                    <select name="status" class="form-select" style="background: rgba(15, 23, 42, 0.6); border: 1px solid #334155; color: white; border-radius: 10px;">
                        <option value="">Semua Status</option>
                        <option value="pending">Menunggu Persetujuan</option>
                        <option value="approved">Disetujui</option>
                        <option value="borrowed">Dipinjam</option>
                        <option value="returned">Dikembalikan</option>
                        <option value="overdue">Terlambat</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100" style="border-radius: 10px;">
                        <i class="bi bi-filter me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
    @if($loans->count() > 0)
        <div class="card" style="background: rgba(30, 41, 59, 0.95); border: 1px solid #334155; border-radius: 16px;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="border-color: #334155;">
                        <thead style="background: rgba(15, 23, 42, 0.8);">
                            <tr>
                                <th class="text-black px-4 py-3" style="white-space: nowrap;">Buku</th>
                                <th class="text-black py-3" style="white-space: nowrap;">Tgl Pinjam</th>
                                <th class="text-black py-3" style="white-space: nowrap;">Jatuh Tempo</th>
                                <th class="text-black py-3" style="white-space: nowrap;">Status</th>
                                <th class="text-black py-3" style="white-space: nowrap;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody style="color: #e2e8f0;">
                            @foreach($loans as $loan)
                                <tr style="border-color: #334155; background: rgba(30, 41, 59, 0.7);">
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $loan->book->image_url }}"
                                                 class="rounded me-3"
                                                 width="45" height="60"
                                                 style="object-fit: cover; border: 1px solid #475569;">
                                            <div>
                                                <small class="text-black d-block fw-bold">{{ Str::limit($loan->book->name, 25) }}</small>
                                                <small class="text-black">{{ $loan->book->category->name ?? 'Buku' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3" style="white-space: nowrap; color: black;">{{ $loan->loan_date->format('d M Y') }}</td>
                                    <td class="py-3 {{ $loan->is_overdue ? 'text-black fw-bold' : 'text-black' }}" style="white-space: nowrap;">
                                        {{ $loan->due_date->format('d M Y') }}
                                        @if($loan->is_overdue)
                                            <i class="bi bi-exclamation-circle ms-1"></i>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        @php
                                            $statusBgColors = [
                                                'pending' => 'background: rgba(245, 158, 11, 0.2); border: 1px solid #f59e0b; color: #fbbf24 !important;',
                                                'approved' => 'background: rgba(59, 130, 246, 0.2); border: 1px solid #3b82f6; color: #60a5fa !important;',
                                                'borrowed' => 'background: rgba(99, 102, 241, 0.2); border: 1px solid #6366f1; color: #818cf8 !important;',
                                                'returned' => 'background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; color: #34d399 !important;',
                                                'overdue' => 'background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #f87171 !important;',
                                                'cancelled' => 'background: rgba(107, 114, 128, 0.2); border: 1px solid #6b7280; color: #9ca3af !important;'
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
                                        <span class="badge px-3 py-2" style="{{ $statusBgColors[$loan->status] ?? 'background: rgba(107, 114, 128, 0.2); border: 1px solid #6b7280; color: #9ca3af !important;' }}">
                                            {{ $statusText[$loan->status] ?? ucfirst($loan->status) }}
                                        </span>
                                        @if($loan->status == 'borrowed' && $loan->days_remaining >= 0)
                                            <br><small class="text-info mt-1 d-block"><i class="bi bi-clock me-1"></i>{{ $loan->days_remaining }} hari</small>
                                        @endif
                                    </td>
                                    <td class="py-3" style="white-space: nowrap;">
                                        <a href="{{ route('loans.show', $loan) }}" class="btn btn-sm btn-outline-primary me-1" style="border-radius: 8px;">
                                            <i class="bi bi-eye me-1"></i>Detail
                                        </a>
                                        @if(in_array($loan->status, ['pending', 'approved']))
                                            <form method="POST" action="{{ route('loans.cancel', $loan) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Batalkan peminjaman ini?')" style="border-radius: 8px;">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if($loan->status == 'borrowed')
                                            <form method="POST" action="{{ route('loans.process-return', $loan) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" style="border-radius: 8px;">
                                                    <i class="bi bi-check2-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $loans->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-book display-1 text-secondary"></i>
            <h4 class="mt-3 text-white">Belum Ada Peminjaman</h4>
            <p class="text-secondary">Anda belum melakukan peminjaman buku apapun</p>
            <a href="{{ route('catalog.index') }}" class="btn btn-primary mt-2" style="border-radius: 10px;">
                <i class="bi bi-search me-2"></i>Jelajahi Koleksi Buku
            </a>
        </div>
    @endif
</div>
<style>
    .stat-card {
        border: none;
        border-radius: 16px;
    }
    .stat-card .card-body {
        padding: 1.5rem;
    }
    .stat-icon {
        font-size: 2.5rem;
        opacity: 0.3;
    }
    .table tbody tr:hover {
        background: rgba(99, 102, 241, 0.1) !important;
    }
    .form-select option {
        background: #1e293b;
        color: white;
    }
    .btn-primary {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #4f46e5, #4338ca);
    }
    .btn-outline-primary {
        border-color: #6366f1;
        color: #6366f1;
    }
    .btn-outline-primary:hover {
        background: #6366f1;
        border-color: #6366f1;
    }
    .btn-success {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border: none;
    }
    .btn-outline-danger {
        border-color: #ef4444;
        color: #ef4444;
    }
    .btn-outline-danger:hover {
        background: #ef4444;
        border-color: #ef4444;
    }
</style>
@endsection