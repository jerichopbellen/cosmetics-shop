@extends('layouts.base')

@section('body')
<div class="container py-5 mt-4">
    {{-- Header Section --}}
    <div class="mb-4 d-flex justify-content-between align-items-end">
        <div>
            <h2 class="fw-bold mb-0 text-dark">
                <i class="fas fa-star me-2 text-pink"></i>Review Management
            </h2>
            <p class="text-muted mb-0">Monitor and manage customer feedback and product ratings.</p>
        </div>
        {{-- Helpful Links --}}
        <div class="d-none d-md-block">
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary border-0 text-muted">
                <i class="fas fa-users me-1"></i> Users
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary border-0 text-muted">
                <i class="fas fa-box me-1"></i> Products
            </a>
        </div>
    </div>

    {{-- Main Reviews Card --}}
    <div class="card shadow-sm border-0 overflow-hidden">
        <div style="height: 4px; background-color: #ec4899;"></div>
        
        <div class="card-header bg-white py-3 border-0">
            <div class="row align-items-center g-3">
                <div class="col-md-6">
                    <h5 class="mb-0 fw-bold text-dark">Customer Feedback</h5>
                </div>
                
                <div class="col-md-6">
                    <div class="d-flex justify-content-md-end gap-2">
                        <button onclick="window.LaravelDataTables['reviews-table'].draw()" class="btn btn-outline-pink btn-sm px-3">
                            <i class="fas fa-sync-alt me-1"></i> Refresh Table
                        </button>
                    </div>
                </div>
            </div>
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