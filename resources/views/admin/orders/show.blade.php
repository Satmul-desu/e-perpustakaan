@extends('layouts.admin')
@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan: ' . $order->order_number)
@section('content')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Detail Pesanan</h6>
                    <div>
                        @include('components.order-status-badge', ['status' => $order->status])
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $item->product->primary_image_url ?? 'https://via.placeholder.com/50x50?text=No+Image' }}"
                                                    class="rounded me-3" width="50" height="50"
                                                    style="object-fit: cover;">
                                                <div>
                                                    <div class="fw-medium">{{ $item->product_name }}</div>
                                                    @if ($item->product)
                                                        <small
                                                            class="text-muted">{{ $item->product->category->name ?? '' }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="fw-medium">Rp
                                            {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-medium">Subtotal:</td>
                                    <td class="fw-medium">Rp
                                        {{ number_format($order->total_amount - $order->shipping_cost, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-medium">Ongkos Kirim:</td>
                                    <td class="fw-medium">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total:</td>
                                    <td class="fw-bold text-primary">Rp
                                        {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Status & Aksi</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Status Pesanan</label>
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>
                                    Processing</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed
                                </option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                                </option>
                            </select>
                        </form>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Pembayaran</label>
                        <div>
                            @if ($order->payment_status == 'paid')
                                <span class="badge bg-success fs-6">Lunas</span>
                            @elseif($order->payment_status == 'pending')
                                <span class="badge bg-warning fs-6">Menunggu Pembayaran</span>
                            @else
                                <span class="badge bg-danger fs-6">Gagal</span>
                            @endif
                        </div>
                    </div>
                    <div class="d-grid">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Informasi Pengiriman</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Penerima:</strong><br>
                        {{ $order->shipping_name }}
                    </div>
                    <div class="mb-2">
                        <strong>Telepon:</strong><br>
                        {{ $order->shipping_phone }}
                    </div>
                    <div class="mb-0">
                        <strong>Alamat:</strong><br>
                        {{ $order->shipping_address }}
                    </div>
                </div>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Informasi Pelanggan</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $order->user->avatar_url }}" class="rounded-circle me-3" width="50"
                            height="50">
                        <div>
                            <div class="fw-medium">{{ $order->user->name }}</div>
                            <small class="text-muted">{{ $order->user->email }}</small>
                        </div>
                    </div>
                    <div class="mb-2">
                        <strong>Tanggal Pesan:</strong><br>
                        {{ $order->created_at->format('d F Y, H:i') }}
                    </div>
                    <div class="mb-0">
                        <strong>Terakhir Update:</strong><br>
                        {{ $order->updated_at->format('d F Y, H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
