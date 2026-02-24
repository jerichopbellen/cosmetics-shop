@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('admin.orders.index') }}" class="text-decoration-none text-muted small">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Orders
        </a>
        <div class="d-flex justify-content-between align-items-center mt-2">
            <h2 class="fw-bold">Order #{{ $order->order_number }}</h2>
            <span class="badge {{ $order->status == 'Pending' ? 'bg-warning text-dark' : 'bg-primary' }} px-3 py-2">
                {{ $order->status }}
            </span>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Items Ordered</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th class="text-end pe-4">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/' . ($item->shade->image_path ?? 'placeholder.jpg')) }}" 
                                             class="rounded border me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                        <div>
                                            <div class="fw-bold text-dark">{{ $item->shade->product->name }}</div>
                                            <small class="text-muted">Shade: {{ $item->shade->shade_name }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="text-end pe-4 fw-bold">${{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end ps-4 fw-bold">Grand Total:</td>
                                <td class="text-end pe-4 fw-bold text-primary fs-5">${{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Customer Info</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Name:</strong> {{ $order->user->name }}</p>
                    <p class="mb-3"><strong>Email:</strong> {{ $order->user->email }}</p>
                    <hr>
                    <p class="text-muted small">Joined: {{ $order->user->created_at->format('M Y') }}</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm bg-dark text-white">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-white">Update Fulfillment</h5>
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label small text-uppercase opacity-75">Order Status</label>
                            <select name="status" class="form-select bg-secondary border-0 text-white shadow-none">
                                @foreach(['Pending', 'Packing', 'Shipped', 'Delivered', 'Cancelled'] as $status)
                                    <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small text-uppercase opacity-75">Tracking Number</label>
                            <input type="text" name="tracking_id" class="form-control bg-secondary border-0 text-white shadow-none" 
                                   value="{{ $order->tracking_id }}" placeholder="e.g. DHL-12345">
                        </div>

                        <button type="submit" class="btn btn-light w-100 fw-bold py-2">
                            Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection