@extends('layouts.base')

@section('body')
<div class="container py-5 mt-4">
    <div class="mb-4 d-flex justify-content-between align-items-end">
        <div>
            <h2 class="fw-bold mb-0 text-dark">
                <i class="fas fa-trash-restore me-2 text-pink"></i>Archived Products
            </h2>
            <p class="text-muted mb-0 small">Restore products to make them visible in the shop again.</p>
        </div>
        <div>
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary border-0 text-muted">
                <i class="fas fa-chevron-left me-1"></i> Back to Inventory
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0 overflow-hidden">
        <div style="height: 4px; background-color: #6c757d;"></div> {{-- Grey line for trash --}}
        
        <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0 fw-bold text-dark">Deleted Items</h5>
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