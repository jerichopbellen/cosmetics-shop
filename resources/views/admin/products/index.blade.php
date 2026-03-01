@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="card shadow-sm border-0 overflow-hidden">
        <div style="height: 4px; background-color: #ec4899;"></div>
        
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
            <h5 class="mb-0 fw-bold text-dark">Product Inventory</h5>
            <a href="{{ route('products.create') }}" class="btn btn-pink btn-sm px-3 shadow-sm">
                <i class="fas fa-plus me-1"></i> Add New Product
            </a>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-hover align-middle w-100']) !!}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush