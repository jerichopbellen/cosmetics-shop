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
                <button class="btn btn-outline-pink btn-sm px-3 shadow-none" type="button" data-bs-toggle="dropdown">
                    Sort By <i class="fas fa-chevron-down ms-1 small"></i>
                </button>
                <ul class="dropdown-menu border-0 shadow-sm">
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
                <div class="card h-100 border shadow-sm product-card transition-all">
                    <div class="position-relative overflow-hidden rounded-top">
                        @php
                            $primaryImage = $product->images->first()?->image_path ?? 'placeholders/product.jpg';
                        @endphp
                        
                        <a href="{{ route('shop.show', $product->id) }}">
                            <img src="{{ asset('storage/' . $primaryImage) }}" 
                                 class="card-img-top object-fit-cover" 
                                 style="height: 300px;" 
                                 alt="{{ $product->name }}">
                        </a>

                        <span class="badge bg-white text-dark position-absolute top-0 start-0 m-2 shadow-sm border">
                            {{ $product->category->name }}
                        </span>
                    </div>

                    <div class="card-body px-1 py-3 text-center">
                        <small class="text-uppercase text-muted fw-bold" style="letter-spacing: 1px; font-size: 0.7rem;">
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
                        <p class="text-pink fw-bold mb-2">
                            @if($minPrice === $maxPrice)
                                ${{ number_format($minPrice, 2) }}
                            @else
                                ${{ number_format($minPrice, 2) }} - ${{ number_format($maxPrice, 2) }}
                            @endif
                        </p>

                        <div class="d-flex justify-content-center gap-1">
                            @foreach($product->shades->take(5) as $shade)
                                <div class="rounded-circle border border-white shadow-sm" 
                                     style="width: 14px; height: 14px; background-color: {{ $shade->hex_code }};"
                                     title="{{ $shade->shade_name }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-box-open fa-3x text-light mb-3"></i>
                <p class="text-muted">No products found.</p>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $products->links() }}
    </div>
</div>

<style>
    /* Simple Border Transition */
    .product-card {
        border: 1px solid transparent !important;
        transition: all 0.2s ease-in-out;
    }

    .product-card:hover {
        border-color: #ec4899 !important;
        box-shadow: 0 5px 15px rgba(236, 72, 153, 0.1) !important;
    }

    /* Transition helper */
    .transition-all {
        transition: all 0.2s ease-in-out;
    }
</style>
@endsection