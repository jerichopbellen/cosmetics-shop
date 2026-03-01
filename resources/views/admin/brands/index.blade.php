@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="card shadow-sm border-0 overflow-hidden">
        <div style="height: 4px; background-color: #ec4899;"></div>

        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-dark fw-bold">
                <i class="fas fa-tags me-2 text-pink"></i>Brand Management
            </h5>
            <a href="{{ route('brands.create') }}" class="btn btn-pink btn-sm fw-bold shadow-sm">
                <i class="fa-solid fa-plus me-1"></i> Add New Brand
            </a>
        </div>

        <div class="card-body p-0"> <div class="table-responsive p-3">
                {!! $dataTable->table(['class' => 'table table-hover align-middle w-100']) !!}
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('products.index') }}" class="text-muted small text-decoration-none">
            <i class="fas fa-box me-1"></i> Switch to Products
        </a>
    </div>
</div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush