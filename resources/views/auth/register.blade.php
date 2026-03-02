<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Toko Buku Online</title>
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
            background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 50%, #16213e 100%);
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: -100px;
            right: -100px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            z-index: 0;
        }
        
        body::after {
            content: '';
            position: fixed;
            bottom: -150px;
            left: -150px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(118, 75, 162, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            z-index: 0;
        }
        
        .register-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            z-index: 1;
        }
        
        .register-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            max-width: 480px;
            width: 100%;
        }
        
        .register-header {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.2) 100%);
            padding: 40px 25px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .register-header .logo {
            width: 85px;
            height: 85px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.4);
        }
        
        .register-header .logo img {
            width: 52px;
            height: 52px;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }
        
        .register-header h3 {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 6px;
            color: #ffffff;
        }
        
        .register-header p {
            opacity: 0.7;
            font-size: 0.9rem;
            color: #e0e0e0;
        }
        
        .register-body {
            padding: 30px 25px;
        }
        
        .form-group {
            margin-bottom: 16px;
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
        
        .btn-register {
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
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.5);
        }
        
        .terms {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 20px;
            font-size: 0.8rem;
            color: #a0a0a0;
        }
        
        .terms input {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            flex-shrink: 0;
            accent-color: #667eea;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            cursor: pointer;
        }
        
        .terms input:checked {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: transparent;
        }
        
        .terms label {
            cursor: pointer;
            line-height: 1.5;
        }
        
        .terms a {
            color: #667eea;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .terms a:hover {
            color: #8b9ff0;
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.8rem;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .divider span {
            padding: 0 15px;
        }
        
        .btn-google {
            width: 100%;
            padding: 12px;
            background: rgba(255, 255, 255, 0.08);
            border: 2px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            color: #e0e0e0;
            font-weight: 500;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
        }
        
        .btn-google:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.15);
            color: #ffffff;
            transform: translateY(-2px);
        }
        
        .btn-google svg {
            width: 20px;
            height: 20px;
        }
        
        .login-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #a0a0a0;
            font-size: 0.85rem;
        }
        
        .login-link a {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .login-link a:hover {
            color: #8b9ff0;
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 18px;
            font-size: 0.85rem;
        }
        
        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        .row-inputs {
            display: flex;
            gap: 12px;
        }
        
        .row-inputs .form-group {
            flex: 1;
        }
        
        /* ========== MOBILE RESPONSIVE ========== */
        @media (max-width: 480px) {
            .register-body {
                padding: 25px 18px;
            }
            
            .register-header {
                padding: 30px 18px;
            }
            
            .register-header .logo {
                width: 70px;
                height: 70px;
            }
            
            .register-header .logo img {
                width: 45px;
                height: 45px;
            }
            
            .register-header h3 {
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
            
            .btn-register {
                padding: 12px;
                font-size: 0.95rem;
            }
            
            .terms {
                font-size: 0.75rem;
                gap: 8px;
            }
            
            .terms input {
                width: 16px;
                height: 16px;
            }
            
            .divider {
                margin: 20px 0;
            }
            
            .btn-google {
                padding: 10px;
                font-size: 0.85rem;
            }
            
            .login-link {
                margin-top: 20px;
                padding-top: 20px;
            }
        }
        
        @media (max-width: 360px) {
            .register-card {
                max-width: 100%;
                border-radius: 16px;
            }
            
            .register-header {
                padding: 25px 15px;
            }
            
            .register-header .logo {
                width: 60px;
                height: 60px;
            }
            
            .register-header .logo img {
                width: 38px;
                height: 38px;
            }
            
            .register-body {
                padding: 20px 15px;
            }
            
            .row-inputs {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <div class="register-wrapper">
        <div class="register-card">
            <!-- Header dengan Logo -->
            <div class="register-header">
                <div class="logo">
                    <img src="{{ asset('images/logo-removebg-preview.png') }}" alt="Logo Toko Buku">
                </div>
                <h3>Buat Akun Baru</h3>
                <p>Daftar dan mulai belanja buku</p>
            </div>
            
            <!-- Body Form -->
            <div class="register-body">
                <!-- Flash Messages -->
                @if(session('status'))
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('status') }}
                    </div>
                @endif
                
                <!-- Validation Errors -->
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <!-- Name Field -->
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <div class="input-wrapper">
                            <i class="bi bi-person-fill"></i>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="Nama lengkap Anda" 
                                   required 
                                   autocomplete="name" 
                                   autofocus>
                        </div>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-wrapper">
                            <i class="bi bi-envelope-fill"></i>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="nama@email.com" 
                                   required 
                                   autocomplete="email">
                        </div>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <!-- Password & Confirm Password -->
                    <div class="row-inputs">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-wrapper">
                                <i class="bi bi-lock-fill"></i>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="••••••••" 
                                       required 
                                       autocomplete="new-password">
                            </div>
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi</label>
                            <div class="input-wrapper">
                                <i class="bi bi-check-circle-fill"></i>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="••••••••" 
                                       required 
                                       autocomplete="new-password">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Terms & Conditions -->
                    <div class="terms">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">
                            Saya setuju dengan 
                            <a href="#">Syarat & Ketentuan</a> 
                            dan 
                            <a href="#">Kebijakan Privasi</a>
                        </label>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn-register">
                        <i class="bi bi-person-plus me-2"></i>
                        Daftar Sekarang
                    </button>
                </form>
                
                <!-- Divider -->
                <div class="divider">
                    <span>atau</span>
                </div>
                
                <!-- Google Register Button -->
                <a href="{{ route('auth.google') }}" class="btn-google">
                    <svg viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Daftar dengan Google
                </a>
                
                <!-- Login Link -->
                <div class="login-link">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}">Login di sini</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

