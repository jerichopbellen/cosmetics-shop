@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Orders</h2>
            <p class="text-muted">Manage customer purchases and fulfillment status.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="table-responsive">
                {{ $dataTable->table(['class' => 'table table-hover align-middle w-100']) }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
    {{-- Using type="module" to align with Vite/Laravel 12 standards --}}
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
@endsection