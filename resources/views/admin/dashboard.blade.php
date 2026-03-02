{{-- ================================================
     FILE: resources/views/admin/dashboard.blade.php
     FUNGSI: Halaman dashboard admin modern dengan statistik lengkap
     ================================================ --}}

@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
{{-- ================================================
     1. STATISTIK UTAMA (Metric Cards)
     ================================================ --}}
<div class="row g-4 mb-4">
    {{-- Total Pendapatan --}}
    <div class="col-md-3">
        <div class="stat-card animate-fade-in">
            <div class="stat-icon success">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="stat-label">Total Pendapatan</div>
            <div class="stat-value">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
            <div class="stat-change positive">
                <i class="bi bi-arrow-up"></i>
                <span>Dari pesanan lunas</span>
            </div>
        </div>
    </div>

    {{-- Total Pesanan --}}
    <div class="col-md-3">
        <div class="stat-card animate-fade-in animate-delay-1">
            <div class="stat-icon primary">
                <i class="bi bi-receipt"></i>
            </div>
            <div class="stat-label">Total Pesanan</div>
            <div class="stat-value">{{ number_format($stats['total_orders']) }}</div>
            <div class="stat-change">
                <span>Semua status</span>
            </div>
        </div>
    </div>

    {{-- Pesanan Pending --}}
    <div class="col-md-3">
        <div class="stat-card animate-fade-in animate-delay-2">
            <div class="stat-icon warning">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="stat-label">Pesanan Pending</div>
            <div class="stat-value">{{ number_format($stats['pending_orders']) }}</div>
            @if($stats['pending_orders'] > 0)
                <div class="stat-change" style="color: var(--warning-color)">
                    <span>Perlu konfirmasi</span>
                </div>
            @else
                <div class="stat-change positive">
                    <span>Semua terkendali</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Sedang Diproses --}}
    <div class="col-md-3">
        <div class="stat-card animate-fade-in animate-delay-3">
            <div class="stat-icon info">
                <i class="bi bi-gear-wide-connected"></i>
            </div>
            <div class="stat-label">Sedang Diproses</div>
            <div class="stat-value">{{ number_format($stats['processing_orders']) }}</div>
            <div class="stat-change">
                <span>Pesanan aktif</span>
            </div>
        </div>
    </div>
</div>

{{-- ================================================
     2. STATISTIK SEKUNDER
     ================================================ --}}
<div class="row g-4 mb-4">
    {{-- Total Produk --}}
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="stat-label">Total Produk</div>
            <div class="stat-value">{{ number_format($stats['total_products']) }}</div>
            <div class="stat-change">
                {{ number_format($stats['total_categories']) }} kategori
            </div>
        </div>
    </div>

    {{-- Total Pelanggan --}}
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(21, 128, 61, 0.2)); color: #4ade80;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-label">Total Pelanggan</div>
            <div class="stat-value">{{ number_format($stats['total_customers']) }}</div>
            <div class="stat-change positive">
                <span>Pelanggan terdaftar</span>
            </div>
        </div>
    </div>

    {{-- Stok Rendah --}}
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div class="stat-label">Stok Rendah (≤5)</div>
            <div class="stat-value">{{ number_format($stats['low_stock']) }}</div>
            @if($stats['low_stock'] > 0)
                <div class="stat-change" style="color: var(--warning-color)">
                    <span>Perlu restock!</span>
                </div>
            @else
                <div class="stat-change positive">
                    <span>Semua aman</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Rata-rata Pesanan --}}
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, rgba(168, 85, 247, 0.2), rgba(126, 34, 206, 0.2)); color: #c084fc;">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div class="stat-label">Rata-rata Pesanan</div>
            <div class="stat-value">Rp {{ number_format($stats['avg_order_value'], 0, ',', '.') }}</div>
            <div class="stat-change">
                <span>Nilai rata-rata</span>
            </div>
        </div>
    </div>
</div>

{{-- ================================================
     3. CHARTS SECTION
     ================================================ --}}
<div class="row g-4 mb-4">
    {{-- Revenue Chart (7 Hari) --}}
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-graph-up me-2"></i>Pendapatan 7 Hari Terakhir</span>
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

    {{-- Sales by Category (Doughnut) --}}
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-pie-chart me-2"></i>Penjualan per Kategori (30 hari)
            </div>
            <div class="card-body">
                @if($salesByCategory->count() > 0)
                    <canvas id="categoryChart" height="300"></canvas>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox display-4"></i>
                        <p class="mt-2">Belum ada data penjualan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ================================================
     4. PESANAN TERBARU & PRODUK TERLARIS
     ================================================ --}}
<div class="row g-4">
    {{-- Recent Orders --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2"></i>Pesanan Terbaru</span>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>No. Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none fw-medium">
                                            #{{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar-sm me-2">
                                                {{ substr($order->user->name ?? 'U', 0, 2) }}
                                            </div>
                                            <span>{{ $order->user->name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
<td>{{ $order->orderItems->count() }} item</td>
                                    <td class="fw-medium text-success">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        @include('components.order-status-badge', ['status' => $order->status])
                                    </td>
                                    <td class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="bi bi-inbox display-3 text-muted"></i>
                                        <p class="mt-2 text-muted">Belum ada pesanan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Top Products --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-trophy me-2"></i>Produk Terlaris</span>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                    @forelse($topProducts as $index => $product)
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
                        <img src="{{ $product->primaryImage?->image_url ?? 'https://via.placeholder.com/50' }}"
                             class="rounded me-3"
                             width="50" height="50"
                             style="object-fit: cover;"
                             onerror="this.src='https://via.placeholder.com/50?text=No+Image'">
                        <div class="flex-grow-1">
                            <h6 class="mb-1" style="font-size: 0.9rem;">{{ Str::limit($product->name, 25) }}</h6>
                            <small class="text-success fw-medium">{{ number_format($product->sold_count) }} terjual</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-light">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-4 text-muted"></i>
                        <p class="mt-2 text-muted">Belum ada produk terjual</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightning me-2"></i>Aksi Cepat
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.orders.index') }}?status=pending" class="btn btn-outline-warning">
                        <i class="bi bi-clock me-2"></i>Konfirmasi Pesanan
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Produk Baru
                    </a>
                    <a href="{{ route('admin.reports.sales') }}" class="btn btn-outline-success">
                        <i class="bi bi-file-earmark-bar-graph me-2"></i>Lihat Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================================================
     5. ACTIVITY LOG
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
                            <div class="timeline-marker {{ $activity['type'] === 'payment' ? 'bg-success' : 'bg-primary' }}"></div>
                            <div class="timeline-content">
                                <p class="mb-0">{{ $activity['message'] }}</p>
                                <small class="text-muted">{{ $activity['time'] }}</small>
                            </div>
                            <div class="timeline-end">
                                <span class="badge bg-success">Rp {{ number_format($activity['amount'], 0, ',', '.') }}</span>
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
    
    // Daily Revenue Data
    const dailyData = @json($revenueChart);
    const monthlyData = @json($monthlySales);
    const categoryData = @json($salesByCategory);
    
    // Daily Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    let revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: dailyData.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
            }),
            datasets: [{
                label: 'Pendapatan',
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
                            return 'Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) {
                                return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                            } else if (value >= 1000) {
                                return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                            }
                            return 'Rp ' + value;
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
            revenueChart.data.labels = monthlyData.map(item => {
                const [year, month] = item.month.split('-');
                const date = new Date(year, month - 1);
                return date.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
            });
            revenueChart.data.datasets[0].data = monthlyData.map(item => item.revenue);
        } else {
            revenueChart.data.labels = dailyData.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
            });
            revenueChart.data.datasets[0].data = dailyData.map(item => item.total);
        }
        revenueChart.update();
    };

    // Category Doughnut Chart
    @if($salesByCategory->count() > 0)
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryColors = [
        '#6366f1', '#22c55e', '#f59e0b', '#ef4444', '#06b6d4', '#8b5cf6', '#ec4899'
    ];

    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: categoryData.map(item => item.category_name),
            datasets: [{
                data: categoryData.map(item => item.total_revenue),
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
                            return 'Rp ' + value.toLocaleString('id-ID') + ' (' + percentage + '%)';
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
