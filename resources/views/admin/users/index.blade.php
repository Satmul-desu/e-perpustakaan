@extends('layouts.admin')
@section('title', 'Kelola Anggota')
@section('page-title', 'Kelola Anggota')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th colspan="7" class="py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0 text-white"><i class="bi bi-people me-2"></i>Daftar Anggota</h5>
                                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                                                <i class="bi bi-person-plus me-1"></i>Tambah Anggota
                                            </a>
                                        </div>
                                    </th>
                                </tr>
                                <tr class="table-light text-dark">
                                    <th>No</th>
                                    <th>Avatar</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status Verifikasi</th>
                                    <th>Tanggal Daftar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if ($user->avatar && file_exists(public_path('storage/' . $user->avatar)))
                                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                                                    class="rounded-circle" width="40" height="40"
                                                    style="object-fit: cover; border: 2px solid #3b82f6;">
                                            @else
                                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 40px; height: 40px; background: rgba(59, 130, 246, 0.1); border: 2px solid #3b82f6;">
                                                    <i class="bi bi-person-fill"
                                                        style="color: #60a5fa; font-size: 1.2rem;"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $user->name }}</strong>
                                            @if ($user->phone)
                                                <br>
                                                <small class="text-muted">{{ $user->phone }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if ($user->role === 'admin')
                                                <span class="badge bg-danger">Admin</span>
                                            @else
                                                <span class="badge bg-primary">Anggota</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->email_verified_at)
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Terverifikasi
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-exclamation-triangle me-1"></i>Belum Verifikasi
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="bi bi-people fs-1 d-block mb-2"></i>
                                            Belum ada anggota terdaftar
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($users->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
