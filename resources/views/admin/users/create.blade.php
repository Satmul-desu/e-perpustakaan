@extends('layouts.admin')
@section('title', 'Tambah Anggota')
@section('page-title', 'Tambah Anggota')
@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 text-dark"><i class="bi bi-person-plus me-2 text-primary"></i>Buat Anggota Baru</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control bg-light @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Masukkan nama lengkap...">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold">Gelar (Opsional)</label>
                        <input type="text" name="title" class="form-control bg-light @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Contoh: S.Kom, M.T, dll...">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    

                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold">Email</label>
                        <input type="email" name="email" class="form-control bg-light @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="Masukkan alamat email aktif...">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-bold">Password</label>
                            <input type="password" name="password" class="form-control bg-light @error('password') is-invalid @enderror" required placeholder="Minimal 8 karakter...">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <label class="form-label text-dark fw-bold">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control bg-light" required placeholder="Ulangi password...">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label text-dark fw-bold">Role / Peran</label>
                        <select name="role" class="form-select bg-light @error('role') is-invalid @enderror" required>
                            <option value="">Pilih Role...</option>
                            <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Anggota Biasa (Customer)</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator (Admin)</option>
                        </select>
                        <small class="text-muted mt-1 d-block">Pemberian wewenang role kepada pengguna baru berdasarkan kebutuhan.</small>
                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <hr>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4"><i class="bi bi-arrow-left me-1"></i> Batal / Kembali</a>
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Adjust input styles slightly for visibility against white card */
.form-control.bg-light, .form-select.bg-light {
    border: 1px solid #ced4da;
    color: #212529 !important;
}
.form-control.bg-light:focus, .form-select.bg-light:focus {
    background-color: #fff !important;
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.25rem rgba(59,130,246,.25);
}
</style>
@endsection
