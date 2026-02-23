@extends('layouts.base')

@section('body')
<div class="container py-5">
    <h2 class="fw-bold mb-4">Your Shopping Cart</h2>

    @if(session('cart'))
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-3">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0 @endphp
                            @foreach(session('cart') as $id => $details)
                                @php $total += $details['price'] * $details['quantity'] @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . $details['image']) }}" width="60" height="60" class="rounded me-3 border">
                                            <div>
                                                <div class="fw-bold">{{ $details['product_name'] }}</div>
                                                <small class="text-muted">Shade: {{ $details['shade_name'] }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format($details['price'], 2) }}</td>
                                    <td>{{ $details['quantity'] }}</td>
                                    <td>${{ number_format($details['price'] * $details['quantity'], 2) }}</td>
                                    <td><button class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4 bg-light">
                    <h5 class="fw-bold">Order Summary</h5>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal</span>
                        <span class="fw-bold">${{ number_format($total, 2) }}</span>
                    </div>
                    <button class="btn btn-primary w-100 py-3 fw-bold">Proceed to Checkout</button>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <p class="text-muted">Your cart is empty.</p>
            <a href="{{ route('shop.index') }}" class="btn btn-dark">Start Shopping</a>
        </div>
    @endif
</div>
@endsection