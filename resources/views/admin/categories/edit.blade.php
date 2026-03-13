@extends('layouts.admin')
@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori: ' . $category->name)
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark">
                    <h6 class="mb-0 text-white">Form Edit Kategori</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle">
                                <tr>
                                    <th style="width: 20%;"><label for="name" class="form-label mb-0">Nama Kategori <span class="text-danger">*</span></label></th>
                                    <td>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="is_active" class="form-label mb-0">Status</label></th>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">Aktif</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="description" class="form-label mb-0">Deskripsi</label></th>
                                    <td>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="icon" class="form-label mb-0">Icon Kategori</label></th>
                                    <td>
                                        <select class="form-select @error('icon') is-invalid @enderror" id="icon" name="icon">
                                            <option value="">-- Pilih Icon --</option>
                                            <option value="bi bi-book" {{ old('icon', $category->icon) == 'bi bi-book' ? 'selected' : '' }}>📚 Buku</option>
                                            <option value="bi bi-journal-text" {{ old('icon', $category->icon) == 'bi bi-journal-text' ? 'selected' : '' }}>📕 Jurnal</option>
                                            <option value="bi bi-mortarboard" {{ old('icon', $category->icon) == 'bi bi-mortarboard' ? 'selected' : '' }}>🎓 Pendidikan</option>
                                            <option value="bi bi-globe" {{ old('icon', $category->icon) == 'bi bi-globe' ? 'selected' : '' }}>🌐 Umum</option>
                                            <option value="bi bi-laptop" {{ old('icon', $category->icon) == 'bi bi-laptop' ? 'selected' : '' }}>💻 Komputer</option>
                                            <option value="bi bi-phone" {{ old('icon', $category->icon) == 'bi bi-phone' ? 'selected' : '' }}>📱 Elektronik</option>
                                            <option value="bi bi-controller" {{ old('icon', $category->icon) == 'bi bi-controller' ? 'selected' : '' }}>🎮 Games</option>
                                            <option value="bi bi-music-note" {{ old('icon', $category->icon) == 'bi bi-music-note' ? 'selected' : '' }}>🎵 Musik</option>
                                            <option value="bi bi-film" {{ old('icon', $category->icon) == 'bi bi-film' ? 'selected' : '' }}>🎬 Film</option>
                                            <option value="bi bi-palette" {{ old('icon', $category->icon) == 'bi bi-palette' ? 'selected' : '' }}>🎨 Seni</option>
                                            <option value="bi bi-tshirt" {{ old('icon', $category->icon) == 'bi bi-tshirt' ? 'selected' : '' }}>👕 Fashion</option>
                                            <option value="bi bi-cup-hot" {{ old('icon', $category->icon) == 'bi bi-cup-hot' ? 'selected' : '' }}>☕ Kuliner</option>
                                            <option value="bi bi-heart-pulse" {{ old('icon', $category->icon) == 'bi bi-heart-pulse' ? 'selected' : '' }}>❤️ Kesehatan</option>
                                            <option value="bi bi-bicycle" {{ old('icon', $category->icon) == 'bi bi-bicycle' ? 'selected' : '' }}>🚴 Olahraga</option>
                                            <option value="bi bi-house" {{ old('icon', $category->icon) == 'bi bi-house' ? 'selected' : '' }}>🏠 Rumah</option>
                                            <option value="bi bi-gift" {{ old('icon', $category->icon) == 'bi bi-gift' ? 'selected' : '' }}>🎁 Hadiah</option>
                                            <option value="bi bi-star" {{ old('icon', $category->icon) == 'bi bi-star' ? 'selected' : '' }}>⭐ Favorit</option>
                                            <option value="bi bi-folder" {{ old('icon', $category->icon) == 'bi bi-folder' ? 'selected' : '' }}>📁 Folder</option>
                                            <option value="bi bi-tag" {{ old('icon', $category->icon) == 'bi bi-tag' ? 'selected' : '' }}>🏷️ Tag</option>
                                        </select>
                                        @error('icon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Pilih icon yang mewakili kategori produk Anda.</div>
                                    </td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary px-4">Update</button>
                                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary px-4">Kembali</a>
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
