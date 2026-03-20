@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg">
                <div style="height: 5px; background-color: #ec4899;"></div>
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fa-solid fa-paper-plane fa-3x text-pink mb-3" style="color: #ec4899;"></i>
                        <h3 class="fw-bold">Resend Verification</h3>
                        <p class="text-muted small">Enter your email and we'll send you a new link.</p>
                    </div>

                    <form action="{{ route('verification.resend.post') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase text-muted">Email Address</label>
                            <input type="email" name="email" class="form-control border-pink shadow-none" 
                                   placeholder="name@example.com" required style="border-color: #ec4899;">
                            @error('email')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-pink w-100 py-2 fw-bold text-white shadow-sm" 
                                style="background-color: #ec4899; border: none;">
                            Send New Link
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="text-decoration-none text-muted small">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection