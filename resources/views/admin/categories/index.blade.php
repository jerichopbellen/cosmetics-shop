@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Category Management</h5>
            <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">Add Category</a>
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