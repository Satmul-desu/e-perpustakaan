@extends('layouts.app')
@section('title', 'Detail Peminjaman')
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white">
            <i class="bi bi-book me-2"></i>Detail Peminjaman
        </h2>
        <a href="{{ route('admin.loans.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4" style="background: rgba(30, 41, 59, 0.95); border: 1px solid #334155;">
                <div class="card-header" style="background: rgba(15, 23, 42, 0.5); border-bottom: 1px solid #334155;">
                    <h5 class="mb-0 text-white">Informasi Buku</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{ $loan->book->image_url }}"
                                 class="img-fluid rounded"
                                 style="border: 2px solid #334155;">
                        </div>
                        <div class="col-md-8">
                            <h4 class="text-white mb-2">{{ $loan->book->name }}</h4>
                            <p class="text-secondary mb-1">
                                <i class="bi bi-folder me-1"></i>{{ $loan->book->category->name ?? 'Buku' }}
                            </p>
                            <p class="text-secondary mb-0">
                                <strong>Stok:</strong> {{ $loan->book->stock }}
                            </p>
                        </div>
                </div>
            <div class="card mb-4" style="background: rgba(30, 41, 59, 0.95); border: 1px solid #334155;">
                <div class="card-header" style="background: rgba(15, 23, 42, 0.5); border-bottom: 1px solid #334155;">
                    <h5 class="mb-0 text-white">Informasi Peminjam</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-lg me-3">
                            <span class="avatar-title bg-primary rounded-circle" style="font-size: 1.5rem;">
                                {{ substr($loan->user->name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <h5 class="text-white mb-0">{{ $loan->user->name }}</h5>
                            <p class="text-secondary mb-0">{{ $loan->user->email }}</p>
                        </div>
                </div>
            @if($loan->notes || $loan->admin_notes)
            <div class="card" style="background: rgba(30, 41, 59, 0.95); border: 1px solid #334155;">
                <div class="card-header" style="background: rgba(15, 23, 42, 0.5); border-bottom: 1px solid #334155;">
                    <h5 class="mb-0 text-white">Catatan</h5>
                </div>
                <div class="card-body">
                    @if($loan->notes)
                        <div class="mb-3">
                            <small class="text-secondary d-block mb-1">Catatan Peminjam:</small>
                            <p class="text-white mb-0">{{ $loan->notes }}</p>
                        </div>
                    @endif
                    @if($loan->admin_notes)
                        <div>
                            <small class="text-secondary d-block mb-1">Catatan Admin:</small>
                            <p class="text-white mb-0">{{ $loan->admin_notes }}</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
        <div class="col-md-4">
            <div class="card mb-4" style="background: rgba(30, 41, 59, 0.95); border: 1px solid #334155;">
                <div class="card-header" style="background: rgba(15, 23, 42, 0.5); border-bottom: 1px solid #334155;">
                    <h5 class="mb-0 text-white">Status</h5>
                </div>
                <div class="card-body">
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
                            'pending' => 'Menunggu Persetujuan',
                            'approved' => 'Disetujui',
                            'borrowed' => 'Sedang Dipinjam',
                            'returned' => 'Dikembalikan',
                            'overdue' => 'Terlambat',
                            'cancelled' => 'Dibatalkan'
                        ];
                    @endphp
                    <div class="text-center mb-3">
                        <span class="badge bg-{{ $statusColors[$loan->status] ?? 'secondary' }} p-3" style="font-size: 1rem;">
                            {{ $statusText[$loan->status] ?? ucfirst($loan->status) }}
                        </span>
                    </div>
                    <table class="table table-borderless" style="color: #94a3b8;">
                        <tr>
                            <td>Tgl Pinjam</td>
                            <td class="text-white">{{ $loan->loan_date->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td>Jatuh Tempo</td>
                            <td class="text-white {{ $loan->is_overdue ? 'text-danger' : '' }}">
                                {{ $loan->due_date->format('d M Y') }}
                            </td>
                        </tr>
                        @if($loan->return_date)
                        <tr>
                            <td>Tgl Kembali</td>
                            <td class="text-white">{{ $loan->return_date->format('d M Y') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td>Durasi</td>
                            <td class="text-white">
                                @if($loan->is_hours_duration)
                                    {{ $loan->loan_duration_hours }} jam
                                @else
                                    {{ $loan->loan_duration }} hari
                                @endif
                            </td>
                        </tr>
                    </table>
                    @if($loan->status == 'pending')
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-info-circle me-1"></i>
                            Peminjaman akan langsung berstatus <strong>Dipinjam</strong> dengan durasi <strong>45 jam</strong> setelah disetujui.
                        </div>
                        <form method="POST" action="{{ route('admin.loans.approve', $loan) }}" class="d-grid mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check me-1"></i>Setuju & Berikan Buku (45 Jam)
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="bi bi-x me-1"></i>Tolak Peminjaman
                        </button>
                    @endif
                    @if($loan->status == 'approved')
                        <div class="alert alert-warning mb-3">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Peminjaman lama dengan status Disetujui. Silakan berikan buku ke peminjam.
                        </div>
                        <form method="POST" action="{{ route('admin.loans.mark-borrowed', $loan) }}" class="d-grid mb-2">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-book me-1"></i>Berikan Buku ke Peminjam
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.loans.reject', $loan) }}" class="d-grid">
                            @csrf
                            <input type="hidden" name="reason" value="Dibatalkan oleh admin">
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-x-circle me-1"></i>Batalkan
                            </button>
                        </form>
                    @endif
                    @if($loan->status == 'borrowed')
                        <form method="POST" action="{{ route('admin.loans.process-return', $loan) }}" class="d-grid mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check2-circle me-1"></i>Terima Pengembalian Buku
                            </button>
                        </form>
                        <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#extendModal">
                            <i class="bi bi-clock me-1"></i>Perpanjang Durasi
                        </button>
                    @endif
                </div>
        </div>
</div>
<div class="modal fade" id="rejectModal" tabindex="-1" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content" style="background: #1e293b; color: white;">
            <form method="POST" action="{{ route('admin.loans.reject', $loan) }}">
                @csrf
                <div class="modal-header" style="border-color: #334155;">
                    <h5 class="modal-title">Tolak Peminjaman</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="border-color: #334155;">
                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan</label>
                        <textarea name="reason" class="form-control" rows="3" required 
                                  style="background: rgba(15, 23, 42, 0.6); border: 1px solid #334155; color: white;"></textarea>
                    </div>
                <div class="modal-footer" style="border-color: #334155;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
</div>
<div class="modal fade" id="extendModal" tabindex="-1" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content" style="background: #1e293b; color: white;">
            <form method="POST" action="{{ route('admin.loans.extend', $loan) }}">
                @csrf
                <div class="modal-header" style="border-color: #334155;">
                    <h5 class="modal-title">Perpanjang Durasi Peminjaman</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="border-color: #334155;">
                    <div class="mb-3">
                        <label class="form-label">Tambah Hari</label>
                        <select name="days" class="form-select" style="background: rgba(15, 23, 42, 0.6); border: 1px solid #334155; color: white;">
                            <option value="1">1 hari</option>
                            <option value="3">3 hari</option>
                            <option value="7">7 hari</option>
                            <option value="14">14 hari</option>
                            <option value="30">30 hari</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alasan</label>
                        <textarea name="reason" class="form-control" rows="2" 
                                  style="background: rgba(15, 23, 42, 0.6); border: 1px solid #334155; color: white;"></textarea>
                    </div>
                <div class="modal-footer" style="border-color: #334155;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Perpanjang</button>
                </div>
            </form>
        </div>
</div>
<style>
    .avatar-lg {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .avatar-title {
        font-weight: 600;
    }
</style>
@endsection