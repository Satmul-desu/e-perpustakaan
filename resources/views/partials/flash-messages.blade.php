{{-- ================================================
     FILE: resources/views/partials/flash-messages.blade.php
     FUNGSI: Menampilkan notifikasi flash messages (Dark Theme)
     ================================================ --}}

{{-- Success Message --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0" role="alert" style="background: rgba(34, 197, 94, 0.2); border: 1px solid #22c55e; color: #86efac;">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Error Message --}}
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0" role="alert" style="background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #fca5a5;">
        <i class="bi bi-exclamation-triangle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Info Message --}}
@if(session('info'))
    <div class="alert alert-info alert-dismissible fade show border-0" role="alert" style="background: rgba(59, 130, 246, 0.2); border: 1px solid #3b82f6; color: #93c5fd;">
        <i class="bi bi-info-circle me-2"></i>
        {{ session('info') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Validation Errors --}}
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0" role="alert" style="background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #fca5a5;">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Terjadi kesalahan:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
@endif

