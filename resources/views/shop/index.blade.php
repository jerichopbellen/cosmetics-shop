@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="row mb-5 align-items-end">
        <div class="col-md-5">
            <h1 class="fw-bold display-5">Our Collection</h1>
            <p class="text-muted">Discover premium beauty essentials for every look.</p>
        </div>
        <div class="col-md-7 text-md-end">
             <a href="{{ route('shop.index') }}" class="btn btn-sm btn-link text-pink text-decoration-none {{ !request()->anyFilled(['search', 'category', 'brand', 'min_price', 'max_price']) ? 'd-none' : '' }}">
                <i class="fas fa-times me-1"></i> Clear All Filters
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm p-4 sticky-top" style="top: 2rem; border-radius: 15px;">
                <form action="{{ route('shop.index') }}" method="GET">
                    
                    <h6 class="fw-bold mb-3 text-uppercase small">Search</h6>
                    <div class="input-group mb-4">
                        <input type="text" name="search" class="form-control border-pink shadow-none" 
                               placeholder="Keywords..." value="{{ request('search') }}">
                        <button class="btn btn-pink" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>

                    <h6 class="fw-bold mb-3 text-uppercase small">Category</h6>
                    <select name="category" class="form-select border-pink shadow-none mb-4">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->name }}" {{ request('category') == $cat->name ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <h6 class="fw-bold mb-3 text-uppercase small">Brand</h6>
                    <select name="brand" class="form-select border-pink shadow-none mb-4">
                        <option value="">All Brands</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->name }}" {{ request('brand') == $brand->name ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>

                    <h6 class="fw-bold mb-3 text-uppercase small">Price Range (₱)</h6>
                    <div class="d-flex gap-2 mb-4">
                        <input type="number" name="min_price" class="form-control border-pink shadow-none" 
                               placeholder="Min" value="{{ request('min_price') }}">
                        <input type="number" name="max_price" class="form-control border-pink shadow-none" 
                               placeholder="Max" value="{{ request('max_price') }}">
                    </div>

                    <button type="submit" class="btn btn-pink w-100 shadow-sm fw-bold py-2">
                        Apply Filters
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-9">
            @if(request()->anyFilled(['search', 'category', 'brand', 'min_price', 'max_price']))
                <div class="mb-4">
                    <span class="text-muted small">Active Filters:</span>
                    @if(request('search')) <span class="badge bg-pink ms-1">{{ request('search') }}</span> @endif
                    @if(request('category')) <span class="badge bg-secondary ms-1">{{ request('category') }}</span> @endif
                    @if(request('brand')) <span class="badge bg-dark ms-1">{{ request('brand') }}</span> @endif
                    
                    {{-- Price Range Badges --}}
                    @if(request('min_price') && request('max_price'))
                        <span class="badge border border-pink text-dark ms-1">₱{{ number_format(request('min_price')) }} - ₱{{ number_format(request('max_price')) }}</span>
                    @elseif(request('min_price'))
                        <span class="badge border border-pink text-dark ms-1">Above ₱{{ number_format(request('min_price')) }}</span>
                    @elseif(request('max_price'))
                        <span class="badge border border-pink text-dark ms-1">Under ₱{{ number_format(request('max_price')) }}</span>
                    @endif
                </div>
            @endif

            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-6 col-md-4">
                        <div class="card h-100 border-0 shadow-sm product-card transition-all" style="border-radius: 12px;">
                            <div class="position-relative overflow-hidden rounded-top">
                                @php
                                    $primaryImage = $product->images->first()?->image_path ?? 'placeholders/product.png';
                                @endphp       
                                <a href="{{ route('shop.show', $product->id) }}">
                                    <img src="{{ asset('storage/' . $primaryImage) }}" 
                                         class="card-img-top object-fit-cover" 
                                         style="height: 250px;" 
                                         alt="{{ $product->name }}">
                                </a>
                                <span class="badge bg-white text-dark position-absolute top-0 start-0 m-2 shadow-sm">
                                    {{ $product->category->name }}
                                </span>
                            </div>

                            <div class="card-body px-2 py-3 text-center">
                                <small class="text-uppercase text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">
                                    {{ $product->brand->name }}
                                </small>
                                <h6 class="card-title my-1">
                                    <a href="{{ route('shop.show', $product->id) }}" class="text-decoration-none text-dark fw-bold">
                                        {{ $product->name }}
                                    </a>
                                </h6>
                                
                                @php $minPrice = $product->shades->min('price'); @endphp
                                <p class="text-pink fw-bold mb-2">
                                    ₱{{ number_format($minPrice, 2) }}
                                </p>

                                <div class="d-flex justify-content-center gap-1">
                                    @foreach($product->shades->take(5) as $shade)
                                        <div class="rounded-circle border border-light shadow-xs" 
                                             style="width: 10px; height: 10px; background-color: {{ $shade->hex_code }};"
                                             title="{{ $shade->shade_name }}">
                                        </div>
                                    @endforeach
                                    @if($product->shades->count() > 5)
                                        <span class="text-muted" style="font-size: 0.6rem;">+{{ $product->shades->count() - 5 }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-search-minus fa-4x text-light"></i>
                        </div>
                        <h4 class="text-muted">No products found</h4>
                        <p class="text-muted small">Try adjusting your filters or search terms.</p>
                        <a href="{{ route('shop.index') }}" class="btn btn-pink btn-sm mt-2">Reset All</a>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

@endsection