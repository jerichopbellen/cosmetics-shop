@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-dark fw-bold">Brand List</h5>
            <a href="{{ route('brands.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus me-1"></i> Add Brand
            </a>
        </div>
        <div class="card-body">
            {!! $dataTable->table(['class' => 'table table-hover w-100']) !!}
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush