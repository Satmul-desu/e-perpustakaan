{{-- ================================================
     FILE: resources/views/admin/dashboard.blade.php
     FUNGSI: Halaman dashboard admin perpustakaan modern dengan statistik lengkap
     ================================================ --}}

@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
{{-- ================================================
     1. STATISTIK UTAMA (6 Kolom Full Width)
     ================================================ --}}
<div class="row g-3 mb-4">
    {{-- Total Peminjaman --}}
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card animate-fade-in">
            <div class="stat-icon success">
                <i class="bi bi-book"></i>
            </div>
            <div class="stat-label">Total Peminjaman</div>
            <div class="stat-value">{{ number_format($stats['total_loans']) }}</div>
            <div class="stat-change">
                <span>Semua status</span>
            </div>
        </div>
    </div>

    {{-- Buku Sedang Dipinjam --}}
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card animate-fade-in animate-delay-1">
            <div class="stat-icon primary">
                <i class="bi bi-journal-bookmark"></i>
            </div>
            <div class="stat-label">Buku Dipinjam</div>
            <div class="stat-value">{{ number_format($stats['active_loans']) }}</div>
            <div class="stat-change">
                <span>Buku aktif</span>
            </div>
        </div>
    </div>

    {{-- Buku Terlambat --}}
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card animate-fade-in animate-delay-2">
            <div class="stat-icon warning">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div class="stat-label">Buku Terlambat</div>
            <div class="stat-value">{{ number_format($stats['overdue_loans']) }}</div>
            @if($stats['overdue_loans'] > 0)
                <div class="stat-change" style="color: var(--warning-color)">
                    <span>Perlu penanganan!</span>
                </div>
            @else
                <div class="stat-change positive">
                    <span>Semua tepat waktu</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Peminjaman Pending --}}
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card animate-fade-in animate-delay-3">
            <div class="stat-icon info">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="stat-label">Menunggu Persetujuan</div>
            <div class="stat-value">{{ number_format($stats['pending_loans']) }}</div>
            @if($stats['pending_loans'] > 0)
                <div class="stat-change" style="color: var(--info-color)">
                    <span>Perlu konfirmasi</span>
                </div>
            @else
                <div class="stat-change positive">
                    <span>Tidak ada antrian</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Total Buku --}}
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="bi bi-book-half"></i>
            </div>
            <div class="stat-label">Total Buku</div>
            <div class="stat-value">{{ number_format($stats['total_books']) }}</div>
            <div class="stat-change">
                {{ number_format($stats['total_categories']) }} kategori
            </div>
        </div>
    </div>

    {{-- Total Anggota --}}
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.2)); color: #60a5fa;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-label">Total Anggota</div>
            <div class="stat-value">{{ number_format($stats['total_members']) }}</div>
            <div class="stat-change positive">
                <span>Anggota terdaftar</span>
            </div>
        </div>
    </div>
</div>

{{-- Alert Stok Rendah --}}
@if($stats['low_stock'] > 0 || $stats['out_of_stock'] > 0)
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div>
                <strong>Perhatian!</strong> 
                @if($stats['out_of_stock'] > 0)
                    {{ $stats['out_of_stock'] }} buku habis &nbsp;
                @endif
                @if($stats['low_stock'] > 0)
                    {{ $stats['low_stock'] }} buku stok rendah (≤5)
                @endif
                - Segera lakukan restock!
            </div>
        </div>
    </div>
</div>
@endif

{{-- ================================================
     2. CHARTS SECTION - Full Width
     ================================================ --}}
<div class="row g-4 mb-4">
    {{-- Peminjaman Chart (Full Width) --}}
    <div class="col-xl-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-graph-up me-2"></i>Peminjaman 7 Hari Terakhir</span>
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-secondary active" onclick="switchChart('daily')">Harian</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="switchChart('monthly')">Bulanan</button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>
    </div>

    {{-- Peminjaman per Kategori (Doughnut) --}}
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-pie-chart me-2"></i>Peminjaman per Kategori (30 hari)
            </div>
            <div class="card-body">
                @if($loansByCategory->count() > 0)
                    <canvas id="categoryChart" height="300"></canvas>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox display-4"></i>
                        <p class="mt-2">Belum ada data peminjaman</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ================================================
     3. RECENT LOANS & SIDEBAR WIDGETS - Full Width
     ================================================ --}}
<div class="row g-4">
{{-- Recent Loans --}}
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2"></i>Peminjaman Terbaru</span>
                <a href="{{ route('admin.loans.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Kode Peminjaman</th>
                                <th>Anggota</th>
                                <th>Buku</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLoans as $loan)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.loans.show', $loan) }}" class="text-decoration-none fw-medium">
                                            #{{ $loan->id }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar-sm me-2">
                                                {{ substr($loan->user->name ?? 'U', 0, 2) }}
                                            </div>
                                            <span>{{ $loan->user->name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($loan->book && $loan->book->primaryImage)
                                            <img src="{{ $loan->book->primaryImage->image_url }}" 
                                                 class="rounded me-2" 
                                                 width="30" height="40"
                                                 style="object-fit: cover;"
                                                 onerror="this.src='https://via.placeholder.com/30x40?text=B'">
                                            @else
                                            <div class="bg-secondary rounded me-2 d-flex align-items-center" style="width:30px;height:40px">
                                                <i class="bi bi-book text-white mx-auto"></i>
                                            </div>
                                            @endif
                                            <span>{{ Str::limit($loan->book->name ?? 'Buku', 25) }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($loan->status) {
                                                'pending' => 'warning',
                                                'approved' => 'info',
                                                'borrowed' => 'primary',
                                                'returned' => 'success',
                                                'overdue' => 'danger',
                                                'cancelled' => 'secondary',
                                                default => 'secondary'
                                            };
                                            $statusText = match($loan->status) {
                                                'pending' => 'Menunggu',
                                                'approved' => 'Disetujui',
                                                'borrowed' => 'Dipinjam',
                                                'returned' => 'Dikembalikan',
                                                'overdue' => 'Terlambat',
                                                'cancelled' => 'Dibatalkan',
                                                default => $loan->status
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                                    </td>
                                    <td class="text-muted">{{ $loan->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="bi bi-inbox display-3 text-muted"></i>
                                        <p class="mt-2 text-muted">Belum ada peminjaman</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

{{-- Sidebar Widgets --}}
    <div class="col-xl-4">
        {{-- Top Books --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-trophy me-2"></i>Buku Terpopuler</span>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                @forelse($topBooks as $index => $book)
                    <div class="d-flex align-items-center p-3 border-bottom {{ $loop->last ? 'border-0' : '' }}">
                        <div class="rank me-3">
                            @if($index === 0)
                                <span class="badge bg-warning">1</span>
                            @elseif($index === 1)
                                <span class="badge" style="background: #94a3b8;">2</span>
                            @elseif($index === 2)
                                <span class="badge" style="background: #b45309;">3</span>
                            @else
                                <span class="badge bg-secondary">{{ $index + 1 }}</span>
                            @endif
                        </div>
                        <img src="{{ $book->primaryImage?->image_url ?? 'https://via.placeholder.com/50' }}"
                             class="rounded me-3"
                             width="50" height="50"
                             style="object-fit: cover;"
                             onerror="this.src='https://via.placeholder.com/50?text=No+Image'">
                        <div class="flex-grow-1">
                            <h6 class="mb-1" style="font-size: 0.9rem;">{{ Str::limit($book->name, 25) }}</h6>
                            <small class="text-success fw-medium">{{ number_format($book->loan_count ?? 0) }}x dipinjam</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-light">{{ $book->stock }} stok</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bi bi-inbox display-4 text-muted"></i>
                        <p class="mt-2 text-muted">Belum ada buku dipinjam</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Overdue Members --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-exclamation-triangle me-2"></i>Anggota Terlambat</span>
                <a href="{{ route('admin.loans.index') }}?status=overdue" class="btn btn-sm btn-outline-danger">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                @forelse($overdueMembers as $index => $loan)
                    <div class="d-flex align-items-center p-3 border-bottom {{ $loop->last ? 'border-0' : '' }}">
                        <div class="user-avatar-sm me-3 bg-danger">
                            {{ substr($loan->user->name ?? 'U', 0, 2) }}
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1" style="font-size: 0.9rem;">{{ $loan->user->name ?? 'N/A' }}</h6>
                            <small class="text-danger fw-medium">{{ Str::limit($loan->book->name ?? 'Buku', 20) }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-danger">Terlambat</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle display-5 text-success"></i>
                        <p class="mt-2 text-muted">Tidak ada anggota terlambat</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card">
            <div class="card-header">
                <i class="bi bi-lightning me-2"></i>Aksi Cepat
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.loans.index') }}?status=pending" class="btn btn-outline-warning">
                        <i class="bi bi-clock me-2"></i>Konfirmasi Peminjaman
                    </a>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-outline-primary">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Buku Baru
                    </a>
                    <a href="{{ route('admin.reports.loans') }}" class="btn btn-outline-success">
                        <i class="bi bi-file-earmark-bar-graph me-2"></i>Lihat Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================================================
     4. ACTIVITY LOG - Full Width
     ================================================ --}}
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-activity me-2"></i>Aktivitas Terbaru
            </div>
            <div class="card-body">
                <div class="timeline">
                    @forelse($recentActivities as $activity)
                        <div class="timeline-item">
                            <div class="timeline-marker {{ $activity['type'] === 'return' ? 'bg-success' : 'bg-primary' }}"></div>
                            <div class="timeline-content">
                                <p class="mb-0">{{ $activity['message'] }}</p>
                                <small class="text-muted">{{ $activity['time'] }}</small>
                            </div>
                            <div class="timeline-end">
                                <span class="badge bg-{{ $activity['status'] === 'overdue' ? 'danger' : ($activity['status'] === 'returned' ? 'success' : 'primary') }}">
                                    {{ ucfirst($activity['status']) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center py-3">Belum ada aktivitas</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* User Avatar Small */
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

/* Timeline */
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 1.5rem;
    padding-left: 20px;
    border-left: 2px solid var(--border-color);
}

.timeline-item:last-child {
    border-left-color: transparent;
    padding-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -7px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid var(--bg-card);
}

.timeline-marker.bg-success {
    background: var(--success-color);
}

.timeline-marker.bg-primary {
    background: var(--primary-color);
}

.timeline-content {
    flex-grow: 1;
}

.timeline-end {
    margin-left: auto;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Shared Chart Options
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.borderColor = '#334155';
    
    // Daily Loans Data
    const dailyData = @json($loansChart);
    const monthlyData = @json($monthlyLoans);
    const categoryData = @json($loansByCategory);
    
    // Daily Chart
    const loansCtx = document.getElementById('revenueChart').getContext('2d');
    let loansChart = new Chart(loansCtx, {
        type: 'line',
        data: {
            labels: dailyData.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
            }),
            datasets: [{
                label: 'Peminjaman',
                data: dailyData.map(item => item.total),
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleColor: '#f1f5f9',
                    bodyColor: '#f1f5f9',
                    borderColor: '#334155',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return context.raw + ' peminjaman';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value;
                        }
                    },
                    grid: { color: '#334155' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // Switch Chart Function
    window.switchChart = function(type) {
        const btnGroup = event.target.closest('.btn-group');
        btnGroup.querySelectorAll('.btn').forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');

        if (type === 'monthly') {
            loansChart.data.labels = monthlyData.map(item => {
                const [year, month] = item.month.split('-');
                const date = new Date(year, month - 1);
                return date.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
            });
            loansChart.data.datasets[0].data = monthlyData.map(item => item.loans);
        } else {
            loansChart.data.labels = dailyData.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
            });
            loansChart.data.datasets[0].data = dailyData.map(item => item.total);
        }
        loansChart.update();
    };

    // Category Doughnut Chart
    @if($loansByCategory->count() > 0)
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryColors = [
        '#6366f1', '#22c55e', '#f59e0b', '#ef4444', '#06b6d4', '#8b5cf6', '#ec4899'
    ];

    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: categoryData.map(item => item.category_name),
            datasets: [{
                data: categoryData.map(item => item.total_loans),
                backgroundColor: categoryColors.slice(0, categoryData.length),
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleColor: '#f1f5f9',
                    bodyColor: '#f1f5f9',
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return value + ' peminjaman (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
    @endif
});
</script>
@endpush