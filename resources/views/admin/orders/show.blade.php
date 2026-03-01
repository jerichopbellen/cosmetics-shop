@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('admin.orders.index') }}" class="text-decoration-none text-muted small">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Orders
        </a>
        <div class="d-flex justify-content-between align-items-center mt-2">
            <h2 class="fw-bold">Order <span class="text-pink">#{{ $order->order_number }}</span></h2>
            <span class="badge {{ $order->status == 'Pending' ? 'bg-warning text-dark' : 'bg-pink' }} px-3 py-2 rounded-pill shadow-sm">
                {{ $order->status }}
            </span>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                <div style="height: 4px; background-color: #ec4899;"></div>
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-shopping-basket me-2 text-pink"></i>Items Ordered</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 border-0">Product</th>
                                    <th class="border-0">Price</th>
                                    <th class="border-0">Qty</th>
                                    <th class="text-end pe-4 border-0">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . ($item->shade->image_path ?? 'placeholder.jpg')) }}" 
                                                 class="rounded border-pink me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                            <div>
                                                <div class="fw-bold text-dark">{{ $item->shade->product->name }}</div>
                                                <span class="badge bg-light text-pink border border-pink small">{{ $item->shade->shade_name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-end pe-4 fw-bold">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-end ps-4 fw-bold py-3">Grand Total:</td>
                                    <td class="text-end pe-4 fw-bold text-pink fs-5 py-3">${{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                <div style="height: 4px; background-color: #ec4899;"></div>
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-user-circle me-2 text-pink"></i>Customer Info</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.users.show', $order->user_id) }}" class="text-decoration-none d-flex align-items-center mb-3 group">
                        <div class="bg-pink text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 45px; height: 45px;">
                            <span class="fw-bold">{{ strtoupper(substr($order->user->name, 0, 1)) }}</span>
                        </div>
                        <div>
                            <p class="mb-0 fw-bold text-dark border-bottom-hover">{{ $order->user->name }}</p>
                            <p class="mb-0 text-muted small">{{ $order->user->email }}</p>
                        </div>
                    </a>
                    <hr class="text-muted opacity-25">
                    <p class="text-muted small mb-0"><i class="fas fa-history me-1"></i> View customer's full order history</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm overflow-hidden">
                <div style="height: 4px; background-color: #ec4899;"></div>
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-truck-loading me-2 text-pink"></i>Update Fulfillment</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="form-label small text-uppercase fw-bold text-muted">Order Status</label>
                            <select name="status" class="form-select border-pink shadow-none py-2">
                                @foreach(['Pending', 'Packing', 'Shipped', 'Delivered', 'Cancelled'] as $status)
                                    <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-pink w-100 fw-bold py-2 shadow-sm">
                            <i class="fas fa-check-circle me-1"></i> Update Order Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection