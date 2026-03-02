{{-- ================================================
     FILE: resources/views/cart/index.blade.php
     FUNGSI: Halaman keranjang belanja (Dark Theme)
     ================================================ --}}

@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-white">
        <i class="bi bi-cart3 me-2"></i>Buku Yang Akan Di Pinjam
    </h2>

    @if($cart && $cart->items->count())
        <div class="row">
            {{-- Cart Items --}}
            <div class="col-lg-8 mb-4">
                <div class="card card-custom">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" style="border-color: #334155;">
                                <thead style="background: rgba(15, 23, 42, 0.5);">
                                    <tr>
                                        <th style="width: 50%; color: white;">Produk</th>
                                        <th class="text-center" style="color: white;">Harga</th>
                                        <th class="text-center" style="color: white;">Jumlah</th>
                                        <th class="text-end" style="color: white;">Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody style="color: #f1f5f9;">
                                    @foreach($cart->items as $item)
                                        <tr style="border-color: #334155;">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $item->product->image_url }}"
                                                         class="rounded me-3"
                                                         width="60" height="60"
                                                         style="object-fit: cover; border: 2px solid #334155;">
                                                    <div>
                                                        <a href="{{ route('catalog.show', $item->product->slug) }}"
                                                           class="text-decoration-none text-light fw-medium"
                                                           style="transition: color 0.3s ease;"
                                                           onmouseover="this.style.color='#60a5fa'"
                                                           onmouseout="this.style.color='white'">
                                                            {{ Str::limit($item->product->name, 40) }}
                                                        </a>
                                                        <div class="small text-secondary">
                                                            {{ $item->product->category->name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle text-custom">
                                                {{ $item->product->formatted_price }}
                                            </td>
                                            <td class="text-center align-middle">
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST"
                                                      class="d-inline-flex align-items-center">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="number" name="quantity"
                                                           value="{{ $item->quantity }}"
                                                           min="1" max="{{ $item->product->stock }}"
                                                           class="form-control form-control-sm text-center"
                                                           style="width: 70px; background: rgba(15, 23, 42, 0.6); border: 1px solid #334155; color: white;"
                                                           onchange="this.form.submit()">
                                                </form>
                                            </td>
                                            <td class="text-end align-middle fw-bold text-custom">
                                                {{ $item->formatted_subtotal }}
                                            </td>
                                            <td class="align-middle">
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Hapus item ini?')"
                                                            style="transition: all 0.3s ease;">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="col-lg-4">
                <div class="card card-custom">
                    <div class="card-header border-secondary" style="background: transparent;">
                        <h5 class="mb-0 text-white">Ringkasan Belanja</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2 text-light">
                            <span>Total Harga ({{ $cart->items->sum('quantity') }} barang)</span>
                            <span class="text-custom">Rp {{ number_format($cart->items->sum('subtotal'), 0, ',', '.') }}</span>
                        </div>
                        <hr style="border-color: #334155;">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold text-white">Total</span>
                            <span class="fw-bold text-custom fs-5">
                                Rp {{ number_format($cart->items->sum('subtotal'), 0, ',', '.') }}
                            </span>
                        </div>
                        <a href="{{ route('checkout.index') }}" class="btn btn-custom w-100 btn-lg">
                            <i class="bi bi-credit-card me-2"></i>Checkout
                        </a>
                        <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary w-100 mt-2" style="border-color: #334155; color: #94a3b8;">
                            <i class="bi bi-arrow-left me-2"></i>Lanjut Belanja
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Empty Cart --}}
        <div class="text-center py-5">
            <i class="bi bi-cart-x display-1 text-secondary"></i>
            <h4 class="mt-3 text-white">Keranjang Kosong</h4>
            <p class="text-secondary">Belum ada produk di keranjang belanja kamu</p>
            <a href="{{ route('catalog.index') }}" class="btn btn-custom">
                <i class="bi bi-bag me-2"></i>Mulai Belanja
            </a>
        </div>
    @endif
</div>
@endsection

