<?php

// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Instance of ProfileService.
     */
    protected $profileService;

    /**
     * Create a new controller instance.
     */
    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Menampilkan form edit profil.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            // Kirim data user yang sedang login ke view
            'user' => $request->user(),
        ]);
    }

    /**
     * Mengupdate avatar saja (tanpa field lain).
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp'],
        ], [
            'avatar.required' => 'Foto profil wajib diunggah.',
            'avatar.image' => 'File harus berupa gambar.',
            'avatar.mimes' => 'Format foto harus berupa jpeg, png, jpg, atau webp.',
            'avatar.uploaded' => 'Gagal mengunggah foto. Hal ini mungkin karena file terlalu besar bagi sistem server.',
        ]);

        if ($request->hasFile('avatar')) {
            $this->profileService->updateAvatar($request->user(), $request->file('avatar'));
        }

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }

    /**
     * Mengupdate informasi profil user.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $avatar = $request->file('avatar');

        // Pisahkan avatar dari data lain untuk penanganan manual,
        unset($validated['avatar']);

        $this->profileService->updateProfile($request->user(), $validated, $avatar);

        return Redirect::route('profile.edit')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Menghapus avatar (tombol "Hapus Foto").
     */
    public function deleteAvatar(Request $request): RedirectResponse
    {
        $this->profileService->deleteAvatar($request->user());

        return back()->with('success', 'Foto profil berhasil dihapus.');
    }

    /**
     * Update password user.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $this->profileService->updatePassword($request->user(), $validated['password']);

        return back()->with('status', 'password-updated');
    }

    /**
     * Menghapus akun user permanen.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validasi password untuk keamanan sebelum hapus akun
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $this->profileService->deleteAccount($user);

        // Invalidate session agar tidak bisa dipakai lagi (Security)
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
