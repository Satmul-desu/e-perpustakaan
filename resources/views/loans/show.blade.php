@extends('layouts.app')
@section('title', 'Detail Peminjaman')
@section('content')
    <div class="container-fluid py-4 px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-white mb-0">
                <i class="bi bi-book me-2"></i>Detail Peminjaman
            </h2>
            <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary" style="border-radius: 10px;">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card mb-4"
                    style="background: rgba(30, 41, 59, 0.95); border: 1px solid #334155; border-radius: 16px;">
                    <div class="card-header"
                        style="background: rgba(15, 23, 42, 0.5); border-bottom: 1px solid #334155; border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 text-white"><i class="bi bi-book me-2"></i>Informasi Buku</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <img src="{{ $loan->book->image_url }}" class="img-fluid rounded"
                                    style="border: 2px solid #334155; width: 100%; max-width: 200px;">
                            </div>
                            <div class="col-md-8">
                                <h4 class="text-white mb-3">{{ $loan->book->name }}</h4>
                                <p class="text-secondary mb-2">
                                    <i class="bi bi-folder me-2"></i>{{ $loan->book->category->name ?? 'Buku' }}
                                </p>
                                <p class="text-secondary">
                                    {{ $loan->book->description ?? 'Tidak ada deskripsi' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card"
                    style="background: rgba(30, 41, 59, 0.95); border: 1px solid #334155; border-radius: 16px;">
                    <div class="card-header"
                        style="background: rgba(15, 23, 42, 0.5); border-bottom: 1px solid #334155; border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 text-white"><i class="bi bi-clock-history me-2"></i>Riwayat Peminjaman</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item mb-4">
                                <div class="d-flex align-items-start">
                                    <div class="me-3">
                                        <span class="badge d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #6366f1, #4f46e5);">
                                            <i class="bi bi-book"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-white mb-1 fw-bold">Peminjaman Dibuat</p>
                                        <small class="text-secondary">{{ $loan->created_at->format('d M Y, H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                            @if ($loan->status != 'pending' && $loan->status != 'cancelled')
                                <div class="timeline-item mb-4">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3">
                                            <span class="badge d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #3b82f6, #2563eb);">
                                                <i class="bi bi-check-lg"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-white mb-1 fw-bold">Peminjaman Disetujui</p>
                                            <small
                                                class="text-secondary">{{ $loan->updated_at->format('d M Y, H:i') }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($loan->status == 'borrowed' || $loan->status == 'returned' || $loan->status == 'overdue')
                                <div class="timeline-item mb-4">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3">
                                            <span class="badge d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                                                <i class="bi bi-box-arrow-right"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-white mb-1 fw-bold">Buku Diambil</p>
                                            <small
                                                class="text-secondary">{{ $loan->loan_date->format('d M Y, H:i') }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($loan->status == 'returned')
                                <div class="timeline-item">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3">
                                            <span class="badge d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #22c55e, #16a34a);">
                                                <i class="bi bi-check-circle"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-white mb-1 fw-bold">Buku Dikembalikan</p>
                                            <small
                                                class="text-secondary">{{ $loan->return_date->format('d M Y, H:i') }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mb-4"
                    style="background: rgba(30, 41, 59, 0.95); border: 1px solid #334155; border-radius: 16px;">
                    <div class="card-header"
                        style="background: rgba(15, 23, 42, 0.5); border-bottom: 1px solid #334155; border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 text-white"><i class="bi bi-info-circle me-2"></i>Status Peminjaman</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $statusColors = [
                                'pending' => 'warning',
                                'approved' => 'info',
                                'borrowed' => 'primary',
                                'returned' => 'success',
                                'overdue' => 'danger',
                                'cancelled' => 'secondary',
                            ];
                            $statusBgColors = [
                                'pending' =>
                                    'background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(234, 88, 12, 0.2)); border: 1px solid #f59e0b; color: #fbbf24 !important;',
                                'approved' =>
                                    'background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.2)); border: 1px solid #3b82f6; color: #60a5fa !important;',
                                'borrowed' =>
                                    'background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(79, 70, 229, 0.2)); border: 1px solid #6366f1; color: #818cf8 !important;',
                                'returned' =>
                                    'background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(22, 163, 74, 0.2)); border: 1px solid #22c55e; color: #34d399 !important;',
                                'overdue' =>
                                    'background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.2)); border: 1px solid #ef4444; color: #f87171 !important;',
                                'cancelled' =>
                                    'background: linear-gradient(135deg, rgba(107, 114, 128, 0.2), rgba(75, 85, 99, 0.2)); border: 1px solid #6b7280; color: #9ca3af !important;',
                            ];
                            $statusText = [
                                'pending' => 'Menunggu Persetujuan',
                                'approved' => 'Disetujui - Siapkan Buku',
                                'borrowed' => 'Sedang Dipinjam',
                                'returned' => 'Dikembalikan',
                                'overdue' => 'Terlambat!',
                                'cancelled' => 'Dibatalkan',
                            ];
                        @endphp
                        <div class="text-center mb-4">
                            <span class="badge d-inline-flex px-4 py-3"
                                style="{{ $statusBgColors[$loan->status] ?? 'background: rgba(107, 114, 128, 0.2); border: 1px solid #6b7280; color: #9ca3af !important;' }}; font-size: 1rem; border-radius: 10px;">
                                {{ $statusText[$loan->status] ?? ucfirst($loan->status) }}
                            </span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-borderless" style="color: #94a3b8;">
                                <tr>
                                    <td class="py-2"><i class="bi bi-calendar me-2"></i>Tanggal Pinjam</td>
                                    <td class="text-white text-end">{{ $loan->loan_date->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2"><i class="bi bi-calendar-check me-2"></i>Jatuh Tempo</td>
                                    <td class="text-white text-end {{ $loan->is_overdue ? 'text-danger fw-bold' : '' }}">
                                        {{ $loan->due_date->format('d M Y') }}
                                        @if ($loan->is_overdue)
                                            <i class="bi bi-exclamation-circle text-danger ms-1"></i>
                                        @endif
                                    </td>
                                </tr>
                                @if ($loan->return_date)
                                    <tr>
                                        <td class="py-2"><i class="bi bi-calendar-x me-2"></i>Tanggal Kembali</td>
                                        <td class="text-white text-end">{{ $loan->return_date->format('d M Y') }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="py-2"><i class="bi bi-clock me-2"></i>Durasi</td>
                                    <td class="text-white text-end">
                                        @if ($loan->is_hours_duration)
                                            {{ $loan->loan_duration_hours }} jam
                                        @else
                                            {{ $loan->loan_duration }} hari
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        @if ($loan->notes)
                            <div class="alert mb-3"
                                style="background: rgba(59, 130, 246, 0.1); border: 1px solid #3b82f6; border-radius: 10px;">
                                <small><strong><i class="bi bi-chat-left-text me-1"></i>Catatan:</strong>
                                    {{ $loan->notes }}</small>
                            </div>
                        @endif
                        @if ($loan->admin_notes)
                            <div class="alert mb-3"
                                style="background: rgba(245, 158, 11, 0.1); border: 1px solid #f59e0b; border-radius: 10px;">
                                <small><strong><i class="bi bi-shield-check me-1"></i>Catatan Admin:</strong>
                                    {{ $loan->admin_notes }}</small>
                            </div>
                        @endif
                        @if (in_array($loan->status, ['pending', 'approved']))
                            <form method="POST" action="{{ route('loans.cancel', $loan) }}" class="d-grid gap-2 mt-4">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-lg"
                                    onclick="return confirm('Batalkan peminjaman ini?')" style="border-radius: 12px;">
                                    <i class="bi bi-x-circle me-2"></i>Batalkan Peminjaman
                                </button>
                            </form>
                        @endif
                        @if ($loan->status == 'borrowed')
                            <form method="POST" action="{{ route('loans.process-return', $loan) }}"
                                class="d-grid gap-2 mt-4">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg"
                                    onclick="return confirm('Kembalikan buku ini?')" style="border-radius: 12px;">
                                    <i class="bi bi-check2-circle me-2"></i>Kembalikan Buku
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .timeline {
            position: relative;
            padding-left: 1rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 19px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, #334155, transparent);
        }

        .timeline-item:last-child::before {
            display: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4f46e5, #4338ca);
        }

        .btn-success {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border: none;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #16a34a, #15803d);
        }

        .btn-outline-danger {
            border-color: #ef4444;
            color: #ef4444;
        }

        .btn-outline-danger:hover {
            background: #ef4444;
            border-color: #ef4444;
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
        }
    </style>
@endsection
