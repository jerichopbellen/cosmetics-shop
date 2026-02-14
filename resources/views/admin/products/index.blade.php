@extends('layouts.base')

@section('body')
<div class="container py-5">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-dark">Product Inventory</h5>
            <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm px-3">
                <i class="fas fa-plus"></i> Add New Product
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