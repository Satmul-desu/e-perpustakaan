@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pratinjau & Alat Ekspor Laporan</h1>
        <a href="{{ route('admin.reports.loans', $filters) }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Laporan
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold">Konfirmasi Data Ekspor</h6>
            <div>
                <a href="{{ route('admin.reports.export.loans.excel', $filters) }}" class="btn btn-light btn-sm font-weight-bold text-success">
                    <i class="fas fa-file-excel"></i> Download Excel
                </a>
                <a href="{{ route('admin.reports.export.loans.word', $filters) }}" class="btn btn-light btn-sm font-weight-bold text-primary ml-2">
                    <i class="fas fa-file-word"></i> Download Word
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3">
                    <label class="text-xs font-weight-bold text-uppercase text-muted">Tanggal Mulai</label>
                    <div class="h6 font-weight-bold">{{ $filters['date_from'] ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <label class="text-xs font-weight-bold text-uppercase text-muted">Tanggal Selesai</label>
                    <div class="h6 font-weight-bold">{{ $filters['date_to'] ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <label class="text-xs font-weight-bold text-uppercase text-muted">Filter Status</label>
                    <div class="h6 font-weight-bold text-capitalize">{{ $filters['status'] ?? 'Semua' }}</div>
                </div>
                <div class="col-md-3">
                    <label class="text-xs font-weight-bold text-uppercase text-muted">Jumlah Data Terpilih</label>
                    <div class="h6 font-weight-bold text-primary">{{ $loans->count() }} Record</div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-bordered" width="100%">
                    <thead class="bg-light">
                        <tr>
                            <th>No</th>
                            <th>Peminjam</th>
                            <th>Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans->take(50) as $index => $loan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $loan->user->name ?? '-' }}</td>
                            <td>{{ $loan->book->name ?? '-' }}</td>
                            <td>{{ $loan->loan_date ? $loan->loan_date->format('d M Y') : '-' }}</td>
                            <td>{{ $loan->due_date ? $loan->due_date->format('d M Y') : '-' }}</td>
                            <td>
                                @php
                                    $statusLabels = [
                                        'pending' => 'Menunggu',
                                        'approved' => 'Disetujui',
                                        'borrowed' => 'Dipinjam',
                                        'returned' => 'Dikembalikan',
                                        'overdue' => 'Terlambat',
                                        'cancelled' => 'Dibatalkan'
                                    ];
                                    $statusColor = match($loan->status) {
                                        'pending' => 'warning',
                                        'returned' => 'success',
                                        'overdue' => 'danger',
                                        'borrowed' => 'primary',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge badge-{{ $statusColor }}">
                                    {{ $statusLabels[$loan->status] ?? $loan->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Tidak ada data ditemukan untuk ekspor ini.</td>
                        </tr>
                        @endforelse
                        
                        @if($loans->count() > 50)
                        <tr>
                            <td colspan="6" class="text-center bg-light small italic text-muted">
                                ... Menampilkan 50 data pertama dari total {{ $loans->count() }} data yang akan diekspor ...
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection