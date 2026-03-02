@extends('layouts.app')

@section('title', 'Hasil Pembayaran - Toko Online')

@section('content')
<style>
    .page-offset { margin-top: 90px; }
    
    /* Success Animation */
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
        0% { transform: scale(0); opacity: 0; }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); opacity: 1; }
    }
    .success-icon {
        font-size: 60px;
        color: white;
        animation: bounceIn 0.6s ease-out 0.3s both;
    }
    @keyframes bounceIn {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    
    /* Failed Animation */
    .failed-icon-container {
        width: 120px;
        height: 120px;
        margin: 0 auto;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 40px rgba(239, 68, 68, 0.4);
        animation: scaleIn 0.5s ease-out;
    }
    .failed-icon {
        font-size: 60px;
        color: white;
    }
    
    /* Pending Animation */
    .pending-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 40px rgba(245, 158, 11, 0.4);
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); box-shadow: 0 10px 40px rgba(245, 158, 11, 0.4); }
        50% { transform: scale(1.05); box-shadow: 0 15px 50px rgba(245, 158, 11, 0.6); }
    }
    .pending-clock {
        font-size: 50px;
        color: white;
    }
    
    /* Info Box */
    .info-box {
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.3);
        border-radius: 12px;
        padding: 20px;
    }
    .info-box-danger {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 12px;
        padding: 20px;
    }
</style>

<div class="container py-5 page-offset">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            
            {{-- SUCCESS STATE --}}
            @if($status === 'success')
                <div class="text-center mb-4">
                    <div class="success-checkmark">
                        <span class="success-icon">✓</span>
                    </div>
                    <h2 class="fw-bold text-custom mt-4">Pembayaran Berhasil!</h2>
                    <p class="text-secondary">
                        Terima kasih, pesanan Anda telah kami terima dan sedang diproses.
                    </p>
                </div>

                <div class="card card-custom">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold text-white mb-3">Order #{{ $order->order_number }}</h4>

                            <div class="alert alert-success bg-success bg-opacity-10 border-success text-success d-inline-block px-4 py-2 rounded-pill mb-4">
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
            
            {{-- FAILED STATE --}}
            @elseif($status === 'failed')
                <div class="text-center mb-4">
                    <div class="failed-icon-container">
                        <span class="failed-icon">✕</span>
                    </div>
                    <h2 class="fw-bold text-danger mt-4">Pembayaran Gagal</h2>
                    <p class="text-secondary">
                        Maaf, pembayaran Anda tidak berhasil diproses.
                    </p>
                </div>

                <div class="card card-custom">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold text-white mb-3">Order #{{ $order->order_number }}</h4>

                            <div class="alert alert-danger bg-danger bg-opacity-10 border-danger text-danger d-inline-block px-4 py-2 rounded-pill mb-4">
                                <i class="bi bi-x-circle me-2"></i>
                                Pembayaran Gagal
                            </div>

                            <p class="text-secondary mb-4">
                                Total yang harus dibayar:
                            </p>

                            <h3 class="fw-bold text-custom mb-4">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </h3>
                            
                            {{-- Rincian Pembayaran --}}
                            <div class="text-start bg-dark bg-opacity-25 rounded-3 p-3 mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary small">Subtotal Barang</span>
                                    <span class="fw-bold text-white small">Rp {{ number_format($order->items_subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary small">Ongkir</span>
                                    <span class="fw-bold text-white small">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between pt-2 border-top border-secondary">
                                    <strong class="text-white">Total Bayar (Subtotal + Ongkir)</strong>
                                    <strong class="text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="info-box-danger mb-4">
                            <h6 class="fw-bold text-danger mb-3">
                                <i class="bi bi-info-circle me-2"></i>Kemungkinan Penyebab:
                            </h6>
                            <ul class="text-secondary mb-0 ps-3">
                                <li class="mb-2">Pembayaran dibatalkan atau tidak selesai</li>
                                <li class="mb-2">Metode pembayaran tidak valid</li>
                                <li class="mb-2">Transaksi kedaluwarsa</li>
                                <li class="mb-2">Saldo atau limit tidak mencukupi</li>
                            </ul>
                        </div>

                        <div class="d-flex gap-3 justify-content-center flex-wrap">
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-light btn-lg">
                                <i class="bi bi-arrow-left me-2"></i>Coba Lagi
                            </a>
                            @if($order->snap_token)
                            <button id="pay-button" class="btn btn-custom btn-lg">
                                <i class="bi bi-credit-card me-2"></i>Bayar Sekarang
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4 text-secondary small">
                    <p class="mb-0">Perlu bantuan? <a href="#" class="text-custom">Hubungi kami</a></p>
                </div>
            
            {{-- PENDING STATE --}}
            @else
                <div class="text-center mb-4">
                    <div class="pending-icon">
                        <span class="pending-clock">⏱</span>
                    </div>
                    <h2 class="fw-bold text-custom mt-4">Menunggu Pembayaran</h2>
                    <p class="text-secondary">
                        Silakan selesaikan pembayaran Anda untuk memproses pesanan.
                    </p>
                </div>

                <div class="card card-custom">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold text-white mb-3">Order #{{ $order->order_number }}</h4>

                            <div class="alert alert-warning bg-warning bg-opacity-10 border-warning text-warning d-inline-block px-4 py-2 rounded-pill mb-4">
                                <i class="bi bi-clock-history me-2"></i>
                                Menunggu Pembayaran
                            </div>

                            <p class="text-secondary">
                                Total yang harus dibayar:
                            </p>

                            <h3 class="fw-bold text-custom mb-4">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </h3>
                            
                            {{-- Rincian Pembayaran --}}
                            <div class="text-start bg-dark bg-opacity-25 rounded-3 p-3 mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary small">Subtotal Barang</span>
                                    <span class="fw-bold text-white small">Rp {{ number_format($order->items_subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary small">Ongkir</span>
                                    <span class="fw-bold text-white small">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between pt-2 border-top border-secondary">
                                    <strong class="text-white">Total Bayar (Subtotal + Ongkir)</strong>
                                    <strong class="text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="info-box mb-4">
                            <h6 class="fw-bold text-white mb-3">
                                <i class="bi bi-info-circle me-2"></i>Cara Pembayaran:
                            </h6>
                            <ul class="text-secondary mb-0 ps-3">
                                <li class="mb-2">Transfer ke rekening yang tertera di invoice</li>
                                <li class="mb-2">atau scan QRIS yang dikirim ke email</li>
                                <li>Pembayaran akan dikonfirmasi secara otomatis</li>
                            </ul>
                        </div>

                        <div class="d-flex gap-3 justify-content-center flex-wrap">
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-light btn-lg">
                                <i class="bi bi-arrow-left me-2"></i>Kembali ke Pesanan
                            </a>
                            @if($order->snap_token)
                            <button id="pay-button" class="btn btn-custom btn-lg">
                                <i class="bi bi-credit-card me-2"></i>Bayar Sekarang
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4 text-secondary small">
                    <p class="mb-1">Perlu bantuan? <a href="#" class="text-custom">Hubungi kami</a></p>
                    <p class="mb-0">Invoice telah dikirim ke email Anda</p>
                </div>
            @endif

        </div>
    </div>
</div>

{{-- Snap.js Integration untuk pembayaran ulang --}}
@if($order->snap_token && ($status === 'failed' || $status === 'pending'))
{{-- Snap.js sudah dimuat di layouts/app.blade.php --}}
@push('scripts')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const payButton = document.getElementById('pay-button');

            if (payButton) {
                payButton.addEventListener('click', function() {
                    payButton.disabled = true;
                    payButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

                    if (typeof snap === 'undefined') {
                        alert('Snap.js terblokir. Nonaktifkan adblocker atau hubungi admin.');
                        payButton.disabled = false;
                        payButton.innerHTML = '<i class="bi bi-credit-card me-2"></i>Bayar Sekarang';
                        return;
                    }

                    window.snap.pay('{{ $order->snap_token }}', {
                        onSuccess: function(result) {
                            console.log('Payment Success:', result);
                            window.location.href = '{{ route("orders.result", ["order" => $order, "status" => "success"]) }}';
                        },
                        onPending: function(result) {
                            console.log('Payment Pending:', result);
                            window.location.href = '{{ route("orders.result", ["order" => $order, "status" => "pending"]) }}';
                        },
                        onError: function(result) {
                            console.log('Payment Error:', result);
                            window.location.href = '{{ route("orders.result", ["order" => $order, "status" => "failed"]) }}';
                        },
                        onClose: function() {
                            payButton.disabled = false;
                            payButton.innerHTML = '<i class="bi bi-credit-card me-2"></i>Bayar Sekarang';
                        }
                    });
                });
            }
        });
    </script>
@endpush
@endif
@endsection

