@props(['product'])
<div class="card h-100 border-0 shadow-sm product-card">
    <div class="position-relative overflow-hidden bg-light" style="padding-top: 100%;">
        <img src="{{ $product->image_url }}"
             class="card-img-top position-absolute top-0 start-0 w-100 h-100 object-fit-cover"
             alt="{{ $product->name }}">
        @if($product->has_discount)
             <span class="position-absolute top-0 start-0 m-2 badge bg-danger">
                 -{{ $product->discount_percentage }}%
             </span>
        @endif
    </div>
    <div class="card-body d-flex flex-column">
        <small class="text-muted mb-1">{{ $product->category->name }}</small>
        <h6 class="card-title mb-2">
            <a href="{{ route('catalog.show', $product->slug) }}" class="text-decoration-none text-white stretched-link">
                {{ $product->name }}
            </a>
        </h6>
        @if($product->description)
            <p class="card-text small text-muted mb-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                {{ \Illuminate\Support\Str::limit($product->description, 80) }}
            </p>
        @endif
        <div class="mt-auto">
            @if($product->has_discount)
                <p class="fw-bold text-danger mb-0">{{ $product->formatted_price }}</p>
                <small class="text-decoration-line-through text-muted">{{ $product->formatted_original_price }}</small>
            @else
                <p class="fw-bold text-primary mb-0">{{ $product->formatted_price }}</p>
            @endif
        </div>
    </div>
</div>