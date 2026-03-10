@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="mb-4">
        <h2 class="fw-bold mb-0 text-dark">
            <i class="fas fa-layer-group me-2 text-pink"></i>Category Management
        </h2>
        <p class="text-muted">View and manage your product categories and organization.</p>
    </div>
    <div class="card shadow-sm border-0 overflow-hidden">
        <div style="height: 4px; background-color: #ec4899;"></div>

        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
            <h5 class="mb-0 text-dark fw-bold">
                All Categories
            </h5>
            <a href="{{ route('categories.create') }}" class="btn btn-pink btn-sm px-3 shadow-sm fw-bold">
                <i class="fa-solid fa-plus me-1"></i> Add New Category
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive p-3">
                {!! $dataTable->table(['class' => 'table table-hover align-middle w-100']) !!}
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('brands.index') }}" class="text-muted small text-decoration-none me-3">
            <i class="fas fa-tag me-1"></i> Manage Brands
        </a>
        <a href="{{ route('products.index') }}" class="text-muted small text-decoration-none">
            <i class="fas fa-box me-1"></i> Manage Products
        </a>
    </div>
</div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush