@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">
                <i class="fas fa-shopping-bag me-2 text-pink"></i>Orders
            </h2>
            <p class="text-muted">Manage customer purchases and fulfillment status.</p>
        </div>
        <div class="col-md-3">
            <select id="status-filter" class="form-select border-pink shadow-sm">
                <option value="">All Statuses</option>
                <option value="Pending">Pending</option>
                <option value="Packing">Packing</option>
                <option value="Shipped">Shipped</option>
                <option value="Delivered">Delivered</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </div>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div style="height: 4px; background-color: #ec4899;"></div>
        
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-dark">Recent Transactions</h5>
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
    
    <script type="module">
        $(function() {
            // Listen for changes on the dropdown
            $('#status-filter').on('change', function() {
                // Reload the table (it will automatically pick up the data from ajax data function)
                window.LaravelDataTables["order-table"].draw();
            });
        });
    </script>
@endpush
@endsection