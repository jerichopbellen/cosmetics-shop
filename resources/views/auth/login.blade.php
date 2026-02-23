@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4">
                <h3 class="fw-bold mb-3 text-center">Login</h3>
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 py-2">Sign In</button>
                </form>
                <p class="text-center mt-3 small">New here? <a href="{{ route('register') }}">Create account</a></p>
            </div>
        </div>
    </div>
</div>
@endsection