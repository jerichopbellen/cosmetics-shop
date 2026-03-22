@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="mb-4 d-flex justify-content-between align-items-end">
        <div>
            <h2 class="fw-bold mb-0 text-dark">
                <i class="fas fa-users me-2" style="color: #ec4899;"></i>Customer Management
            </h2>
            <p class="text-muted">Monitor customer activity and total revenue from delivered orders.</p>
        </div>
        <div class="text-end">
            <span class="badge bg-light text-dark border p-2">
                <i class="fas fa-info-circle me-1"></i> Lifetime spent reflects Delivered orders only.
            </span>
        </div>
    </div>

    <div class="card shadow-sm border-0 overflow-hidden">
        <div style="height: 4px; background-color: #ec4899;"></div>
        
        <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0 fw-bold text-dark">User Directory</h5>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                {!! $dataTable->table(['class' => 'table table-hover align-middle w-100']) !!}
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-between">
        <a href="{{ route('products.index') }}" class="btn btn-link text-muted text-decoration-none p-0">
            <i class="fas fa-box me-1"></i> Back to Inventory
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-link text-muted text-decoration-none p-0">
            <i class="fas fa-shopping-cart me-1"></i> View All Orders
        </a>
    </div>
</div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush