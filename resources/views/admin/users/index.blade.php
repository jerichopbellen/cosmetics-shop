@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="mb-4">
        <h2 class="fw-bold mb-0 text-dark">
            <i class="fas fa-users me-2 text-pink"></i>Customer Management
        </h2>
        <p class="text-muted">View and manage your registered users and their activity.</p>
    </div>
    <div class="card shadow-sm border-0 overflow-hidden">
        <div style="height: 4px; background-color: #ec4899;"></div>
        
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
            <h5 class="mb-0 fw-bold text-dark">
                User Directory
            </h5>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                {!! $dataTable->table(['class' => 'table table-hover align-middle w-100']) !!}
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('products.index') }}" class="text-muted small text-decoration-none">
            <i class="fas fa-box me-1"></i> Back to Inventory
        </a>
    </div>
</div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush