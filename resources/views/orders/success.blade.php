@extends('layouts.app')
@section('title', 'Pembayaran Berhasil - Toko Online')
@section('content')
    <style>
        .page-offset {
            margin-top: 90px;
        }

        .success-checkmark {
            width: 120px;
            height: 120px;
            margin: 0 auto;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 40px rgba(16, 185, 129, 0.4);
            animation: scaleIn 0.5s ease-out;
        }

        @keyframes scaleIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .success-icon {
            font-size: 60px;
            color: white;
            animation: bounceIn 0.6s ease-out 0.3s both;
        }

        @keyframes bounceIn {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }
    </style>
    <div class="container py-5 page-offset">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="text-center mb-4">
                    <div class="success-checkpoint.
<br>
                <h2 class="fw-bold text-custom mt-4">Pembayaran
                        Berhasil!</h2>
                        <p class="text-secondary">
                            Terima kasih, pesanan Anda telah kami terima dan sedang diproses.
                        </p>
                    </div>
                    <div class="card card-custom">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <div class="success-checkmark">
                                    <span class="success-icon">✓</span>
                                </div>
                            </div>
                            <div class="text-center">
                                <h4 class="fw-bold text-white mb-3">Order #{{ $order->order_number }}</h4>
                                <div
                                    class="alert alert-success bg-success bg-opacity-10 border-success text-success d-inline-block px-4 py-2 rounded-pill mb-4">
                                    <i class="bi bi-check-circle me-2"></i>
                                    {{ $order->payment->payment_type ?? 'Pembayaran' }} - Berhasil
                                </div>
                                <p class="text-secondary mb-4">
                                    Pesanan Anda sedang kami proses dan akan dikirimkan sesuai alamat yang ditentukan.
                                </p>
                                <div class="d-flex gap-3 justify-content-center flex-wrap">
                                    <a href="{{ route('orders.index') }}" class="btn btn-outline-light btn-lg">
                                        <i class="bi bi-list me-2"></i>Lihat Pesanan
                                    </a>
                                    <a href="{{ route('catalog.index') }}" class="btn btn-custom btn-lg">
                                        <i class="bi bi-shop me-2"></i>Lanjut Belanja
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4 text-secondary small">
                        <p><i class="bi bi-envelope me-1"></i> Invoice telah dikirim ke email Anda</p>
                        <p class="mb-0">Perlu bantuan? <a href="#" class="text-custom">Hubungi kami</a></p>
                    </div>
                </div>
            </div>
        </div>
    @endsection
