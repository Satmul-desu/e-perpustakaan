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

