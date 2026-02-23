@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm p-4">
                <h3 class="fw-bold mb-3 text-center">Create Account</h3>
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 py-2">Join GLOW</button>
                </form>
                <p class="text-center mt-3 small">Already have an account? <a href="{{ route('login') }}">Login</a></p>
            </div>
        </div>
    </div>
</div>
@endsection