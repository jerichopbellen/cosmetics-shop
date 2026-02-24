@extends('layouts.base')

@section('body')
<div class="container py-5 text-center">
    <div class="card border-0 shadow-sm p-5">
        <div class="mb-4">
            <i class="fa-solid fa-circle-check fa-5x text-success"></i>
        </div>
        <h1 class="fw-bold">Order Placed!</h1>
        <p class="text-muted fs-5">Thank you for shopping with GLOW.</p>
        <div class="bg-light p-3 rounded d-inline-block mb-4">
            <span class="text-muted">Order Number:</span>
            <span class="fw-bold text-dark">#{{ $order_number }}</span>
        </div>
        <div>
            <a href="{{ route('shop.index') }}" class="btn btn-dark px-5 py-3 fw-bold">CONTINUE SHOPPING</a>
        </div>
    </div>
</div>
@endsection