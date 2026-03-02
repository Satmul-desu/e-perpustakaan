@extends('layouts.app')

@section('title', 'Menunggu Pembayaran - Toko Online')

@section('content')
<style>
    .page-offset { margin-top: 90px; }
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
    .clock-icon {
        font-size: 50px;
        color: white;
    }
    .info-box {
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.3);
        border-radius: 12px;
        padding: 20px;
    }
</style>

<div class="container py-5 page-offset">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="text-center mb-4">
                <div class="pending-icon">
                    <span class="clock-icon">⏱</span>
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
        </div>
    </div>
</div>

{{-- Snap.js Integration untuk pembayaran --}}
@if($order->snap_token)
@push('scripts')
    <script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const payButton = document.getElementById('pay-button');

            if (payButton) {
                payButton.addEventListener('click', function() {
                    payButton.disabled = true;
                    payButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

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

