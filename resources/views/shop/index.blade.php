@extends('layouts.base') 
@section('body')
<div class="container py-5">
    <div class="row mb-5 align-items-end">
        <div class="col-md-6">
            <h1 class="fw-bold display-5">Our Collection</h1>
            <p class="text-muted">Discover our range of premium beauty essentials.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="dropdown">
                <button class="btn btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Sort By
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Newest First</a></li>
                    <li><a class="dropdown-item" href="#">Price: Low to High</a></li>
                    <li><a class="dropdown-item" href="#">Price: High to Low</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 border-0 shadow-sm product-card transition">
                    <div class="position-relative overflow-hidden rounded-3">
                        @php
                            // Use the first shade image as primary, or the first gallery image, or a placeholder
                            $primaryImage = $product->shades->first()?->image_path 
                                            ?? ($product->images->first()?->image_path ?? 'placeholders/product.jpg');
                        @endphp
                        
                        <a href="{{ route('shop.show', $product->id) }}">
                            <img src="{{ asset('storage/' . $primaryImage) }}" 
                                 class="card-img-top object-fit-cover" 
                                 style="height: 300px;" 
                                 alt="{{ $product->name }}">
                        </a>

                        <span class="badge bg-white text-dark position-absolute top-0 start-0 m-2 shadow-sm">
                            {{ $product->category->name }}
                        </span>

                        <div class="quick-add-btn position-absolute bottom-0 w-100 p-2 opacity-0">
                            <a href="{{ route('shop.show', $product->id) }}" class="btn btn-dark w-100 py-2">
                                <i class="fas fa-eye me-2"></i>View Details
                            </a>
                        </div>
                    </div>

                    <div class="card-body px-1 py-3 text-center">
                        <small class="text-uppercase text-muted fw-bold letter-spacing-1">
                            {{ $product->brand->name }}
                        </small>
                        <h6 class="card-title my-1">
                            <a href="{{ route('shop.show', $product->id) }}" class="text-decoration-none text-dark fw-bold">
                                {{ $product->name }}
                            </a>
                        </h6>
                        
                        @php
                            $minPrice = $product->shades->min('price');
                            $maxPrice = $product->shades->max('price');
                        @endphp
                        <p class="text-primary fw-bold mb-2">
                            @if($minPrice == $maxPrice)
                                ${{ number_format($minPrice, 2) }}
                            @else
                                ${{ number_format($minPrice, 2) }} - ${{ number_format($maxPrice, 2) }}
                            @endif
                        </p>

                        <div class="d-flex justify-content-center gap-1">
                            @foreach($product->shades->take(5) as $shade)
                                <div class="rounded-circle border border-2 shadow-xs" 
                                     style="width: 12px; height: 12px; background-color: {{ $shade->hex_code }};"
                                     title="{{ $shade->shade_name }}">
                                </div>
                            @endforeach
                            @if($product->shades->count() > 5)
                                <small class="text-muted">+{{ $product->shades->count() - 5 }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-box-open fa-3x text-light mb-3"></i>
                <p class="text-muted">No products found in this category.</p>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $products->links() }}
    </div>
</div>

<style>
    .product-card:hover {
        transform: translateY(-5px);
    }
    .product-card:hover .quick-add-btn {
        opacity: 1 !important;
        transition: 0.3s ease-in-out;
    }
    .letter-spacing-1 {
        letter-spacing: 1px;
    }
    .object-fit-cover {
        object-fit: cover;
    }
    .transition {
        transition: all 0.3s ease;
    }
</style>
@endsection