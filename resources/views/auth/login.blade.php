@extends('layouts.base')

@section('body')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div style="height: 5px; background-color: #ec4899;"></div>
                
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold mb-1 text-dark">Welcome Back</h3>
                        <p class="text-muted small">Please enter your details to sign in</p>
                    </div>

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Email Address</label>
                            <input type="email" name="email" 
                                   class="form-control border-pink-focus shadow-none py-2" 
                                   placeholder="name@example.com" required autofocus>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <label class="form-label small fw-bold text-muted text-uppercase">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-pink small text-decoration-none">Forgot?</a>
                                @endif
                            </div>
                            <input type="password" name="password" 
                                   class="form-control border-pink-focus shadow-none py-2" 
                                   placeholder="••••••••" required>
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" name="remember" class="form-check-input shadow-none" id="remember">
                            <label class="form-check-label small text-muted" for="remember">Keep me signed in</label>
                        </div>

                        <button type="submit" class="btn btn-pink w-100 py-2 fw-bold shadow-sm mb-3">
                            Sign In
                        </button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <p class="text-muted small">New here? 
                            <a href="{{ route('register') }}" class="text-pink fw-bold text-decoration-none">Create account</a>
                        </p>
                    </div>
                </div>
            </div>
            
            <p class="text-center mt-4">
                <a href="/" class="text-muted small text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i> Back to Home
                </a>
            </p>
        </div>
    </div>
</div>
@endsection