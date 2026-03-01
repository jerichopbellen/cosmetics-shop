@extends('layouts.base')

@section('body')
<div class="container py-5 mt-4">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div style="height: 5px; background-color: #ec4899;"></div>
                
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold mb-1 text-dark">Create Account</h3>
                        <p class="text-muted small">Join the GLOW community today</p>
                    </div>

                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Full Name</label>
                            <input type="text" name="name" 
                                   class="form-control border-pink-focus shadow-none py-2" 
                                   placeholder="John Doe" value="{{ old('name') }}" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Email Address</label>
                            <input type="email" name="email" 
                                   class="form-control border-pink-focus shadow-none py-2" 
                                   placeholder="name@example.com" value="{{ old('email') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Password</label>
                                <input type="password" name="password" 
                                       class="form-control border-pink-focus shadow-none py-2" 
                                       placeholder="••••••••" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Confirm</label>
                                <input type="password" name="password_confirmation" 
                                       class="form-control border-pink-focus shadow-none py-2" 
                                       placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="mb-4 small text-muted">
                        </div>

                        <button type="submit" class="btn btn-pink w-100 py-2 fw-bold shadow-sm mb-3">
                            Join GLOW
                        </button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <p class="text-muted small">Already have an account? 
                            <a href="{{ route('login') }}" class="text-pink fw-bold text-decoration-none">Login</a>
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