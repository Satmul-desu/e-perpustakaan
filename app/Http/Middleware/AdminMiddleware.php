<?php
// ========================================
// FILE: app/Http/Middleware/AdminMiddleware.php
// FUNGSI: Membatasi akses hanya untuk 2 akun admin spesifik
// ========================================

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Daftar email admin yang diizinkan mengakses panel admin.
     * HANYA 2 AKUN INI yang bisa masuk:
     * 1. AdminTB@TokoBuku.com
     * 2. adminTb@TokoBuku.com
     */
    private const ALLOWED_ADMIN_EMAILS = [
        'admintb@tokobuku.com',
        'admin@example.com', // Dari DatabaseSeeder
    ];

    /**
     * Handle an incoming request.
     *
     * Method ini dipanggil SETIAP KALI ada request yang melewati middleware ini.
     *
     * @param Request $request  Request dari user
     * @param Closure $next     Fungsi untuk melanjutkan ke proses berikutnya
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ================================================
        // STEP 1: Cek apakah user sudah login
        // ================================================
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // ================================================
        // STEP 2: Cek apakah user adalah admin DAN email-nya diizinkan
        // ================================================
        
        // Cek role admin
        if ($user->role !== 'admin') {
            abort(403, 'Anda tidak memiliki role administrator.');
        }

        // Cek apakah email admin diizinkan (case-insensitive)
        $userEmail = strtolower($user->email);
        if (! in_array($userEmail, self::ALLOWED_ADMIN_EMAILS)) {
            // Log percobaan akses tidak sah untuk keamanan
            \Log::warning('Percobaan akses admin oleh user non-izin', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
            ]);
            
            abort(403, 'Email ini tidak diizinkan mengakses panel admin.');
        }

        // ================================================
        // STEP 3: Jika lolos semua pengecekan, lanjutkan request
        // ================================================
        return $next($request);
    }
}
