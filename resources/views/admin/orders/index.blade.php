{{-- ================================================
     FILE: resources/views/admin/orders/index.blade.php
     FUNGSI: Menampilkan daftar pesanan untuk admin
     ================================================ --}}

@extends('layouts.admin')

@section('title', 'Kelola Pesanan')
@section('page-title', 'Daftar Pesanan')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h6 class="mb-0">Kelola Pesanan Pelanggan</h6>
    </div>
    <div class="card-body">
        {{-- Filter & Search --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <form method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control"
                           placeholder="Cari nomor pesanan..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-secondary ms-2">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
            <div class="col-md-4">
                <form method="GET">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </form>
            </div>
        </div>

        {{-- Tabel Pesanan --}}
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status Pembayaran</th>
                        <th>Status Pesanan</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders ?? [] as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td>{{ $order->user->name ?? 'N/A' }}</td>
                            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>
                                @if($order->payment_status == 'paid')
                                    <span class="badge bg-success">Lunas</span>
                                @elseif($order->payment_status == 'pending')
                                    <span class="badge bg-warning">Menunggu</span>
                                @else
                                    <span class="badge bg-danger">Gagal</span>
                                @endif
                            </td>
                            <td>
                                @include('components.order-status-badge', ['status' => $order->status])
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($order->status == 'pending')
                                        <button type="button" class="btn btn-sm btn-outline-success"
                                                onclick="updateStatus({{ $order->id }}, 'processing')">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Belum ada pesanan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(isset($orders) && $orders->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateStatus(orderId, status) {
    if (confirm('Yakin ubah status pesanan ini?')) {
        fetch(`{{ url('admin/orders') }}/${orderId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal update status');
            }
        });
    }
}
</script>
@endpush
