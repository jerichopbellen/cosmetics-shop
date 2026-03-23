@extends('layouts.base')

@section('body')
<div class="container py-5 mt-4">
    {{-- Header Section --}}
    <div class="mb-4 d-flex justify-content-between align-items-end">
        <div>
            <h2 class="fw-bold mb-0 text-dark">
                <i class="fas fa-box me-2 text-pink"></i>Product Management
            </h2>
            <p class="text-muted mb-0 small">Manage inventory, archive seasonal items, or restore previously deleted products.</p>
        </div>
        
        {{-- Helpful Quick Links --}}
        <div class="d-none d-md-block">
            <a href="{{ route('brands.index') }}" class="btn btn-sm btn-outline-secondary border-0 text-muted">
                <i class="fas fa-tag me-1"></i> Brands
            </a>
            <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-secondary border-0 text-muted">
                <i class="fas fa-layer-group me-1"></i> Categories
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Main Inventory Card --}}
    <div class="card shadow-sm border-0 overflow-hidden">
        {{-- Visual Accent Line --}}
        <div style="height: 4px; background-color: #ec4899;"></div>
        
        <div class="card-header bg-white py-3 border-0">
            <div class="row align-items-center g-3">
                <div class="col-md-4">
                    <h5 class="mb-0 fw-bold text-dark">Product Inventory</h5>
                </div>
                
                <div class="col-md-8">
                    <div class="d-flex flex-column flex-md-row justify-content-md-end gap-2">
                        
                        {{-- Bulk Import Form --}}
                        <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2 align-items-center">
                            @csrf
                            <div class="input-group input-group-sm">
                                <label class="input-group-text bg-light border-pink-soft text-muted" for="importFile">
                                    <i class="fas fa-file-excel"></i>
                                </label>
                                <input type="file" name="file" class="form-control form-control-sm border-pink-soft shadow-none" id="importFile" required style="max-width: 200px;">
                                <button type="submit" class="btn btn-outline-pink btn-sm">Import</button>
                            </div>
                        </form>

                        <div class="vr d-none d-md-block mx-1"></div>

                        {{-- Add New Product --}}
                        <a href="{{ route('products.create') }}" class="btn btn-pink btn-sm px-3 shadow-sm">
                            <i class="fas fa-plus me-1"></i> Add Product
                        </a>
                            <a href="{{ route('products.trash') }}" class="btn btn-outline-secondary btn-sm px-3 shadow-sm">
                            <i class="fas fa-trash-alt me-1"></i> Trash
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                {{-- DataTables will automatically inject the "Archive" or "Restore" buttons here based on your ProductDataTable logic --}}
                {!! $dataTable->table(['class' => 'table table-hover align-middle w-100']) !!}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- Renders the JavaScript configuration for the table --}}
    {!! $dataTable->scripts() !!}
@endpush