@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">
                <i class="fas fa-shopping-bag me-2 text-pink"></i>Order Management
            </h2>
            <p class="text-muted">Manage customer purchases and fulfillment status.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0 overflow-hidden">
        <div style="height: 4px; background-color: #ec4899;"></div>
        
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
            <h5 class="mb-0 fw-bold text-dark">All Orders</h5>
            <div style="width: 180px;">
                <select id="status-filter" class="form-select form-select-sm border-pink shadow-sm">
                    <option value="">All Statuses</option>
                    <option value="Pending">Pending</option>
                    <option value="Packing">Packing</option>
                    <option value="Shipped">Shipped</option>
                    <option value="Delivered">Delivered</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                {!! $dataTable->table(['class' => 'table table-hover align-middle w-100']) !!}
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('products.index') }}" class="text-muted small text-decoration-none">
            <i class="fas fa-box me-1"></i> Back to Inventory
        </a>
    </div>
</div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
    <script type="module">
        $(function() {
            $('#status-filter').on('change', function() {
                $('#order-table').DataTable().draw();
            });
        });
    </script>
@endpush