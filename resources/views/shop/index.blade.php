@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="row mb-5 align-items-end">
        <div class="col-md-5">
            <h1 class="fw-bold display-5">Our Collection</h1>
            <p class="text-muted">Discover premium beauty essentials for every look.</p>
        </div>
        <div class="col-md-7">
            <div class="d-flex flex-column flex-md-row gap-3 justify-content-md-end">
                <form action="{{ route('shop.index') }}" method="GET" class="d-flex" style="max-width: 400px;">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control border-pink shadow-none" 
                               placeholder="Search brand, category, or product..." value="{{ request('search') }}">
                        <button class="btn btn-pink px-3" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <div class="dropdown">
                    <button class="btn btn-outline-pink px-3 shadow-none w-100 h-100" type="button" data-bs-toggle="dropdown">
                        Sort By <i class="fas fa-chevron-down ms-1 small"></i>
                    </button>
                    <ul class="dropdown-menu border-0 shadow-sm dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Newest First</a></li>
                        <li><a class="dropdown-item" href="#">Price: Low to High</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @if(request('search'))
        <div class="mb-4 d-flex align-items-center justify-content-between">
            <p class="mb-0 text-muted">Showing results for "<strong>{{ request('search') }}</strong>"</p>
            <a href="{{ route('shop.index') }}" class="btn btn-sm btn-link text-pink text-decoration-none">Clear All</a>
        </div>
    @endif

    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 border-0 shadow-sm product-card transition-all">
                    <div class="position-relative overflow-hidden rounded shadow-sm">
                        @php
                            $primaryImage = $product->images->first()?->image_path ?? 'placeholders/product.png';
                        @endphp       
                        <a href="{{ route('shop.show', $product->id) }}">
                            <img src="{{ asset('storage/' . $primaryImage) }}" 
                                 class="card-img-top object-fit-cover" 
                                 style="height: 280px;" 
                                 alt="{{ $product->name }}">
                        </a>
                        <span class="badge bg-white text-dark position-absolute top-0 start-0 m-2 shadow-sm">
                            {{ $product->category->name }}
                        </span>
                    </div>

                    <div class="card-body px-1 py-3 text-center">
                        <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">
                            {{ $product->brand->name }}
                        </small>
                        <h6 class="card-title my-1">
                            <a href="{{ route('shop.show', $product->id) }}" class="text-decoration-none text-dark fw-bold">
                                {{ $product->name }}
                            </a>
                        </h6>
                        
                        @php
                            $minPrice = $product->shades->min('price');
                        @endphp
                        <p class="text-pink fw-bold mb-2">
                            ₱{{ number_format($minPrice, 2) }}
                        </p>

                        <div class="d-flex justify-content-center gap-1">
                            @foreach($product->shades->take(4) as $shade)
                                <div class="rounded-circle border border-light" 
                                     style="width: 12px; height: 12px; background-color: {{ $shade->hex_code }};"
                                     title="{{ $shade->shade_name }}">
                                </div>
                            @endforeach
                            @if($product->shades->count() > 4)
                                <small class="text-muted" style="font-size: 0.6rem;">+{{ $product->shades->count() - 4 }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 mt-4">
                <div class="mb-3">
                    <i class="fas fa-search-minus fa-4x text-light"></i>
                </div>
                <h4 class="text-muted">No matches found</h4>
                <p class="text-muted">Try checking your spelling or use more general terms.</p>
                <a href="{{ route('shop.index') }}" class="btn btn-pink mt-2">Back to Shop</a>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $products->links() }}
    </div>
</div>
@endsection