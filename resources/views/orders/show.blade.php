@extends('layouts.app')
@section('title', 'Detail Pesanan #' . $order->order_number)
@section('content')
<style>
    .page-offset { margin-top: 90px; }
</style>
<div class="container py-5 page-offset">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-1">
                        <i class="bi bi-receipt me-2"></i> Pesanan #{{ $order->order_number }}
                    </h1>
                    <p class="text-secondary mb-0">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-light">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="card card-custom h-100">
                        <div class="card-body">
                            <small class="text-secondary d-block mb-1">Status Pesanan</small>
                            <span class="badge bg-{{ $order->status_color }} px-3 py-2 rounded-pill">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-custom h-100">
                        <div class="card-body">
                            <small class="text-secondary d-block mb-1">Status Pembayaran</small>
                            <span class="badge bg-{{ $order->payment_status_color }} px-3 py-2 rounded-pill">
                                {{ $order->payment_status_text }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card card-custom">
                        <div class="card-header bg-secondary rounded-top-4">
                            <h5 class="mb-0 text-white">
                                <i class="bi bi-box-seam me-2"></i> Produk Dipesan
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            @foreach($order->orderItems as $item)
                            <div class="d-flex justify-content-between align-items-center p-4 border-bottom">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-3 p-3 me-3">
                                        <i class="bi bi-box text-primary fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $item->product_name }}</h6>
                                        <small class="text-secondary">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</small>
                                    </div>
                                </div>
                                <span class="fw-bold text-custom">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                            <div class="p-4 bg-light">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Subtotal Barang</span>
                                    <span class="fw-bold text-dark">Rp {{ number_format($order->items_subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Ongkir</span>
                                    <span class="fw-bold text-dark">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between pt-2 border-top mt-2">
                                    <strong class="text-dark">Total Bayar (Subtotal + Ongkir)</strong>
                                    <strong class="text-primary fs-5">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card card-custom mb-4">
                        <div class="card-header bg-secondary rounded-top-4">
                            <h5 class="mb-0 text-white">
                                <i class="bi bi-truck me-2"></i> Alamat Pengiriman
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="fw-bold mb-1">{{ $order->shipping_name }}</p>
                            <p class="text-secondary mb-1">{{ $order->shipping_phone }}</p>
                            <p class="text-secondary mb-0" style="white-space: pre-line;">{{ $order->shipping_address }}</p>
                        </div>
                    </div>
                    @if($order->payment_status === 'pending' || $order->payment_status === 'unpaid')
                    <div class="card card-custom">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-credit-card text-custom" style="font-size: 48px;"></i>
                            </div>
                            <h5 class="fw-bold text-white mb-2">Selesaikan Pembayaran</h5>
                            <p class="text-secondary small mb-4">
                                Total: <strong class="text-custom">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                            </p>
                            <button id="pay-button" class="btn btn-custom btn-lg w-100 fw-bold mb-3" data-order-number="{{ $order->order_number }}">
                                <i class="bi bi-credit-card me-2"></i> Bayar Sekarang
                            </button>
                            <p class="text-secondary small mb-0 snap-unavailable" style="display: none;">
                                <i class="bi bi-info-circle text-warning me-1"></i>
                                Snap.js terblokir? 
                                <a href="#" onclick="alert('Silakan cek email invoice untuk link pembayaran atau hubungi admin.'); return false;">
                                    Cara pembayaran manual
                                </a>
                            </p>
                            <p class="text-secondary small mt-3 mb-0">
                                <i class="bi bi-shield-check text-success me-1"></i>
                                Pembayaran aman dengan Midtrans
                            </p>
                        </div>
                    </div>
                    @endif
                    @if($order->payment_status === 'paid')
                    <div class="card card-custom bg-success bg-opacity-10 border-success">
                        <div class="card-body text-center">
                            <i class="bi bi-check-circle text-success" style="font-size: 48px;"></i>
                            <h5 class="fw-bold text-white mt-3 mb-2">Pembayaran Lunas</h5>
                            <p class="text-secondary small mb-0">
                                Pesanan Anda sedang kami proses
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@if($order->payment_status === 'pending' || $order->payment_status === 'unpaid')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const payButton = document.getElementById('pay-button');
            const orderNumber = payButton.getAttribute('data-order-number');
            const snapUnavailableMsg = document.querySelector('.snap-unavailable');
            if (typeof snap === 'undefined' && snapUnavailableMsg) {
                snapUnavailableMsg.style.display = 'block';
            }
            payButton.addEventListener('click', function() {
                payButton.disabled = true;
                payButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
                if (typeof snap === 'undefined') {
                    alert('Snap.js terblokir oleh browser/security. Silakan:\n\n1. Nonaktifkan adblocker\n2. Gunakan browser lain\n3. Atau hubungi admin untuk pembayaran manual');
                    payButton.disabled = false;
                    payButton.innerHTML = '<i class="bi bi-credit-card me-2"></i> Bayar Sekarang';
                    return;
                }
                fetch(`/orders/${orderNumber}/snap-token`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.token) {
                            snap.pay(data.token, {
                                onSuccess: function(result) {
                                    console.log('Payment Success:', result);
                                    window.location.href = `/orders/{{ $order->id }}/result/success`;
                                },
                                onPending: function(result) {
                                    console.log('Payment Pending:', result);
                                    window.location.href = `/orders/{{ $order->id }}/result/pending`;
                                },
                                onError: function(result) {
                                    console.log('Payment Error:', result);
                                    window.location.href = `/orders/{{ $order->id }}/result/failed`;
                                },
                                onClose: function() {
                                    payButton.disabled = false;
                                    payButton.innerHTML = '<i class="bi bi-credit-card me-2"></i> Bayar Sekarang';
                                }
                            });
                        } else {
                            throw new Error(data.error || 'Gagal mendapatkan token');
                        }
                    })
                    .catch(error => {
                        console.error('Payment error:', error);
                        alert('Gagal: ' + error.message);
                        payButton.disabled = false;
                        payButton.innerHTML = '<i class="bi bi-credit-card me-2"></i> Bayar Sekarang';
                    });
            });
        });
    </script>
@endif
@endsection