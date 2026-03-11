@php
    $isGoogleUser = $user->isGoogleUser();
    $isGoogleAvatar = $user->isGoogleAvatar();
    $avatarUrl = $user->avatar_url;
@endphp
<p class="text-muted small">
    Upload foto profil kamu. Format yang didukung: JPG, PNG, WebP. Maksimal 2MB.
    @if ($isGoogleUser)
        <span class="d-block mt-1 text-info">
            <i class="bi bi-google me-1"></i>Avatar Google akan otomatis tersinkronisasi saat login
        </span>
    @endif
</p>
<form method="post" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data">
    @csrf
    <div class="d-flex align-items-center gap-4">
        <div class="position-relative">
            @if ($avatarUrl && filter_var($avatarUrl, FILTER_VALIDATE_URL))
                <img id="avatar-preview" class="rounded-circle object-fit-cover border"
                    style="width: 100px; height: 100px;" src="{{ $avatarUrl }}" alt="{{ $user->name }}">
                @if (!$isGoogleUser || !$isGoogleAvatar)
                    <button type="button"
                        onclick="if(confirm('Hapus foto profil?')) document.getElementById('delete-avatar-form').submit()"
                        class="btn btn-danger btn-sm rounded-circle position-absolute top-0 start-100 translate-middle p-1"
                        style="width: 24px; height: 24px; line-height: 1;" title="Hapus foto">
                        &times;
                    </button>
                @endif
            @else
                <div id="avatar-preview" class="rounded-circle d-flex align-items-center justify-content-center border"
                    style="width: 100px; height: 100px; background: rgba(59, 130, 246, 0.1); border: 3px solid #3b82f6;">
                    <i class="bi bi-person-fill" style="color: #60a5fa; font-size: 3rem;"></i>
                </div>
            @endif
            @if ($isGoogleUser && $isGoogleAvatar)
                <div class="position-absolute bottom-0 start-100 translate-middle">
                    <span class="badge bg-primary rounded-pill" style="font-size: 0.65rem;">
                        <i class="bi bi-google"></i>
                    </span>
                </div>
            @endif
        </div>
        <div class="flex-grow-1">
            <input type="file" name="avatar" id="avatar" accept="image/*" onchange="previewAvatar(event)"
                class="form-control @error('avatar') is-invalid @enderror">
            @error('avatar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="mt-3">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-upload me-2"></i>Simpan Foto
        </button>
        @if ($isGoogleUser && $isGoogleAvatar)
            <small class="text-muted ms-2">
                Upload foto baru untuk menggunakan avatar kustom
            </small>
        @endif
    </div>
</form>
<form id="delete-avatar-form" action="{{ route('profile.avatar.destroy') }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>
<script>
    function previewAvatar(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatar-preview');
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    preview.outerHTML = `<img id="avatar-preview"
                        class="rounded-circle object-fit-cover border position-relative"
                        style="width: 100px; height: 100px;"
                        src="${e.target.result}"
                        alt="{{ $user->name }}">`;
                }
            }
            reader.readAsDataURL(file);
        }
    }
</script>
<style>
    #avatar-preview {
        transition: all 0.3s ease;
    }

    #avatar-preview:hover {
        box-shadow: 0 0 15px rgba(59, 130, 246, 0.4);
    }
</style>
