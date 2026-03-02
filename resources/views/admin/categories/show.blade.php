{{-- ================================================
     FILE: resources/views/admin/categories/show.blade.php
     FUNGSI: Halaman detail kategori
     ================================================ --}}

@extends('layouts.admin')

@section('title', 'Detail Kategori')
@section('page-title', 'Detail Kategori: ' . $category->name)

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0">Informasi Kategori</h6>
            </div>
            <div class="card-body">
                @if($category->image)
                    <div class="text-center mb-3">
                        <img src="{{ Storage::url($category->image) }}"
                             alt="{{ $category->name }}"
                             class="img-fluid rounded">
                    </div>
                @endif

                <table class="table table-sm">
                    <tr>
                        <td><strong>Nama</strong></td>
                        <td>{{ $category->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Slug</strong></td>
                        <td><code>{{ $category->slug }}</code></td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>
                            @if($category->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Dibuat</strong></td>
                        <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Diupdate</strong></td>
                        <td>{{ $category->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>

                <div class="d-grid gap-2">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>Edit Kategori
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Produk dalam Kategori ({{ $category->products->count() }})</h6>
                <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Produk
                </a>
            </div>
            <div class="card-body">
                @if($category->products->count() > 0)
                    <div class="row g-3">
                        @foreach($category->products as $product)
                            <div class="col-md-6">
                                <div class="card h-100 border">
                                    <div class="row g-0">
                                        <div class="col-4">
                                            @if($product->primaryImage)
                                                <img src="{{ Storage::url($product->primaryImage->image_path) }}"
                                                     class="img-fluid rounded-start h-100"
                                                     style="object-fit: cover;"
                                                     alt="{{ $product->name }}">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                                    <i class="bi bi-image text-muted fs-1"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-8">
                                            <div class="card-body">
                                                <h6 class="card-title">{{ Str::limit($product->name, 30) }}</h6>
                                                <p class="card-text small text-muted">{{ Str::limit($product->description, 50) }}</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <strong class="text-success">Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                                                    <small class="text-muted">Stok: {{ $product->stock }}</small>
                                                </div>
                                                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-outline-primary mt-2">
                                                    Lihat Detail
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-box-seam fs-1 d-block mb-3"></i>
                        <h6>Belum ada produk</h6>
                        <p class="mb-0">Tambahkan produk pertama untuk kategori ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
