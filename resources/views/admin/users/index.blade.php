@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="mb-4">
        <h2 class="fw-bold mb-0 text-dark">
            <i class="fas fa-users me-2 text-pink"></i>Customer Management
        </h2>
        <p class="text-muted">View and manage your registered users and their activity.</p>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div style="height: 4px; background-color: #ec4899;"></div>
        
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-dark">User Directory</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive p-4">
                {{ $dataTable->table(['class' => 'table table-hover align-middle w-100']) }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    
@endpush
@endsection