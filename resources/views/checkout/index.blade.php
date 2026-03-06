@extends('layouts.app')
@section('title', 'Checkout')
@section('content')
<style>
    .page-offset {
        margin-top: 90px;
    }
    .sticky-summary {
        position: sticky;
        top: 120px;
    }
    .dark-form-control {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid #334155;
        color: white;
    }
    .dark-form-control:focus {
        background: rgba(15, 23, 42, 0.8);
        border-color: #3b82f6;
        color: white;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
    }
    .dark-form-control::placeholder {
        color: #94a3b8;
    }
</style>
<div class="container py-5 page-offset">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-custom">
                    <i class="bi bi-cart-check me-2"></i> Checkout Pesanan
                </h2>
                <p class="text-secondary">
                    Lengkapi data pengiriman sebelum melanjutkan pembayaran
                </p>
            </div>
            @if($cart->items->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-cart-x display-1 text-secondary"></i>
                    <h4 class="mt-3 text-white">Keranjang Masih Kosong</h4>
                    <p class="text-secondary">Silakan pilih produk terlebih dahulu.</p>
                    <a href="{{ route('catalog.index') }}" class="btn btn-custom mt-3">
                        <i class="bi bi-shop"></i> Mulai Belanja
                    </a>
                </div>
            @else
            @php
                $subtotal = $cart->items->sum('subtotal');
                $shippingCost = 10000;
                $total = $subtotal + $shippingCost;
            @endphp
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="card card-custom">
                        <div class="card-header border-secondary rounded-top-4" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                            <h5 class="mb-0 text-white">
                                <i class="bi bi-truck me-2"></i> Data Pengiriman
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('checkout.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-white">Nama Penerima</label>
                                    <input type="text" name="name" class="form-control form-control-lg dark-form-control"
                                        value="{{ old('name', auth()->user()->name ?? '') }}" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-white">No. HP</label>
                                        <input type="text" name="phone" class="form-control form-control-lg dark-form-control"
                                            value="{{ old('phone', auth()->user()->phone ?? '') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-white">Email (Opsional)</label>
                                        <input type="email" name="email" class="form-control form-control-lg dark-form-control"
                                            value="{{ old('email', auth()->user()->email ?? '') }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-white">Alamat Lengkap</label>
                                    <textarea name="address" rows="4" class="form-control form-control-lg dark-form-control" required>{{ old('address', auth()->user()->address ?? '') }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-white">Catatan (Opsional)</label>
                                    <textarea name="notes" rows="3" class="form-control dark-form-control"></textarea>
                                </div>
                                <button type="submit" class="btn btn-custom btn-lg w-100 fw-bold shadow-sm">
                                    <i class="bi bi-credit-card-2-front me-2"></i>Buat Pesanan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card card-custom shadow-lg sticky-summary">
                        <div class="card-header border-secondary rounded-top-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);">
                            <h5 class="mb-0 text-white">
                                <i class="bi bi-receipt-cutoff me-2"></i> Ringkasan Pesanan
                            </h5>
                        </div>
                        <div class="card-body p-4" style="color: #f1f5f9;">
                            @foreach($cart->items as $item)
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <strong>{{ $item->product->name }}</strong><br>
                                        <small class="text-secondary">{{ $item->quantity }} x {{ $item->product->formatted_price }}</small>
                                    </div>
                                    <span class="fw-semibold text-custom">{{ $item->formatted_subtotal }}</span>
                                </div>
                            @endforeach
                            <hr style="border-color: #334155;">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-secondary">Subtotal Barang</span>
                                <span class="text-light">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-secondary">Ongkir (Rp 10.000)</span>
                                <span class="text-light">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between p-3 rounded mt-3" style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.3);">
                                <strong class="text-white">Total Bayar (Subtotal + Ongkir)</strong>
                                <strong class="text-custom">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                            </div>
                            <div class="mt-3 text-secondary small">
                                <i class="bi bi-shield-check text-success"></i> Pembayaran aman & terenkripsi
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection