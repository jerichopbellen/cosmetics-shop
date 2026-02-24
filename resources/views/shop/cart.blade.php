@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Your Shopping Cart</h2>
        <a href="{{ route('shop.index') }}" class="btn btn-outline-dark btn-sm">
            <i class="fa-solid fa-arrow-left me-2"></i>Continue Shopping
        </a>
    </div>

    @if(session('cart') && count(session('cart')) > 0)
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4 py-3">Product</th>
                                        <th class="py-3">Price</th>
                                        <th class="py-3">Quantity</th>
                                        <th class="py-3">Total</th>
                                        <th class="py-3 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $subtotal = 0 @endphp
                                    @foreach(session('cart') as $key => $details)
                                        @php $subtotal += $details['price'] * $details['quantity'] @endphp
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center py-2">
                                                    <img src="{{ asset('storage/' . $details['image']) }}" 
                                                         width="70" height="70" 
                                                         class="rounded border object-fit-cover me-3"
                                                         alt="{{ $details['product_name'] }}">
                                                    <div>
                                                        <div class="fw-bold text-dark">{{ $details['product_name'] }}</div>
                                                        <small class="text-muted text-uppercase small">Shade: {{ $details['shade_name'] }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>${{ number_format($details['price'], 2) }}</td>
                                            <td>
                                                <span class="badge bg-light text-dark border px-3 py-2">
                                                    {{ $details['quantity'] }}
                                                </span>
                                            </td>
                                            <td class="fw-bold">${{ number_format($details['price'] * $details['quantity'], 2) }}</td>
                                            <td class="text-center">
                                                <form action="{{ route('cart.remove', $key) }}" method="POST" 
                                                      onsubmit="return confirm('Remove this item from your cart?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger p-0 shadow-none">
                                                        <i class="fa-solid fa-trash-can fa-lg"></i>
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

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm bg-light sticky-top" style="top: 20px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Order Summary</h5>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Shipping</span>
                            <span class="text-success fw-bold">Calculated at Checkout</span>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="h5 mb-0 fw-bold">Total</span>
                            <span class="h4 mb-0 text-primary fw-bold">${{ number_format($subtotal, 2) }}</span>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('checkout.index') }}" class="btn btn-dark btn-lg py-3 fw-bold shadow-sm">
                                PROCEED TO CHECKOUT
                            </a>
                            <small class="text-center text-muted mt-2">
                                <i class="fa-solid fa-shield-halved me-1"></i> Secure Checkout
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fa-solid fa-cart-shopping fa-4x text-light"></i>
                </div>
                <h4 class="fw-bold">Your cart is feeling light!</h4>
                <p class="text-muted mb-4">Explore our collection and find something you love.</p>
                <a href="{{ route('shop.index') }}" class="btn btn-dark px-5 py-2">
                    Start Shopping
                </a>
            </div>
        </div>
    @endif
</div>
@endsection