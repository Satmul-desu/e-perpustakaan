@extends('layouts.admin')
@section('title', 'Tambah Buku')
@section('page-title', 'Tambah Buku Baru')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark">
                    <h6 class="mb-0 text-white">Informasi Buku</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle">
                                <tr>
                                    <th style="width: 20%;"><label for="name" class="form-label mb-0">Nama Buku <span class="text-danger">*</span></label></th>
                                    <td>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="category_id" class="form-label mb-0">Kategori <span class="text-danger">*</span></label></th>
                                    <td>
                                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                            <option value="">Pilih Kategori</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="description" class="form-label mb-0">Deskripsi</label></th>
                                    <td>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="stock" class="form-label mb-0">Stok <span class="text-danger">*</span></label></th>
                                    <td>
                                        <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', 1) }}" min="0" required>
                                        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </td>
                                </tr>
                                <tr>
                                    <th><label class="form-label mb-0">Status</label></th>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">Buku Aktif (tampil di katalog)</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="images" class="form-label mb-0">Gambar Buku</label></th>
                                    <td>
                                        <input type="file" class="form-control @error('images') is-invalid @enderror" id="images" name="images[]" multiple accept="image/*">
                                        <div class="form-text">Pilih satu atau lebih gambar. Gambar pertama akan dijadikan sampul utama.</div>
                                        @error('images')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <td>
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary px-4">Kembali</a>
                                            <button type="submit" class="btn btn-primary px-4">Simpan Buku</button>
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
