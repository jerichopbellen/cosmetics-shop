@extends('layouts.base')

@section('body')
<div class="container py-5 mt-4">
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="main-image-container border rounded bg-white p-3 mb-3 text-center shadow-sm position-relative">
                @php
                    $firstShade = $product->shades->first();
                    $defaultImage = $firstShade->image_path ?? ($product->images->first()->image_path ?? 'placeholders/product.png');
                @endphp
                <img id="main-display-image" src="{{ asset("storage/{$defaultImage}") }}" 
                     class="img-fluid" style="max-height: 500px; object-fit: contain;">
            </div>
            
            <div class="d-flex gap-2 overflow-auto pb-2 custom-scrollbar">
                @foreach($product->images as $img)
                    <img src="{{ asset("storage/" . ($img->image_path ?? 'placeholders/product.png')) }}" 
                         class="img-thumbnail thumb-select border-pink-hover" 
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

            <h1 class="fw-bold display-6 mb-1 text-dark">{{ $product->name }}</h1>
            <p class="text-muted mb-3">Brand: <span class="text-dark fw-bold">{{ $product->brand->name }}</span></p>
            
            <div class="d-flex align-items-center gap-3 mb-4">
                <h2 class="fw-bold mb-0 text-pink" id="dynamic-price">
                    ₱{{ number_format($firstShade->price, 2) }}
                </h2>
                <span id="stock-status" class="badge {{ $firstShade->stock > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} px-3 py-2">
                    {{ $firstShade->stock > 0 ? "In Stock ({$firstShade->stock})" : 'Out of Stock' }}
                </span>
            </div>

            <hr class="my-4 opacity-50">

            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="shade_id" id="selected-shade-id" value="{{ $firstShade->id }}">

                <div class="mb-4">
                    <label class="fw-bold d-block mb-3">
                        Shade: <span id="shade-name-display" class="text-muted fw-normal small ms-1">{{ $firstShade->shade_name }}</span>
                    </label>
                    <div class="d-flex flex-wrap gap-3">
                        @foreach($product->shades as $shade)
                            <div class="shade-swatch {{ $loop->first ? 'active' : '' }}" 
                                 style="background-color: {{ $shade->hex_code }};"
                                 data-shade-id="{{ $shade->id }}"
                                 data-name="{{ $shade->shade_name }}"
                                 data-price="{{ number_format($shade->price, 2) }}"
                                 data-img="{{ asset("storage/" . ($shade->image_path ?? $product->images->first()->image_path ?? 'placeholders/product.png')) }}"
                                 data-stock="{{ $shade->stock }}"
                                 title="{{ $shade->shade_name }}">
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="row g-3 align-items-center mb-4">
                    <div class="col-auto">
                        <label class="fw-bold small d-block mb-2 text-muted uppercase">Quantity</label>
                        <div class="input-group" style="width: 140px;">
                            <button class="btn btn-outline-pink" type="button" onclick="stepQuantity(-1)">-</button>
                            <input type="number" name="quantity" id="quantity-input" class="form-control text-center border-pink shadow-none" value="1" min="1" max="{{ $firstShade->stock }}">
                            <button class="btn btn-outline-pink" type="button" onclick="stepQuantity(1)">+</button>
                        </div>
                    </div>
                    <div class="col">
                        <label class="d-block mb-2">&nbsp;</label>
                        <button type="submit" id="add-to-cart-btn" class="btn btn-pink btn-lg w-100 py-3 shadow-sm fw-bold" {{ $firstShade->stock <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </form>

            <div class="bg-light p-3 rounded mb-4 border border-pink-subtle">
                <div class="row small text-muted">
                    <div class="col-6 mb-2">Finish: <strong class="text-dark">{{ $product->finish }}</strong></div>
                    <div class="col-6 mb-2">Category: <strong class="text-dark">{{ $product->category->name }}</strong></div>
                </div>
            </div>

            <div class="product-description mt-4">
                <h5 class="fw-bold mb-3 border-bottom pb-2">Product Description</h5>
                <p class="text-muted lh-lg" style="text-align: justify;">{{ $product->description }}</p>
            </div>
        </div>
    </div>

    <div class="row mt-5 pt-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                <h3 class="fw-bold mb-0">Customer Reviews</h3>
                <div class="text-warning fs-5">
                    @php $avg = $product->reviews->avg('rating') ?? 0; @endphp
                    @for($i=1; $i<=5; $i++)
                        <i class="{{ $i <= round($avg) ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                    @endfor
                    <span class="text-muted small ms-2 fw-normal">({{ $product->reviews->count() }} reviews)</span>
                </div>
            </div>

            <div class="reviews-list">
                @forelse($product->reviews->sortByDesc('created_at') as $review)
                    <div class="card border-0 shadow-sm mb-4 review-card">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center mb-3">
                                        {{-- Avatar Logic --}}
                                        @if($review->user->image_path)
                                            <img src="{{ asset('storage/' . $review->user->image_path) }}" 
                                                 class="rounded-circle me-3 border border-pink-subtle shadow-sm" 
                                                 style="width: 48px; height: 48px; object-fit: cover;">
                                        @else
                                            <div class="bg-pink text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" 
                                                 style="width: 48px; height: 48px; font-size: 1.2rem;">
                                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                            </div>
                                        @endif

                                        <div>
                                            <h6 class="fw-bold mb-0">{{ $review->user->name }}</h6>
                                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    
                                    <div class="text-warning mb-2" style="font-size: 0.9rem;">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="{{ $i <= $review->rating ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                        @endfor
                                        <span class="ms-2 badge bg-light text-pink border border-pink-subtle fw-normal rounded-pill">
                                            Shade: {{ $review->shade->shade_name ?? 'N/A' }}
                                        </span>
                                    </div>

                                    <p class="text-dark mb-0 fs-6 lh-base">{{ $review->comment }}</p>
                                </div>
                                
                                @if($review->photo_path)
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <img src="{{ asset('storage/' . $review->photo_path) }}" 
                                         class="rounded border border-pink-subtle shadow-sm review-img-pop" 
                                         style="width: 130px; height: 130px; object-fit: cover; cursor: pointer;"
                                         data-bs-toggle="modal" data-bs-target="#imageModal{{ $review->id }}">
                                </div>

                                <div class="modal fade" id="imageModal{{ $review->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 bg-transparent">
                                            <div class="modal-body p-0 text-center">
                                                <img src="{{ asset('storage/' . $review->photo_path) }}" class="img-fluid rounded shadow-lg">
                                                <button type="button" class="btn btn-light mt-3 rounded-pill fw-bold px-4" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 bg-white rounded shadow-sm">
                        <i class="fa-regular fa-comments fa-3x text-pink-subtle mb-3"></i>
                        <h5 class="text-muted fw-normal">No reviews for this product yet.</h5>
                        <p class="text-muted small">Purchase this product to share your experience!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    .text-pink { color: #ec4899 !important; }
    .bg-pink { background-color: #ec4899 !important; }
    .btn-pink { background-color: #ec4899; color: white; border: none; transition: 0.3s; }
    .btn-pink:hover { background-color: #be185d; color: white; transform: translateY(-2px); }
    .btn-outline-pink { color: #ec4899; border-color: #ec4899; }
    .btn-outline-pink:hover { background-color: #ec4899; color: white; }
    .border-pink { border-color: #ec4899 !important; }
    .border-pink-subtle { border-color: #fce7f3 !important; }
    .text-pink-subtle { color: #fce7f3 !important; }

    .shade-swatch {
        width: 32px; height: 32px; border-radius: 50%; cursor: pointer;
        border: 2px solid #fff; box-shadow: 0 0 0 1px #dee2e6; transition: 0.2s;
    }
    .shade-swatch.active { box-shadow: 0 0 0 2px #ec4899; transform: scale(1.1); }
    .thumb-select:hover { border-color: #ec4899 !important; }
    
    .review-card { transition: 0.3s; border-left: 4px solid transparent !important; }
    .review-card:hover { border-left: 4px solid #ec4899 !important; transform: translateX(5px); }
    .review-img-pop:hover { opacity: 0.9; transform: scale(1.03); transition: 0.2s; }

    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #fce7f3; border-radius: 10px; }
</style>

@push('scripts')
<script>
    $(document).ready(function() {
        // Handle Shade Selection
        $('.shade-swatch').on('click', function() {
            const $this = $(this);
            $('.shade-swatch').removeClass('active');
            $this.addClass('active');
            
            $('#shade-name-display').text($this.data('name'));
            $('#dynamic-price').text('₱' + $this.data('price'));
            
            $('#main-display-image').fadeOut(150, function() {
                $(this).attr('src', $this.data('img')).fadeIn(150);
            });

            $('#selected-shade-id').val($this.data('shade-id'));

            const stock = parseInt($this.data('stock'));
            const qtyInput = $('#quantity-input');
            const cartBtn = $('#add-to-cart-btn');
            
            qtyInput.attr('max', stock);
            if(stock <= 0) {
                $('#stock-status').text('Out of Stock').removeClass('bg-success-subtle text-success').addClass('bg-danger-subtle text-danger');
                cartBtn.prop('disabled', true).text('Out of Stock');
                qtyInput.val(0);
            } else {
                $('#stock-status').text(`In Stock (${stock})`).removeClass('bg-danger-subtle text-danger').addClass('bg-success-subtle text-success');
                cartBtn.prop('disabled', false).html('<i class="fas fa-shopping-cart me-2"></i> Add to Cart');
                if(qtyInput.val() == 0) qtyInput.val(1);
            }
        });

        // Handle Thumbnail Clicks
        $('.thumb-select').on('click', function() {
            const newSrc = $(this).attr('src');
            $('#main-display-image').attr('src', newSrc);
        });
    });

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