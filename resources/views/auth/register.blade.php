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

                    <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                <img id="avatar-preview" src="{{ asset('storage/placeholders/default-avatar.jpg') }}" 
                                     class="rounded-circle border border-pink p-1 shadow-sm" 
                                     style="width: 100px; height: 100px; object-fit: cover;">
                                
                                <label for="avatar-input" class="position-absolute bottom-0 end-0 bg-pink text-white rounded-circle d-flex align-items-center justify-content-center shadow" 
                                       style="width: 32px; height: 32px; cursor: pointer; border: 2px solid white;">
                                    <i class="fas fa-camera small"></i>
                                </label>
                                <input type="file" name="avatar" id="avatar-input" class="d-none" accept="image/*" onchange="previewImage(this)">
                            </div>
                            <div class="mt-2 small text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Profile Photo</div>
                            @error('avatar')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Full Name</label>
                            <input type="text" name="name" 
                                   class="form-control border-pink-focus shadow-none py-2" 
                                   placeholder="John Doe" value="{{ old('name') }}" autofocus>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Email Address</label>
                            <input name="email" 
                                   class="form-control border-pink-focus shadow-none py-2" 
                                   placeholder="name@example.com" value="{{ old('email') }}" autofocus>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Password</label>
                                <input type="password" name="password" 
                                       class="form-control border-pink-focus shadow-none py-2" 
                                       placeholder="••••••••" >
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Confirm</label>
                                <input type="password" name="password_confirmation" 
                                       class="form-control border-pink-focus shadow-none py-2" 
                                       placeholder="••••••••" >
                            </div>
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

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<style>
    .border-pink { border-color: #ec4899 !important; }
    .bg-pink { background-color: #ec4899 !important; }
    .border-pink-focus:focus {
        border-color: #ec4899;
        box-shadow: 0 0 0 0.25rem rgba(236, 72, 153, 0.1);
    }
</style>
@endsection