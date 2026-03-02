{{-- ================================================
     FILE: resources/views/admin/products/show.blade.php
     FUNGSI: Halaman detail produk admin
     ================================================ --}}

@extends('layouts.admin')

@section('title', 'Detail Produk')
@section('page-title', 'Detail Produk: ' . $product->name)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0">Informasi Produk</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        @if($product->primaryImage)
                            <img src="{{ $product->primaryImage->image_url }}"
                                 alt="{{ $product->name }}"
                                 class="img-fluid rounded mb-3">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3"
                                 style="height: 200px;">
                                <i class="bi bi-image text-muted fs-1"></i>
                            </div>
                        @endif

                        {{-- Galeri Gambar --}}
                        @if($product->images->count() > 1)
                            <div class="row g-2">
                                @foreach($product->images as $image)
                                    <div class="col-3">
                                        <img src="{{ $image->image_url }}"
                                             alt="{{ $product->name }}"
                                             class="img-thumbnail"
                                             style="width: 100%; height: 60px; object-fit: cover; cursor: pointer;"
                                             onclick="changeMainImage('{{ $image->image_url }}')">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <table class="table table-sm">
                            <tr>
                                <td width="150"><strong>Nama Produk</strong></td>
                                <td>{{ $product->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kategori</strong></td>
                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Harga</strong></td>
                                <td>
                                    <span class="h5 text-success">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Stok</strong></td>
                                <td>
                                    @if($product->stock <= 5)
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
                                    @if($product->is_active)
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
                                <i class="bi bi-pencil me-2"></i>Edit Produk
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
                        <h6>Deskripsi Produk</h6>
                        <p class="text-muted">{{ $product->description ?? 'Tidak ada deskripsi' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Statistik Produk --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0">Statistik</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-primary mb-1">{{ $product->orderItems->sum('quantity') }}</h4>
                            <small class="text-muted">Terjual</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success mb-1">{{ $product->orderItems->count() }}</h4>
                        <small class="text-muted">Transaksi</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Riwayat Order --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0">Riwayat Penjualan</h6>
            </div>
            <div class="card-body p-0">
                @if($product->orderItems->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($product->orderItems->take(5) as $item)
                            <div class="list-group-item px-3 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">{{ $item->order->order_number }}</small>
                                        <br>
                                        <small>{{ $item->order->user->name ?? 'N/A' }}</small>
                                    </div>
                                    <div class="text-end">
                                        <small class="fw-bold">{{ $item->quantity }} pcs</small>
                                        <br>
                                        <small class="text-muted">{{ $item->order->created_at->format('d/m') }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-graph-up fs-1 d-block mb-2"></i>
                        Belum ada penjualan
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
    document.querySelector('.col-md-4 img').src = src;
}
</script>
@endpush
