<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    /**
     * Update profile avatar.
     */
    public function updateAvatar(User $user, $file): string
    {
        // Hapus avatar lama jika ada dan merupakan file lokal
        if ($user->avatar &&
            !$this->isGoogleAvatar($user->avatar) &&
            Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Generate nama file unik
        $filename = 'avatar-' . $user->id . '-' . time() . '.' . $file->extension();
        $path = $file->storeAs('avatars', $filename, 'public');

        // Update data user
        $updateData = ['avatar' => $path];
        if ($user->isGoogleUser()) {
            $updateData['google_id'] = null;
        }
        
        $user->update($updateData);

        return $path;
    }

    /**
     * Delete profile avatar.
     */
    public function deleteAvatar(User $user): void
    {
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }
    }

    /**
     * Update user profile information.
     */
    public function updateProfile(User $user, array $validatedData, $avatarFile = null): void
    {
        // Handle Avatar if provided
        if ($avatarFile) {
            $this->updateAvatar($user, $avatarFile);
        }

        // Check for email change to reset verification
        if (isset($validatedData['email']) && $user->email !== $validatedData['email']) {
            $user->email_verified_at = null;
        }

        $user->fill($validatedData);
        $user->save();
    }

    /**
     * Update user password.
     */
    public function updatePassword(User $user, string $newPassword): void
    {
        $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }

    /**
     * Delete user account and cleanup files.
     */
    public function deleteAccount(User $user): void
    {
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }
        $user->delete();
    }

    /**
     * Helper to check if avatar is a Google URL.
     */
    public function isGoogleAvatar(?string $avatar): bool
    {
        return $avatar && (str_starts_with($avatar, 'http://') || str_starts_with($avatar, 'https://'));
    }
}