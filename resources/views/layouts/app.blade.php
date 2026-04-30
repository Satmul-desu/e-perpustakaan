<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Perpustakaan') - {{ config('app.name') }}</title>
    <meta name="description" content="@yield('meta_description', 'Toko online terpercaya dengan produk berkualitas')">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">
    <meta name="theme-color" content="#3b82f6">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        :root {
            --body-bg: #f8fafc;
            --text-color: #0f172a;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
            --input-bg: #ffffff;
            --input-focus: #f1f5f9;
        }

        [data-bs-theme="dark"] {
            --body-bg: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 50%, #16213e 100%);
            --text-color: #e0e0e0;
            --card-bg: rgba(30, 41, 59, 0.95);
            --border-color: #334155;
            --input-bg: rgba(15, 23, 42, 0.6);
            --input-focus: rgba(15, 23, 42, 0.8);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--body-bg);
            min-height: 100vh;
            color: var(--text-color);
            transition: background 0.3s ease, color 0.3s ease;
        }

        .bg-dark-custom {
            background: var(--body-bg) !important;
        }

        .bg-dark-card {
            background: var(--card-bg) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-color) !important;
        }

        .text-primary-custom {
            color: #60a5fa !important;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border: none;
            color: white;
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
            color: white;
            transform: translateY(-2px);
        }

        .form-control-custom {
            background: var(--input-bg);
            border: 1px solid var(--border-color);
            color: var(--text-color);
        }

        .form-control-custom:focus {
            background: var(--input-focus);
            border-color: #3b82f6;
            color: var(--text-color);
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }

        .form-control-custom::placeholder {
            color: #64748b;
        }

        .card-custom {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            color: var(--text-color);
        }

        .floating-cs-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9999;
        }

        .floating-cs-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 50%;
            color: white;
            text-decoration: none;
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.4);
            transition: all 0.3s ease;
        }

        .floating-cs-link:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 30px rgba(99, 102, 241, 0.5);
            color: white;
        }

        .floating-cs-icon {
            font-size: 1.75rem;
        }

        .floating-cs-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            min-width: 20px;
            height: 20px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #1e293b;
        }

        #toast-container {
            z-index: 10000;
        }

        @media (max-width: 575.98px) {
            .floating-cs-button {
                bottom: 20px;
                right: 20px;
            }

            .floating-cs-link {
                width: 52px;
                height: 52px;
            }

            .floating-cs-icon {
                font-size: 1.5rem;
            }
        }
    </style>
    @yield('styles')
</head>

<body>
    @include('partials.navbar')

    <div class="container mt-3">
        @include('partials.flash-messages')
    </div>

    <main class="min-vh-100">
        @yield('content')
    </main>

    @include('partials.footer')

    <div class="floating-cs-button" data-bs-toggle="tooltip" data-bs-placement="left" title="Customer Service">
        <a href="{{ route('cs.index') }}" class="floating-cs-link">
            <div class="floating-cs-icon">
                <i class="bi bi-headset"></i>
                @if (isset($pendingComplaints) && $pendingComplaints > 0)
                    <span class="floating-cs-badge">{{ $pendingComplaints > 9 ? '9+' : $pendingComplaints }}</span>
                @endif
            </div>
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const savedTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-bs-theme', currentTheme);
            localStorage.setItem('theme', currentTheme);
            updateThemeIcon(currentTheme);
        }
        function updateThemeIcon(theme) {
            const moonIcon = document.getElementById('theme-icon-moon');
            const sunIcon = document.getElementById('theme-icon-sun');
            if(moonIcon && sunIcon) {
                if (theme === 'dark') {
                    moonIcon.classList.add('d-none');
                    sunIcon.classList.remove('d-none');
                } else {
                    sunIcon.classList.add('d-none');
                    moonIcon.classList.remove('d-none');
                }
            }
        }
        document.addEventListener('DOMContentLoaded', () => {
            updateThemeIcon(savedTheme);
        });
    </script>
    @yield('scripts')
    <script>
        async function toggleWishlist(productId) {
            try {
                const token = document.querySelector('meta[name="csrf-token"]').content;
                console.log('Toggle wishlist for product:', productId);

                const response = await fetch(`/wishlist/toggle/${productId}`, {
                    method: "POST",
                    headers: {
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": token,
                        },
                });

                console.log('Response status:', response.status);

                if (response.status === 401) {
                    window.location.href = "/login";
                    return;
                }

                const responseText = await response.text();
                let data;
                try {
                    data = JSON.parse(responseText);
                } catch (e) {
                    console.error('JSON parse error:', e);
                    throw new Error('Server returned invalid JSON');
                }

                if (data.status === "success") {
                    updateWishlistUI(productId, data.added);
                    updateWishlistCounter(data.count);
                    showToast(data.message);
                } else if (data.status === "error") {
                    showToast(data.message || "Terjadi kesalahan.", "error");
                }
            } catch (error) {
                console.error("Error:", error);
                showToast("Terjadi kesalahan sistem: " + error.message, "error");
            }
        }

        function updateWishlistUI(productId, isAdded) {
            const buttons = document.querySelectorAll(`.wishlist-btn-${productId}`);
            buttons.forEach((btn) => {
                const icon = btn.querySelector("i");
                if (isAdded) {
                    icon.classList.remove("bi-heart", "text-secondary");
                    icon.classList.add("bi-heart-fill", "text-danger");
                } else {
                    icon.classList.remove("bi-heart-fill", "text-danger");
                    icon.classList.add("bi-heart", "text-secondary");
                }
            });
        }

        function updateWishlistCounter(count) {
            const badge = document.getElementById("wishlist-count");
            if (badge) {
                badge.innerText = count;
                badge.style.display = count > 0 ? "inline-block" : "none";
            }
        }

        function showToast(message, type = 'success') {
            let toastContainer = document.getElementById('toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toast-container';
                toastContainer.className = 'position-fixed top-0 end-0 p-3';
                document.body.appendChild(toastContainer);
            }

            const toastId = 'toast-' + Date.now();
            const bgColor = type === 'success' ? 'success' : type === 'error' ? 'danger' : 'primary';
            const icon = type === 'success' ? 'bi-check-circle' : type === 'error' ? 'bi-x-circle' : 'bi-info-circle';

            const toastHtml = `
                <div id="${toastId}" class="toast align-items-center text-bg-${bgColor} border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body d-flex align-items-center">
                            <i class="bi ${icon} me-2"></i>
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;

            toastContainer.insertAdjacentHTML('beforeend', toastHtml);

            const toastEl = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastEl, {
                autohide: true,
                delay: 3000
            });
            toast.show();

            toastEl.addEventListener('hidden.bs.toast', function() {
                toastEl.remove();
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    trigger: 'hover focus',
                    delay: {
                        show: 200,
                        hide: 100
                    }
                });
            });
        });

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    }, err => {
                        console.log('ServiceWorker registration failed: ', err);
                    });
            });
        }
    </script>
</body>

</html>
