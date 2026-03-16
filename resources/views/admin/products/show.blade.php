@extends('layouts.admin')
@section('title', 'Detail Buku')
@section('page-title', 'Detail Buku: ' . $product->name)
@section('content')
    <div class="center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark">
                    <h6 class="mb-0 text-white">Informasi Buku</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @php
                                $mainImage = $product->primaryImage ?? ($product->images->first() ?? null);
                            @endphp
                            @if ($mainImage)
                                <img src="{{ $mainImage->image_url }}" alt="{{ $product->name }}"
                                    class="img-fluid rounded mb-3" style="max-height: 300px; object-fit: cover;"
                                    onerror="this.onerror=null; this.src='https://via.placeholder.com/300x400?text=No+Image'" />
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3"
                                    style="height: 200px;">
                                    <i class="bi bi-book text-muted fs-1"></i>
                                </div>
                            @endif
                            @if ($product->images && $product->images->count() > 1)
                                <div class="row g-2">
                                    @foreach ($product->images as $image)
                                        <div class="col-3">
                                            <img src="{{ $image->image_url }}" alt="{{ $product->name }}"
                                                class="img-thumbnail"
                                                style="width: 100%; height: 60px; object-fit: cover; cursor: pointer;"
                                                onerror="this.onerror=null; this.src='https://via.placeholder.com/100x60?text=No+Image'" />
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <table class="table table-sm">
                                <tr>
                                    <td width="150"><strong>Nama Buku</strong></td>
                                    <td>{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kategori</strong></td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Stok</strong></td>
                                    <td>
                                        @if ($product->stock <= 5)
                                            <span class="badge bg-danger fs-6">{{ $product->stock }}</span>
                                        @elseif($product->stock <= 10)
                                            <span class="badge bg-warning fs-6">{{ $product->stock }}</span>
                                        @else
                                            <span class="badge bg-success fs-6">{{ $product->stock }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td>
                                        @if ($product->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat</strong></td>
                                    <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Diupdate</strong></td>
                                    <td>{{ $product->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                            <div class="d-flex gap-2 mt-3">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil me-2"></i>Edit Buku
                                </a>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                                </a>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6>Deskripsi Buku</h6>
                            <p class="text-muted">{{ $product->description ?? 'Tidak ada deskripsi' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-dark">
                    <h6 class="mb-0 text-white">Statistik</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">{{ $product->orderItems->sum('quantity') ?? 0 }}</h4>
                                <small class="text-muted">Dipinjam</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-1">{{ $product->orderItems->count() ?? 0 }}</h4>
                            <small class="text-muted">Transaksi</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark">
                    <h6 class="mb-0 text-white">Riwayat Peminjaman</h6>
                </div>
                <div class="card-body p-0">
                    @if ($product->orderItems && $product->orderItems->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach ($product->orderItems->take(5) as $item)
                                <div class="list-group-item px-3 py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted">{{ $item->order->order_number ?? '#' }}</small>
                                            <br>
                                            <small>{{ $item->order->user->name ?? 'N/A' }}</small>
                                        </div>
                                        <div class="text-end">
                                            <small class="fw-bold">{{ $item->quantity }} buku</small>
                                            <br>
                                            <small class="text-muted">{{ $item->order->created_at->format('d/m') }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-book fs-1 d-block mb-2"></i>
                            Belum ada peminjaman
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function changeMainImage(src) {
            document.querySelector('.col-md-4 > img').src = src;
        }
    </script>
@endpush
