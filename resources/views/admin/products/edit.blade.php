@extends('layouts.admin')
@section('title', 'Edit Buku')
@section('page-title', 'Edit Buku: ' . $product->name)
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark">
                    <h6 class="mb-0 text-white">Edit Informasi Buku</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle">
                                <tr>
                                    <th style="width: 20%;"><label for="name" class="form-label mb-0">Nama Buku <span class="text-danger">*</span></label></th>
                                    <td>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="category_id" class="form-label mb-0">Kategori <span class="text-danger">*</span></label></th>
                                    <td>
                                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                            <option value="">Pilih Kategori</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="description" class="form-label mb-0">Deskripsi</label></th>
                                    <td>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="stock" class="form-label mb-0">Stok <span class="text-danger">*</span></label></th>
                                    <td>
                                        <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required>
                                        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </td>
                                </tr>
                                <tr>
                                    <th><label class="form-label mb-0">Status</label></th>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">Buku Aktif (tampil di katalog)</label>
                                        </div>
                                    </td>
                                </tr>
                                @if ($product->images && $product->images->count() > 0)
                                <tr>
                                    <th><label class="form-label mb-0">Gambar Saat Ini</label></th>
                                    <td>
                                        <div class="row g-3">
                                            @foreach ($product->images as $image)
                                                <div class="col-md-3">
                                                    <div class="card bg-dark bg-opacity-10 border-0">
                                                        <img src="{{ $image->image_url }}" class="card-img-top rounded" alt="Book Image" style="height: 150px; object-fit: cover;" onerror="this.onerror=null; this.src='https://via.placeholder.com/150x200?text=No+Image'" />
                                                        <div class="card-body p-2">
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input" type="checkbox" id="delete_image_{{ $image->id }}" name="delete_images[]" value="{{ $image->id }}">
                                                                <label class="form-check-label text-dark small" for="delete_image_{{ $image->id }}">Hapus</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" id="primary_{{ $image->id }}" name="primary_image" value="{{ $image->id }}" {{ $image->is_primary ? 'checked' : '' }}>
                                                                <label class="form-check-label text-dark small" for="primary_{{ $image->id }}">Jadikan Utama</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th><label for="images" class="form-label mb-0">Tambah Gambar Baru</label></th>
                                    <td>
                                        <input type="file" class="form-control @error('images') is-invalid @enderror" id="images" name="images[]" multiple accept="image/*">
                                        <div class="form-text">Biarkan kosong jika tidak ada perubahan.</div>
                                        @error('images')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <td>
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary px-4">Kembali</a>
                                            <button type="submit" class="btn btn-primary px-4">Update Buku</button>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
