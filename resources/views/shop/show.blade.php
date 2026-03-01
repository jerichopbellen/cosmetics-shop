@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="main-image-container border rounded bg-white p-3 mb-3 text-center shadow-sm">
                @php
                    $firstShade = $product->shades->first();
                    $defaultImage = $firstShade->image_path ?? ($product->images->first()->image_path ?? 'placeholder.jpg');
                @endphp
                <img id="main-display-image" src="{{ asset('storage/' . $defaultImage) }}" 
                     class="img-fluid" style="max-height: 500px; object-fit: contain;">
            </div>
            
            <div class="d-flex gap-2 overflow-auto pb-2 custom-scrollbar">
                @foreach($product->images as $img)
                    <img src="{{ asset('storage/' . $img->image_path) }}" 
                         class="img-thumbnail thumb-select" 
                         style="width: 80px; height: 80px; cursor: pointer; object-fit: cover;">
                @endforeach
            </div>
        </div>

        <div class="col-md-6">
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('shop.index') }}" class="text-decoration-none text-muted">Shop</a></li>
                    <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">{{ $product->category->name }}</li>
                </ol>
            </nav>

            <h1 class="fw-bold display-6 mb-1">{{ $product->name }}</h1>
            <p class="text-muted mb-3">Brand: <span class="text-dark fw-bold">{{ $product->brand->name }}</span></p>
            
            <div class="d-flex align-items-center gap-3 mb-4">
                <h2 class="fw-bold mb-0 text-pink" id="dynamic-price">
                    ${{ number_format($firstShade->price, 2) }}
                </h2>
                <span id="stock-status" class="badge {{ $firstShade->stock > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} px-3 py-2">
                    {{ $firstShade->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                </span>
            </div>

            <hr class="my-4">

            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="shade_id" id="selected-shade-id" value="{{ $firstShade->id }}">

                <div class="mb-4">
                    <label class="fw-bold d-block mb-3">
                        Shade: <span id="shade-name-display" class="text-muted fw-normal">{{ $firstShade->shade_name }}</span>
                    </label>
                    <div class="d-flex flex-wrap gap-3">
                        @foreach($product->shades as $shade)
                            <div class="shade-swatch {{ $loop->first ? 'active' : '' }}" 
                                 style="background-color: {{ $shade->hex_code }};"
                                 data-shade-id="{{ $shade->id }}"
                                 data-name="{{ $shade->shade_name }}"
                                 data-price="{{ number_format($shade->price, 2) }}"
                                 data-img="{{ asset('storage/' . ($shade->image_path ?? $product->images->first()->image_path)) }}"
                                 data-stock="{{ $shade->stock }}"
                                 title="{{ $shade->shade_name }}">
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="row g-3 align-items-center mb-4">
                    <div class="col-auto">
                        <label class="fw-bold small d-block mb-2">Quantity</label>
                        <div class="input-group" style="width: 140px;">
                            <button class="btn btn-outline-pink" type="button" onclick="stepQuantity(-1)">-</button>
                            <input type="number" name="quantity" id="quantity-input" class="form-control text-center border-pink" value="1" min="1" max="{{ $firstShade->stock }}">
                            <button class="btn btn-outline-pink" type="button" onclick="stepQuantity(1)">+</button>
                        </div>
                    </div>
                    <div class="col">
                        <label class="d-block mb-2">&nbsp;</label>
                        <button type="submit" id="add-to-cart-btn" class="btn btn-pink btn-lg w-100 py-3 shadow-sm" {{ $firstShade->stock <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </form>

            <div class="bg-light p-3 rounded mb-4">
                <div class="row small text-muted">
                    <div class="col-6 mb-2">Finish: <strong class="text-dark">{{ $product->finish }}</strong></div>
                    <div class="col-6 mb-2">Category: <strong class="text-dark">{{ $product->category->name }}</strong></div>
                </div>
            </div>

            <div>
                <h5 class="fw-bold mb-3">Product Description</h5>
                <p class="text-muted lh-lg">{{ $product->description }}</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // 1. Handle Shade Selection
        $('.shade-swatch').on('click', function() {
            const $this = $(this);
            
            // UI Updates
            $('.shade-swatch').removeClass('active');
            $this.addClass('active');
            
            // Text & Price Updates
            $('#shade-name-display').text($this.data('name'));
            $('#dynamic-price').text('$' + $this.data('price'));
            
            // Image Update with smooth fade
            $('#main-display-image').fadeOut(150, function() {
                $(this).attr('src', $this.data('img')).fadeIn(150);
            });

            // Update Hidden Form Field for Cart logic
            const shadeId = $this.data('shade-id');
            $('#selected-shade-id').val(shadeId);

            // Update Stock/Button Logic
            const stock = parseInt($this.data('stock'));
            const qtyInput = $('#quantity-input');
            const cartBtn = $('#add-to-cart-btn');
            
            qtyInput.attr('max', stock);
            if(stock <= 0) {
                $('#stock-status').text('Out of Stock').removeClass('bg-success-subtle text-success').addClass('bg-danger-subtle text-danger');
                cartBtn.prop('disabled', true).text('Out of Stock');
            } else {
                $('#stock-status').text('In Stock').removeClass('bg-danger-subtle text-danger').addClass('bg-success-subtle text-success');
                cartBtn.prop('disabled', false).html('<i class="fas fa-shopping-cart me-2"></i> Add to Cart');
            }
        });

        // 2. Handle Thumbnail Clicks
        $('.thumb-select').on('click', function() {
            const newSrc = $(this).attr('src');
            $('#main-display-image').attr('src', newSrc);
        });
    });

    // Quantity Increment/Decrement handler
    function stepQuantity(val) {
        const input = document.getElementById('quantity-input');
        const newVal = parseInt(input.value) + val;
        const max = parseInt(input.getAttribute('max'));
        if(newVal >= 1 && newVal <= max) {
            input.value = newVal;
        }
    }
</script>
@endpush
@endsection