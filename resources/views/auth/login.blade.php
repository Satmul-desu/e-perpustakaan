<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Buku Online</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background-image: url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
            overflow-x: hidden;
        }

        /* Dark overlay */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(15, 15, 35, 0.85) 0%, rgba(26, 26, 46, 0.75) 50%, rgba(22, 33, 62, 0.85) 100%);
            z-index: 0;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            max-width: 420px;
            width: 100%;
        }

        .login-header {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.2) 100%);
            padding: 35px 25px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .login-header .logo {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.4);
        }

        .login-header .logo img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }

        .login-header h3 {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 6px;
            color: #ffffff;
        }

        .login-header p {
            opacity: 0.7;
            font-size: 0.9rem;
            color: #e0e0e0;
        }

        .login-body {
            padding: 30px 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            font-weight: 600;
            color: #e0e0e0;
            margin-bottom: 8px;
            display: block;
            font-size: 0.85rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
            font-size: 1.1rem;
        }

        .form-control {
            width: 100%;
            padding: 13px 14px 13px 46px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .form-control.is-invalid {
            border-color: #ef4444;
        }

        .text-danger {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 5px;
            display: block;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.5);
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #667eea;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 5px;
        }

        .form-check-input:checked {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: transparent;
        }

        .form-check-label {
            color: #c0c0c0;
            font-size: 0.85rem;
            cursor: pointer;
        }

        .links {
            text-align: center;
            margin-top: 20px;
        }

        .links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            font-size: 0.85rem;
        }

        .links a:hover {
            color: #8b9ff0;
            text-shadow: 0 0 10px rgba(102, 126, 234, 0.5);
        }


        .register-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #a0a0a0;
            font-size: 0.85rem;
        }

        .register-link a {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }

        .register-link a:hover {
            color: #8b9ff0;
            text-shadow: 0 0 10px rgba(102, 126, 234, 0.5);
        }

        .alert {
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 18px;
            font-size: 0.85rem;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.15);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        /* ========== MOBILE RESPONSIVE ========== */
        @media (max-width: 480px) {
            .login-body {
                padding: 25px 18px;
            }

            .login-header {
                padding: 30px 18px;
            }

            .login-header .logo {
                width: 100px;
                height: 100px;
            }

            .login-header .logo img {
                width: 65px;
                height: 65px;
            }

            .login-header h3 {
                font-size: 1.3rem;
            }

            .form-group {
                margin-bottom: 14px;
            }

            .form-control {
                padding: 12px 12px 12px 44px;
                font-size: 0.9rem;
            }

            .input-wrapper i {
                font-size: 1rem;
                left: 12px;
            }

            .btn-login {
                padding: 12px;
                font-size: 0.95rem;
            }

            .register-link {
                margin-top: 20px;
                padding-top: 20px;
            }
        }

        @media (max-width: 360px) {
            .login-card {
                max-width: 100%;
                border-radius: 16px;
            }

            .login-header {
                padding: 25px 15px;
            }

            .login-header .logo {
                width: 80px;
                height: 80px;
            }

            .login-header .logo img {
                width: 50px;
                height: 50px;
            }

            .login-body {
                padding: 20px 15px;
            }
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="login-card">
            <!-- Header dengan Logo -->
            <div class="login-header">
                <div class="logo">
                    <img src="{{ asset('images/logo-removebg-preview.png') }}" alt="Logo Toko Buku">
                </div>
                <h3>Selamat Datang</h3>
                <p>Login ke akun Anda</p>
            </div>

            <!-- Body Form -->
            <div class="login-body">
                <!-- Flash Messages -->
                @if (session('status'))
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-wrapper">
                            <i class="bi bi-envelope-fill"></i>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com"
                                required autocomplete="email" autofocus>
                        </div>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <i class="bi bi-lock-fill"></i>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="••••••••" required
                                autocomplete="current-password">
                        </div>
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">Ingat saya</label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Login
                    </button>

                    <!-- Forgot Password Link -->
                    <div class="links">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">
                                <i class="bi bi-question-circle me-1"></i>
                                Lupa password?
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Register Link -->
                <div class="register-link">
                    Belum punya akun?
                        <a href="{{ route('register') }}">Daftar sekarang</a>
                    </div>
            </div>
        </div>
    </div>
</body>

</html>
