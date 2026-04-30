@extends('layouts.admin')
@section('title', 'Detail Peminjaman')
@section('page-title', 'Detail Peminjaman')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.loans.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="row">
        {{-- Kolom Kiri: Informasi Buku & Peminjam --}}
        <div class="col-md-8">
            {{-- Kartu Informasi Buku --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-book me-2"></i>Informasi Buku</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                                @if($loan->book && $loan->book->primaryImage)
                                    <img src="{{ asset('storage/products/' . $loan->book->primaryImage->image_path) }}" 
                                         class="img-fluid rounded" alt="{{ $loan->book->name }}">
                                @elseif($loan->book)
                                    <img src="{{ $loan->book->image_url }}" class="img-fluid rounded" alt="{{ $loan->book->name }}">
                                @else
                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="height: 150px;">
                                        <i class="bi bi-image text-white display-4"></i>
                                    </div>
                                @endif

                        </div>
                        <div class="col-md-9">
                            <h4 class="mb-2">{{ $loan->book->name }}</h4>
                            <p class="text-secondary mb-1">
                                <i class="bi bi-folder me-1"></i>{{ $loan->book->category->name ?? 'Tanpa Kategori' }}
                            </p>
                            <p class="text-secondary mb-0">
                                <strong>Stok Tersedia:</strong> {{ $loan->book->stock }}
                            </p>
                            <div class="mt-3">
                                <span class="badge bg-dark border border-secondary">{{ $loan->book->slug }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kartu Informasi Peminjam --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person me-2"></i>Informasi Peminjam</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar-lg me-3">
                            <span class="avatar-title bg-primary rounded-circle text-white d-flex align-items-center justify-content-center" 
                                  style="width: 50px; height: 50px; font-size: 1.5rem; font-weight: bold;">
                                {{ substr($loan->user->name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $loan->user->name }}</h5>
                            <p class="text-secondary mb-0">{{ $loan->user->email }}</p>
                        </div>
                    </div>

                    @if ($loan->notes || $loan->admin_notes)
                        <div class="card bg-dark bg-opacity-25 border border-secondary">
                            <div class="card-body">
                                @if ($loan->notes)
                                    <div class="mb-3">
                                        <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Catatan Peminjam</small>
                                        <p class="mb-0">{{ $loan->notes }}</p>
                                    </div>
                                @endif
                                @if ($loan->notes && $loan->admin_notes)
                                    <hr class="border-secondary">
                                @endif
                                @if ($loan->admin_notes)
                                    <div>
                                        <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Catatan Admin</small>
                                        <p class="mb-0">{{ $loan->admin_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Status & Aksi --}}
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Status Peminjaman</h5>
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
                        $statusText = [
                            'pending' => 'Menunggu Persetujuan',
                            'approved' => 'Disetujui',
                            'borrowed' => 'Sedang Dipinjam',
                            'returned' => 'Dikembalikan',
                            'overdue' => 'Terlambat',
                            'cancelled' => 'Dibatalkan',
                        ];
                    @endphp
                    
                    <div class="text-center mb-4 mt-2">
                        <span class="badge bg-{{ $statusColors[$loan->status] ?? 'secondary' }} p-3 fs-6">
                            {{ $statusText[$loan->status] ?? ucfirst($loan->status) }}
                        </span>
                    </div>

                    <table class="table table-borderless text-secondary mb-4">
                        <tr>
                            <td class="ps-0">Tgl Pinjam</td>
                            <td class="text-end text-white">{{ $loan->loan_date->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td class="ps-0">Jatuh Tempo</td>
                            <td class="text-end text-white {{ $loan->is_overdue ? 'text-danger fw-bold' : '' }}">
                                {{ $loan->due_date->format('d M Y') }}
                            </td>
                        </tr>
                        @if ($loan->return_date)
                            <tr>
                                <td class="ps-0">Dikembalikan</td>
                                <td class="text-end text-success">{{ $loan->return_date->format('d M Y') }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="ps-0">Durasi</td>
                            <td class="text-end text-white">
                                @if ($loan->is_hours_duration)
                                    {{ $loan->loan_duration_hours }} jam
                                @else
                                    {{ $loan->loan_duration }} hari
                                @endif
                            </td>
                        </tr>
                    </table>

                    <div class="d-grid gap-2">
                        @if ($loan->status == 'pending')
                            <div class="alert alert-info py-2 small mb-2">
                                <i class="bi bi-info-circle me-1"></i>
                                Menyetujui akan mengubah status menjadi <strong>Dipinjam</strong> (45 Jam).
                            </div>
                            <form method="POST" action="{{ route('admin.loans.approve', $loan) }}">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 mb-2">
                                    <i class="bi bi-check-lg me-1"></i>Setujui Peminjaman
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal"
                                data-bs-target="#rejectModal">
                                <i class="bi bi-x-lg me-1"></i>Tolak
                            </button>
                        @endif

                        @if ($loan->status == 'approved')
                            <div class="alert alert-warning py-2 small mb-2">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                Peminjaman disetujui tapi buku belum diberikan.
                            </div>
                            <form method="POST" action="{{ route('admin.loans.mark-borrowed', $loan) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100 mb-2">
                                    <i class="bi bi-hand-thumbs-up me-1"></i>Tandai Sudah Diambil
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.loans.reject', $loan) }}">
                                @csrf
                                <input type="hidden" name="reason" value="Dibatalkan oleh admin">
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="bi bi-x-circle me-1"></i>Batalkan
                                </button>
                            </form>
                        @endif

                        @if ($loan->status == 'borrowed')
                            <form method="POST" action="{{ route('admin.loans.process-return', $loan) }}">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 mb-2">
                                    <i class="bi bi-journal-check me-1"></i>Proses Pengembalian
                                </button>
                            </form>
                            <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal"
                                data-bs-target="#extendModal">
                                <i class="bi bi-clock-history me-1"></i>Perpanjang Durasi
                            </button>
                        @endif

                        @if ($loan->status == 'overdue')
                            <button type="button" class="btn btn-warning w-100 mb-2" data-bs-toggle="modal"
                                data-bs-target="#fineModal">
                                <i class="bi bi-cash-coin me-1"></i>Kirim Tagihan Denda
                            </button>
                            <form method="POST" action="{{ route('admin.loans.process-return', $loan) }}">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-journal-check me-1"></i>Proses Pengembalian
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tolak -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.loans.reject', $loan) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tolak Peminjaman</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Alasan Penolakan</label>
                            <textarea name="reason" class="form-control" rows="3" required placeholder="Contoh: Stok buku rusak, atau user mencapai limit."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak Peminjaman</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Perpanjang -->
    <div class="modal fade" id="extendModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.loans.extend', $loan) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Perpanjang Durasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Tambah Durasi</label>
                            <select name="days" class="form-select">
                                <option value="1">1 Hari</option>
                                <option value="3">3 Hari</option>
                                <option value="7">1 Minggu</option>
                                <option value="14">2 Minggu</option>
                                <option value="30">1 Bulan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan (Opsional)</label>
                            <textarea name="reason" class="form-control" rows="2" placeholder="Alasan perpanjangan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Perpanjang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Denda -->
    <div class="modal fade" id="fineModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.loans.fine', $loan) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Kirim Tagihan Denda</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nominal Denda (Rp)</label>
                            <input type="number" name="fine_amount" class="form-control" min="1000" step="500" placeholder="Misal: 5000" required>
                            <small class="text-secondary">Pesan akan ditambahkan di catatan admin secara otomatis.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning"><i class="bi bi-send me-1"></i>Kirim Tagihan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
