<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
class LoginController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = '/home';
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
    protected function redirectTo(): string
    {
        $user = auth()->user();
        
        if ($user && $user->isAdmin()) {
            return route('admin.dashboard');
        }
        
        return route('home');
    }
    protected function validateLogin($request): void
    {
        $request->validate([
            $this->username() => 'required|string|email',
            'password'        => 'required|string|min:6',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid (harus ada @).',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);
    }
}