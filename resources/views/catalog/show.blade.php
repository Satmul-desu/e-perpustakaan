@extends('layouts.app')
@section('title', $product->name)
@section('content')
    <div class="container py-4">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}">Katalog</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('catalog.index', ['category' => $product->category->slug]) }}">
                        {{ $product->category->name }}
                    </a>
                </li>
                <li class="breadcrumb-item active">{{ Str::limit($product->name, 30) }}</li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="position-relative">
                        <img src="{{ $product->image_url }}" id="main-image" class="card-img-top" alt="{{ $product->name }}"
                            style="height: 400px; object-fit: contain; background: #f8f9fa;">
                        @if ($product->has_discount)
                            <span class="badge bg-danger position-absolute top-0 start-0 m-3 fs-6">
                                -{{ $product->discount_percentage }}%
                            </span>
                        @endif
                    </div>
                    @if ($product->images->count() > 1)
                        <div class="card-body">
                            <div class="d-flex gap-2 overflow-auto">
                                @foreach ($product->images as $image)
                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                        class="rounded border cursor-pointer"
                                        style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                                        onclick="document.getElementById('main-image').src = this.src">
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <a href="{{ route('catalog.index', ['category' => $product->category->slug]) }}"
                            class="badge bg-light text-dark text-decoration-none mb-2">
                            {{ $product->category->name }}
                        </a>
                        <h2 class="mb-3">{{ $product->name }}</h2>

                        <div class="mb-4">
                            @if ($product->stock > 10)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i> Stok Tersedia
                                </span>
                            @elseif($product->stock > 0)
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-exclamation-triangle me-1"></i> Stok Tinggal {{ $product->stock }}
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="bi bi-x-circle me-1"></i> Stok Habis
                                </span>
                            @endif
                        </div>
                        <form action="{{ route('loans.store') }}" method="POST" class="mb-4">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $product->id }}">
                            <div class="row g-3 align-items-end">
                                <div class="col-auto">
                                    <label class="form-label">Durasi Pinjam (hari)</label>
                                    <div class="input-group" style="width: 140px;">
                                        <input type="number" name="duration" id="duration" value="7" min="1" max="30" class="form-control text-center">
                                    </div>
                                </div>
                                <div class="col">
                                    <button type="submit" class="btn btn-primary btn-lg w-100" @if ($product->stock == 0) disabled @endif>
                                        <i class="bi bi-book me-2"></i>
                                        Pinjam Buku
                                    </button>
                                </div>
                            </div>
                        </form>
                        @auth
                            <button type="button" onclick="toggleWishlist({{ $product->id }})"
                                class="btn btn-outline-danger mb-4 wishlist-btn-{{ $product->id }}">
                                <i
                                    class="bi {{ auth()->user()->hasInWishlist($product) ? 'bi-heart-fill' : 'bi-heart' }} me-2"></i>
                                {{ auth()->user()->hasInWishlist($product) ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist' }}
                            </button>
                        @endauth
                        <hr>
                        <div class="mb-3">
                            <h6>Deskripsi</h6>
                            <p class="text-muted">{!! nl2br(e($product->description)) !!}</p>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        
        <!-- Buku Serupa Section -->
        <div class="row mt-5">
            <div class="col-12 border-top pt-5">
                <h4 class="mb-4 d-flex align-items-center">
                    <i class="bi bi-stars text-warning me-2"></i> Buku yang mungkin Anda suka
                </h4>
                @if(isset($relatedBooks) && $relatedBooks->count() > 0)
                    <div class="row row-cols-2 row-cols-md-4 g-4">
                        @foreach($relatedBooks as $related)
                            <div class="col">
                                <a href="{{ route('catalog.show', $related->slug) }}" class="text-decoration-none">
                                    <div class="card h-100 border-0 shadow-sm transition-hover">
                                        <div class="position-relative bg-light rounded-top">
                                            <img src="{{ $related->image_url }}" class="card-img-top w-100 p-2" alt="{{ $related->name }}" style="height: 200px; object-fit: contain;">
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="text-muted small mt-2"><i class="bi bi-tag me-1"></i> {{ optional($related->category)->name ?? 'Buku' }}</div>
                                        </div>

                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-light text-muted border-0">
                        <i class="bi bi-info-circle me-2"></i> Belum ada buku terkait dalam kategori ini.
                    </div>
                @endif
            </div>
        </div>
    </div>
    @push('scripts')
    @endpush
@endsection
