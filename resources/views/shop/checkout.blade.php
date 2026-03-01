@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h2 class="fw-bold mb-4 text-center">Checkout</h2>
            
            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-md-7">
                        <div class="card border-0 shadow-sm p-4">
                            <h5 class="fw-bold mb-4 border-bottom pb-2">Shipping Information</h5>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Shipping Address</label>
                                <textarea name="address" class="form-control" rows="3" placeholder="Street, City, Postal Code" required></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">Phone Number</label>
                                    <input type="text" name="phone" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">City</label>
                                    <input type="text" name="city" class="form-control" required>
                                </div>
                            </div>

                            <h5 class="fw-bold mt-4 mb-4 border-bottom pb-2">Payment Method</h5>
                            <div class="form-check border rounded p-3 mb-2">
                                <input class="form-check-input ms-0 me-2" type="radio" name="payment_method" id="cod" value="COD" checked>
                                <label class="form-check-label fw-bold" for="cod">
                                    Cash on Delivery (COD)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="card border-0 shadow-sm bg-light p-4 sticky-top" style="top: 20px;">
                            <h5 class="fw-bold mb-4">Your Order</h5>
                            
                            @php $subtotal = 0 @endphp
                            @foreach($cart as $item)
                                @php $subtotal += $item['price'] * $item['quantity'] @endphp
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/' . $item['image']) }}" width="50" class="rounded border me-3">
                                        <div>
                                            <div class="small fw-bold">{{ $item['product_name'] }}</div>
                                            <small class="text-muted">{{ $item['shade_name'] }} x{{ $item['quantity'] }}</small>
                                        </div>
                                    </div>
                                    <span class="small fw-bold">${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                </div>
                            @endforeach

                            <hr>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span class="fw-bold">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3 text-success">
                                <span>Shipping</span>
                                <span class="fw-bold">FREE</span>
                            </div>
                            
                            <div class="d-flex justify-content-between h4 fw-bold mt-3 border-top pt-3">
                                <span>Total</span>
                                <span style="color: #ec4899;">${{ number_format($subtotal, 2) }}</span>
                            </div>

                            <button type="submit" class="btn btn-lg w-100 mt-4 py-3 fw-bold" style="background-color: #ec4899; border-color: #ec4899; color: white;">
                                PLACE ORDER NOW
                            </button>
                            <p class="text-muted small text-center mt-3">
                                <i class="fa-solid fa-lock me-1"></i> Your transaction is secure.
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection