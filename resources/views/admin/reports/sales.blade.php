{{-- ================================================
     FILE: resources/views/admin/reports/sales.blade.php
     FUNGSI: Laporan penjualan dengan filter, charts, dan analisis lengkap
     ================================================ --}}

@extends('layouts.admin')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@section('content')
{{-- ================================================
     1. FILTER SECTION
     ================================================ --}}
<div class="card mb-4">
    <div class="card-header">
        <i class="bi bi-funnel me-2"></i>Filter Laporan
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="date_from" class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" id="date_from" name="date_from" 
                       value="{{ request('date_from', $dateFrom) }}">
            </div>
            <div class="col-md-3">
                <label for="date_to" class="form-label">Tanggal Akhir</label>
                <input type="date" class="form-control" id="date_to" name="date_to" 
                       value="{{ request('date_to', $dateTo) }}">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status Pesanan</label>
                <select name="status" class="form-select" id="status">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.reports.sales') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </div>
        </form>
        
        {{-- Quick Date Filters --}}
        <div class="mt-3">
            <span class="text-muted me-2">Filter Cepat:</span>
            <div class="btn-group btn-group-sm">
                <a href="{{ route('admin.reports.sales', ['date_from' => now()->today()->toDateString(), 'date_to' => now()->today()->toDateString()]) }}" 
                   class="btn btn-outline-secondary {{ $dateFrom == $dateTo && $dateFrom == now()->today()->toDateString() ? 'active' : '' }}">
                    Hari Ini
                </a>
                <a href="{{ route('admin.reports.sales', ['date_from' => now()->startOfWeek()->toDateString(), 'date_to' => now()->endOfWeek()->toDateString()]) }}" 
                   class="btn btn-outline-secondary">
                    Minggu Ini
                </a>
                <a href="{{ route('admin.reports.sales', ['date_from' => now()->startOfMonth()->toDateString(), 'date_to' => now()->endOfMonth()->toDateString()]) }}" 
                   class="btn btn-outline-secondary {{ $dateFrom == now()->startOfMonth()->toDateString() ? 'active' : '' }}">
                    Bulan Ini
                </a>
                <a href="{{ route('admin.reports.sales', ['date_from' => now()->subMonths(3)->startOfMonth()->toDateString(), 'date_to' => now()->endOfMonth()->toDateString()]) }}" 
                   class="btn btn-outline-secondary">
                    3 Bulan
                </a>
                <a href="{{ route('admin.reports.sales', ['date_from' => now()->subYear()->toDateString(), 'date_to' => now()->toDateString()]) }}" 
                   class="btn btn-outline-secondary">
                    1 Tahun
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ================================================
     2. SUMMARY STATISTICS CARDS
     ================================================ --}}
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="stat-label">Total Pendapatan</div>
            <div class="stat-value">Rp {{ number_format($summary->total_revenue ?? 0, 0, ',', '.') }}</div>
            <div class="stat-change">
                <span>Periode: {{ \Carbon\Carbon::parse($dateFrom)->format('d M') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</span>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="bi bi-receipt"></i>
            </div>
            <div class="stat-label">Total Pesanan</div>
            <div class="stat-value">{{ number_format($summary->total_orders ?? 0) }}</div>
            <div class="stat-change">
                {{ number_format($summary->unique_customers ?? 0) }} pelanggan unik
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon info">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div class="stat-label">Rata-rata Pesanan</div>
            <div class="stat-value">Rp {{ number_format($summary->avg_order_value ?? 0, 0, ',', '.') }}</div>
            <div class="stat-change">
                <span>Nilai rata-rata</span>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="bi bi-truck"></i>
            </div>
            <div class="stat-label">Total Ongkos Kirim</div>
            <div class="stat-value">Rp {{ number_format($summary->total_shipping ?? 0, 0, ',', '.') }}</div>
            <div class="stat-change">
                <span>Biaya pengiriman</span>
            </div>
        </div>
    </div>
</div>

{{-- ================================================
     3. CHARTS SECTION
     ================================================ --}}
<div class="row g-4 mb-4">
    {{-- Daily Trend Chart --}}
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-graph-up me-2"></i>Tren Penjualan Harian
            </div>
            <div class="card-body">
                <canvas id="dailyTrendChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    {{-- Category Distribution --}}
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-pie-chart me-2"></i>Penjualan per Kategori
            </div>
            <div class="card-body">
                @if($byCategory->count() > 0)
                    <canvas id="categoryChart" height="300"></canvas>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox display-4"></i>
                        <p class="mt-2">Tidak ada data</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ================================================
     4. CATEGORY ANALYSIS TABLE
     ================================================ --}}
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-folder me-2"></i>Analisis per Kategori</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th class="text-center">Produk Terjual</th>
                                <th class="text-center">Pendapatan</th>
                                <th class="text-end">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalRevenue = $byCategory->sum('total_revenue'); @endphp
                            @forelse($byCategory as $category)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="category-dot me-2" 
                                                 style="background: hsl({{ rand(0, 360) }}, 70%, 60%);"></div>
                                            {{ $category->category_name }}
                                        </div>
                                    </td>
                                    <td class="text-center">{{ number_format($category->total_sold) }}</td>
                                    <td class="text-center fw-medium">Rp {{ number_format($category->total_revenue, 0, ',', '.') }}</td>
                                    <td class="text-end">
                                        <div class="progress" style="height: 6px; width: 80px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ ($category->total_revenue / $totalRevenue) * 100 }}%; background: var(--primary-color);">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Top Products --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-trophy me-2"></i>Produk Terlaris
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Terjual</th>
                                <th class="text-end">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProductsByCategory->flatten()->take(10) as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-secondary me-2">#{{ $loop->index + 1 }}</span>
                                            {{ Str::limit($product->product_name, 30) }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ number_format($product->total_sold) }}</span>
                                    </td>
                                    <td class="text-end fw-medium">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================================================
     5. DETAILED ORDERS TABLE
     ================================================ --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-table me-2"></i>Detail Pesanan</span>
        <div class="d-flex gap-2">
            {{-- Export Word --}}
            <a href="{{ route('admin.reports.export-word', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}" 
               class="btn btn-primary btn-sm">
                <i class="bi bi-file-earmark-word me-1"></i> Export Word
            </a>
            
            {{-- Export Excel button disabled - maatwebsite/excel not installed --}}
            {{--
            <a href="{{ route('admin.reports.export-sales', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}" 
               class="btn btn-success btn-sm">
                <i class="bi bi-download me-1"></i> Export Excel
            </a>
            --}}
            {{-- Temporarily disabled until package installed --}}
            @if(false)
            <a href="{{ route('admin.reports.export-sales', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}" 
               class="btn btn-success btn-sm">
                <i class="bi bi-download me-1"></i> Export Excel
            </a>
            @endif
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Items</th>
                        <th>Subtotal</th>
                        <th>Ongkir</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
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
                                    <div>
                                        <div class="fw-medium">{{ $order->user->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $order->user->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
<td>{{ $order->orderItems->count() }} item</td>
                            <td>Rp {{ number_format($order->total_amount - $order->shipping_cost, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                            <td class="fw-bold text-success">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>
                                @include('components.order-status-badge', ['status' => $order->status])
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox display-3 text-muted"></i>
                                <p class="mt-2 text-muted">Tidak ada pesanan pada periode ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- Pagination --}}
    @if($orders->hasPages())
        <div class="card-footer d-flex justify-content-center">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.category-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    flex-shrink: 0;
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Shared Chart Options
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.borderColor = '#334155';
    
    const dailyData = @json($dailyTrend);
    const categoryData = @json($byCategory);
    
    // Daily Trend Chart
    const dailyCtx = document.getElementById('dailyTrendChart').getContext('2d');
    new Chart(dailyCtx, {
        type: 'bar',
        data: {
            labels: dailyData.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
            }),
            datasets: [
                {
                    label: 'Pendapatan',
                    data: dailyData.map(item => item.revenue),
                    backgroundColor: 'rgba(99, 102, 241, 0.8)',
                    borderColor: '#6366f1',
                    borderWidth: 1,
                    borderRadius: 6,
                    yAxisID: 'y'
                },
                {
                    label: 'Jumlah Pesanan',
                    data: dailyData.map(item => item.orders),
                    type: 'line',
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleColor: '#f1f5f9',
                    bodyColor: '#f1f5f9',
                    borderColor: '#334155',
                    borderWidth: 1,
                    padding: 12
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                            if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                            return 'Rp ' + value;
                        }
                    },
                    grid: { color: '#334155' }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    grid: { drawOnChartArea: false }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
    
    // Category Doughnut Chart
    @if($byCategory->count() > 0)
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryColors = [
        '#6366f1', '#22c55e', '#f59e0b', '#ef4444', '#06b6d4', '#8b5cf6', '#ec4899', '#14b8a6'
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
            cutout: '65%',
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
