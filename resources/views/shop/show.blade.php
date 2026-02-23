@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="main-image-container border rounded bg-white p-3 mb-3 text-center">
                @php
                    $firstShade = $product->shades->first();
                @endphp
                <img id="main-display-image" src="{{ asset('storage/' . ($firstShade->image_path ?? $product->images->first()->image_path)) }}" 
                     class="img-fluid" style="max-height: 500px; object-fit: contain;">
            </div>
            
            <div class="d-flex gap-2 overflow-auto pb-2">
                @foreach($product->images as $img)
                    <img src="{{ asset('storage/' . $img->image_path) }}" 
                         class="img-thumbnail thumb-select" 
                         style="width: 80px; height: 80px; cursor: pointer; object-fit: cover;">
                @endforeach
            </div>
        </div>

        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Shop</a></li>
                    <li class="breadcrumb-item active">{{ $product->category->name }}</li>
                </ol>
            </nav>

            <h1 class="fw-bold">{{ $product->name }}</h1>
            <p class="text-muted mb-1">Brand: <span class="fw-bold">{{ $product->brand->name }}</span></p>
            
            <h2 class="text-primary fw-bold my-3" id="dynamic-price">
                ${{ number_format($firstShade->price, 2) }}
            </h2>

            <hr>

            <div class="mb-4">
                <label class="fw-bold d-block mb-2">Select Shade: <span id="shade-name-display" class="text-muted fw-normal">{{ $firstShade->shade_name }}</span></label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($product->shades as $shade)
                        <div class="shade-swatch {{ $loop->first ? 'active' : '' }}" 
                             style="background-color: {{ $shade->hex_code }};"
                             data-name="{{ $shade->shade_name }}"
                             data-price="{{ number_format($shade->price, 2) }}"
                             data-img="{{ asset('storage/' . $shade->image_path) }}"
                             data-stock="{{ $shade->stock }}">
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <p class="small text-muted mb-1">Finish: <strong>{{ $product->finish }}</strong></p>
                <p class="small mb-0">Availability: <span id="stock-status" class="text-success fw-bold">In Stock ({{ $firstShade->stock }})</span></p>
            </div>

            <div class="d-grid gap-2">
                <button class="btn btn-dark btn-lg py-3 shadow-sm">
                    <i class="fas fa-shopping-bag me-2"></i> Add to Cart
                </button>
            </div>

            <div class="mt-4">
                <h5>Description</h5>
                <p class="text-muted">{{ $product->description }}</p>
            </div>
        </div>
    </div>
</div>

<style>
    .shade-swatch {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        cursor: pointer;
        border: 3px solid #eee;
        transition: 0.2s;
    }
    .shade-swatch.active {
        border-color: #000;
        transform: scale(1.1);
    }
    .thumb-select:hover { border-color: #000; }
</style>

@push('scripts')
<script>
    $(document).ready(function() {
        // Handle Shade Selection
        $('.shade-swatch').on('click', function() {
            $('.shade-swatch').removeClass('active');
            $(this).addClass('active');

            // Update Price, Name, and Image
            $('#dynamic-price').text('$' + $(this).data('price'));
            $('#shade-name-display').text($(this).data('name'));
            $('#main-display-image').attr('src', $(this).data('img'));
            
            // Update Stock Status
            let stock = $(this).data('stock');
            if(stock > 0) {
                $('#stock-status').text(`In Stock (${stock})`).removeClass('text-danger').addClass('text-success');
            } else {
                $('#stock-status').text('Out of Stock').removeClass('text-success').addClass('text-danger');
            }
        });

        // Handle Gallery Thumbnail Clicks
        $('.thumb-select').on('click', function() {
            $('#main-display-image').attr('src', $(this).attr('src'));
        });
    });
</script>
@endpush
@endsection