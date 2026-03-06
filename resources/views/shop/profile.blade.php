@extends('layouts.base')

@section('body')
<div class="container py-5 mt-4">
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm text-center p-4 h-100">
                
                <div class="position-relative d-inline-block mx-auto mb-3">
                    <img id="avatar-preview" 
                         src="{{ auth()->user()->image_path ? asset('storage/' . auth()->user()->image_path) : asset('storage/placeholders/default-avatar.jpg') }}" 
                         class="rounded-circle border border-pink p-1 shadow-sm" 
                         style="width: 140px; height: 140px; object-fit: cover;">
                    
                    <label for="avatar-input" class="position-absolute bottom-0 end-0 bg-pink text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                           style="width: 38px; height: 38px; cursor: pointer; border: 3px solid white;">
                        <i class="fas fa-camera"></i>
                    </label>
                </div>

                @if(auth()->user()->image_path)
                    <form action="{{ route('profile.avatar.delete', auth()->user()) }}" method="POST" onsubmit="return confirm('Remove profile photo?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-pink btn-sm px-3 rounded-pill fw-bold mb-3">
                            <i class="fas fa-trash-alt me-1"></i> Remove Photo
                        </button>
                    </form>
                @endif

                <h4 class="fw-bold mb-0 text-dark">{{ auth()->user()->name }}</h4>
                <p class="text-muted small mb-4">{{ auth()->user()->email }}</p>
                
                <hr class="opacity-50">
                
                <div class="row mt-3">
                    <div class="col-6 border-end">
                        <h5 class="fw-bold mb-0 text-dark">{{ auth()->user()->orders->count() ?? 0 }}</h5>
                        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.6rem;">Orders</small>
                    </div>
                    <div class="col-6">
                        <h5 class="fw-bold mb-0 text-dark">{{ auth()->user()->created_at->format('M Y') }}</h5>
                        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.6rem;">Joined</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div style="height: 5px; background-color: #ec4899;"></div>
                <div class="card-body p-4 p-md-5">
                    <h3 class="fw-bold mb-4 text-dark">Account Settings</h3>

                    <form action="{{ route('profile.update', auth()->user()) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <input type="file" name="avatar" id="avatar-input" class="d-none" accept="image/*" onchange="previewProfileImage(this)">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Full Name</label>
                                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" 
                                       class="form-control border-pink-focus shadow-none py-2 @error('name') is-invalid @enderror" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" 
                                       class="form-control border-pink-focus shadow-none py-2 @error('email') is-invalid @enderror" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12"><hr class="my-4 opacity-25"></div>
                            
                            <h5 class="fw-bold mb-3 text-dark">Change Password</h5>
                            <p class="text-muted small mb-4">Leave blank if you do not want to change your password.</p>

                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">New Password</label>
                                <input type="password" name="password" class="form-control border-pink-focus shadow-none py-2 @error('password') is-invalid @enderror" placeholder="••••••••">
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control border-pink-focus shadow-none py-2" placeholder="••••••••">
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-pink px-5 py-2 fw-bold shadow-sm">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewProfileImage(input) {
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
    /* Custom Pink Theme Styles */
    .border-pink { border-color: #ec4899 !important; }
    .bg-pink { background-color: #ec4899 !important; }
    .text-pink { color: #ec4899 !important; }
    
    .btn-pink { background-color: #ec4899; color: white; border: none; transition: 0.3s; }
    .btn-pink:hover { background-color: #be185d; color: white; transform: translateY(-1px); }
    
    /* Cohesive Outline Button for Removal */
    .btn-outline-pink { 
        border: 1px solid #ec4899; 
        color: #ec4899; 
        background: transparent;
        transition: 0.2s;
    }
    .btn-outline-pink:hover { 
        background-color: #fff1f2; 
        color: #be185d; 
        border-color: #be185d;
    }

    .border-pink-focus:focus {
        border-color: #ec4899;
        box-shadow: 0 0 0 0.25rem rgba(236, 72, 153, 0.1);
    }
    .is-invalid { border-color: #dc3545 !important; }
</style>
@endsection