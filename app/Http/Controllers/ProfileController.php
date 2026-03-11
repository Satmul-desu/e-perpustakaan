<?php

// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
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
            'avatar' => ['required', 'image', 'max:2048', 'mimes:jpeg,png,jpg,webp'],
        ]);

        $user = $request->user();

        // Handle Upload Avatar
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada dan merupakan file lokal
            if ($user->avatar &&
                ! $this->isGoogleAvatar($user->avatar) &&
                Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Generate nama file unik
            $filename = 'avatar-'.$user->id.'-'.time().'.'.$request->file('avatar')->extension();
            $path = $request->file('avatar')->storeAs('avatars', $filename, 'public');

            // Simpan ke database
            // Jika user login dengan Google, set google_id ke NULL agar menggunakan avatar lokal
            $updateData = ['avatar' => $path];
            if ($user->isGoogleUser()) {
                $updateData['google_id'] = null;
            }
            $user->update($updateData);
        }

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }

    /**
     * Helper untuk cek apakah avatar adalah URL Google
     */
    protected function isGoogleAvatar(?string $avatar): bool
    {
        if (empty($avatar)) {
            return false;
        }

        return str_starts_with($avatar, 'http://') || str_starts_with($avatar, 'https://');
    }

    /**
     * Mengupdate informasi profil user.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // 1. Handle Upload Avatar
        // Cek apakah user mengupload file baru di input 'avatar'?
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada dan merupakan file lokal
            if ($user->avatar &&
                ! $this->isGoogleAvatar($user->avatar) &&
                Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Generate nama file unik
            $filename = 'avatar-'.$user->id.'-'.time().'.'.$request->file('avatar')->extension();
            $path = $request->file('avatar')->storeAs('avatars', $filename, 'public');

            // Simpan path ke properti model
            $user->avatar = $path;

            // Jika user login dengan Google, set google_id ke NULL agar menggunakan avatar lokal
            if ($user->isGoogleUser()) {
                $user->google_id = null;
            }
        }

        // 2. Update Data Text (Nama, Email, dll)
        $user->fill($request->validated());

        // 3. Cek Perubahan Email
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // 4. Simpan ke Database
        $user->save();

        return Redirect::route('profile.edit')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Helper khusus untuk menangani logika upload avatar.
     * Mengembalikan string path file yang tersimpan.
     */
    protected function uploadAvatar(ProfileUpdateRequest $request, $user): string
    {
        // Hapus avatar lama (Garbage Collection)
        // Cek 1: Apakah user punya avatar sebelumnya?
        // Cek 2: Apakah file fisiknya benar-benar ada di storage 'public'?
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Generate nama file unik untuk mencegah bentrok nama.
        // Format: avatar-{user_id}-{timestamp}.{ext}
        $filename = 'avatar-'.$user->id.'-'.time().'.'.$request->file('avatar')->extension();

        // Simpan file ke folder: storage/app/public/avatars
        // return path relatif: "avatars/namafile.jpg"
        $path = $request->file('avatar')->storeAs('avatars', $filename, 'public');

        return $path;
    }

    /**
     * Menghapus avatar (tombol "Hapus Foto").
     */
    public function deleteAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Hapus file fisik
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);

            // Set kolom di database jadi NULL
            $user->update(['avatar' => null]);
        }

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

        $request->user()->update([
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
        ]);

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

        // Logout dulu
        Auth::logout();

        // Hapus avatar fisik user sebelum hapus data user
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Hapus data user dari DB
        $user->delete();

        // Invalidate session agar tidak bisa dipakai lagi (Security)
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
