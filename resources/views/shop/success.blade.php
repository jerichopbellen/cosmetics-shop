@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 text-center">
            <div class="card border-0 shadow-sm p-5">
                <div class="mb-4">
                    <i class="fa-solid fa-circle-check fa-5x text-pink"></i>
                </div>
                
                <h1 class="fw-bold mb-2">Order Placed!</h1>
                <p class="text-muted fs-5 mb-4">Thank you for shopping with <span class="fw-bold text-pink">GLOW</span>.</p>
                
                <div class="bg-light p-3 rounded-pill d-inline-block mb-5 border border-pink px-4">
                    <span class="text-muted me-1">Order Number:</span>
                    <span class="fw-bold text-dark">#{{ $order_number }}</span>
                </div>
                
                <div class="d-grid gap-3">
                    <a href="{{ route('shop.index') }}" class="btn btn-pink btn-lg py-3 fw-bold shadow-sm text-uppercase">
                        Continue Shopping
                    </a>
                    
                    <a href="{{ route('orders.my') }}" class="btn btn-link text-muted text-decoration-none small">
                        View Orders
                    </a>
                </div>

                <div class="mt-5 border-top pt-4">
                    <p class="small text-muted">
                        A confirmation email has been sent to your inbox. <br>
                        Need help? <a href="#" class="text-pink">Contact Support</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection