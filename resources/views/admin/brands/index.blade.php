@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="mb-4">
        <h2 class="fw-bold mb-0 text-dark">
            <i class="fas fa-tag me-2 text-pink"></i>Brand Management
        </h2>
        <p class="text-muted">View and manage your brand inventory and details.</p>
    </div>
    <div class="card shadow-sm border-0 overflow-hidden">
        <div style="height: 4px; background-color: #ec4899;"></div>
        
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
            <h5 class="mb-0 text-dark fw-bold">
                All Brands
            </h5>
            <a href="{{ route('brands.create') }}" class="btn btn-pink btn-sm px-3 shadow-sm fw-bold">
            <i class="fa-solid fa-plus me-1"></i> Add New Brand
            </a>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-hover align-middle w-100']) !!}
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('products.index') }}" class="text-muted small text-decoration-none">
            <i class="fas fa-box me-1"></i> Switch to Products
        </a>
        <a href="{{ route('categories.index') }}" class="text-muted small text-decoration-none">
            <i class="fas fa-layer-group me-1"></i> Switch to Categories
        </a>
    </div>

</div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush