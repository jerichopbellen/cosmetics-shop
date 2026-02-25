@extends('layouts.base')
@section('body')
<div class="container py-5">
    <h2 class="fw-bold mb-4">Customer Management</h2>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            {{ $dataTable->table(['class' => 'table table-hover align-middle w-100']) }}
        </div>
    </div>
</div>
@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
@endsection