{{-- ================================================
     FILE: resources/views/admin/products/edit.blade.php
     FUNGSI: Form untuk mengedit buku yang sudah ada
     ================================================ --}}

@extends('layouts.admin')

@section('title', 'Edit Buku')
@section('page-title', 'Edit Buku: ' . $product->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark">
                <h6 class="mb-0 text-white">Edit Informasi Buku</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Nama Buku --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Buku <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kategori --}}
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror"
                                id="category_id" name="category_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Stok --}}
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('stock') is-invalid @enderror"
                               id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Buku Aktif (tampil di katalog)
                            </label>
                        </div>
                    </div>

                    {{-- Gambar Buku yang Ada --}}
                    @if($product->images && $product->images->count() > 0)
                        <div class="mb-3">
                            <label class="form-label">Gambar Buku Saat Ini</label>
                            <div class="row g-3">
                                @foreach($product->images as $image)
                                    <div class="col-md-3">
                                        <div class="card">
                                            <img src="{{ $image->image_url }}" class="card-img-top" alt="Book Image"
                                                 style="height: 150px; object-fit: cover;"
                                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/200x150?text=No+Image'">
                                            <div class="card-body p-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                           id="delete_image_{{ $image->id }}"
                                                           name="delete_images[]" value="{{ $image->id }}">
                                                    <label class="form-check-label small" for="delete_image_{{ $image->id }}">
                                                        Hapus
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                           id="primary_{{ $image->id }}"
                                                           name="primary_image" value="{{ $image->id }}"
                                                           {{ $image->is_primary ? 'checked' : '' }}>
                                                    <label class="form-check-label small" for="primary_{{ $image->id }}">
                                                        Utama
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-text">
                                Centang gambar yang ingin dihapus. Pilih satu gambar sebagai sampul utama.
                            </div>
                        </div>
                    @endif

                    {{-- Upload Gambar Baru --}}
                    <div class="mb-3">
                        <label for="images" class="form-label">Tambah Gambar Baru</label>
                        <input type="file" class="form-control @error('images') is-invalid @enderror"
                               id="images" name="images[]" multiple accept="image/*">
                        <div class="form-text">
                            Pilih gambar tambahan jika ingin menambah. Biarkan kosong jika tidak ada perubahan.
                        </div>
                        @error('images')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Update Buku
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
