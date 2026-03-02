# Laravel E-Commerce Project - PKL Internal Satmul

Project e-commerce untuk PKL Internal Satmul dengan Laravel 11, PostgreSQL, dan Midtrans Payment Gateway.

## 🚀 Fitur Utama

- **Authentication**: Login/Register + Google OAuth
- **Customer Features**: 
  - Katalog produk dengan filter & search
  - Keranjang belanja (Cart)
  - Wishlist
  - Checkout & Payment Gateway (Midtrans)
  - Riwayat pesanan
  
- **Admin Features**:
  - Dashboard dengan statistik & grafik
  - Manajemen Produk & Kategori
  - Manajemen Pesanan
  - Laporan Penjualan (dapat export Excel)
  - Manajemen User

## 📋 Persyaratan Sistem

- PHP 8.2+
- PostgreSQL 14+
- Composer 2+
- Node.js 18+ (untuk Vite)

## 🛠️ Instalasi

### 1. Clone & Install Dependencies

```bash
cd /home/tsukareta/Documents/www/pkl/project-pkl

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 2. Konfigurasi Environment

Copy file `.env.example` ke `.env` dan sesuaikan konfigurasi:

```bash
cp .env.example .env
```

Edit file `.env` dengan konfigurasi database PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=pkl-internal-satmul
DB_USERNAME=postgres
DB_PASSWORD=satmul-desu
```

### 3. Generate APP_KEY

```bash
php artisan key:generate
```

### 4. Setup Database

Pastikan PostgreSQL service sudah berjalan, lalu jalankan migration:

```bash
php artisan migrate --seed
```

### 5. Setup Storage Link

```bash
php artisan storage:link
```

### 6. Build Assets

```bash
npm run build
```

## ▶️ Menjalankan Aplikasi

### Development

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev
```

Akses aplikasi di: `http://localhost:8000`

### Admin Panel

Login dengan kredensial admin:
- URL: `http://localhost:8000/admin/login`
- Email: `admin@satmul.com`
- Password: `password`

## 🔧 Konfigurasi Tambahan

### Midtrans Payment Gateway

Pastikan konfigurasi Midtrans di `.env`:

```env
MIDTRANS_MERCHANT_ID=G961197935
MIDTRANS_CLIENT_KEY=SB-Mid-client-plI1v_dpcaNOqGz1
MIDTRANS_SERVER_KEY=SB-Mid-server-F8zAlin58C_rCkvDOvSBiplm
MIDTRANS_IS_PRODUCTION=false
```

### Google OAuth

Buat project di [Google Cloud Console](https://console.cloud.google.com/) dan aktifkan Google+ API. Kemudian:

```env
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

## 📁 Struktur Project

```
app/
├── Console/                 # Commands
├── Events/                  # Event classes
├── Exceptions/              # Custom exceptions
├── Exports/                 # Excel exports
├── Http/
│   ├── Controllers/         # Web controllers
│   │   ├── Admin/          # Admin controllers
│   │   └── Auth/           # Auth controllers
│   ├── Middleware/         # Custom middleware
│   └── Requests/           # Form requests
├── Jobs/                    # Queue jobs
├── Listeners/               # Event listeners
├── Mail/                    # Mailable classes
├── Models/                  # Eloquent models
├── Observers/               # Model observers
├── Providers/               # Service providers
├── Services/                # Business logic services
└── Traits/                  # Shared traits

database/
├── migrations/             # Database migrations
└── seeders/                # Database seeders

resources/
├── css/                    # Stylesheets
├── js/                     # JavaScript
├── sass/                   # SCSS
└── views/                  # Blade templates
    ├── admin/              # Admin views
    ├── auth/               # Auth views
    ├── components/         # Blade components
    ├── emails/             # Email templates
    ├── layouts/            # Layout files
    └── partials/           # Partial views
```

## 🔒 Keamanan

- **CSRF Protection**: Aktif untuk semua form
- **SQL Injection Prevention**: Menggunakan Laravel Eloquent ORM
- **XSS Prevention**: Blade escaping diaktifkan
- **Authentication**: Session-based dengan password hashing
- **Middleware**: Admin middleware untuk proteksi route admin
- **Security Headers**: CSP, X-Frame-Options, X-XSS-Protection

## 📊 Database Schema

### Tabel Utama

- `users` - Data user & admin
- `categories` - Kategori produk
- `products` - Data produk
- `product_images` - Gambar produk
- `carts` & `cart_items` - Keranjang belanja
- `wishlists` - Wishlist user
- `orders` & `order_items` - Data pesanan
- `payments` - Data pembayaran Midtrans

## 🧪 Testing

```bash
# Run all tests
php artisan test
```

## 📝 Catatan Pengembangan

### Coding Standards

- Gunakan PSR-12 coding standard
- Format kode dengan PHP CS Fixer
- Gunakan type hints dan return types
- Documentasikan fungsi dengan DocBlock

### Git Workflow

1. Buat branch baru dari `main`
2. Kerjakan fitur/bugfix
3. Commit dengan message yang jelas
4. Push dan buat Pull Request

## 📞 Support

Untuk pertanyaan atau issue, silakan hubungi tim developer.

## 📄 Lisensi

Project ini adalah proprietary software untuk PKL Internal Satmul.

