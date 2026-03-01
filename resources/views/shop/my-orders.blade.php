@extends('layouts.base')

@section('body')

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">My Orders</h2>
        <a href="{{ route('shop.index') }}" class="btn btn-outline-pink btn-sm">
            <i class="fa-solid fa-plus me-2"></i>New Order
        </a>
    </div>

    <ul class="nav nav-tabs border-bottom border-2" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active px-4 py-3" id="active-tab" data-bs-toggle="tab" data-bs-target="#active-content" type="button" role="tab">
                <i class="fa-solid fa-box-open me-2"></i>Active Shipments
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link px-4 py-3" id="history-tab" data-bs-toggle="tab" data-bs-target="#history-content" type="button" role="tab">
                <i class="fa-solid fa-clock-rotate-left me-2"></i>Order History
            </button>
        </li>
    </ul>

    <div class="tab-content mt-4">
        <div class="tab-pane fade show active" id="active-content" role="tabpanel">
            @forelse($currentOrders as $order)
                <div class="card border-0 shadow-sm mb-3 overflow-hidden">
                    <div style="height: 4px; background-color: #ec4899;"></div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <span class="text-muted small text-uppercase fw-semibold">Order #</span>
                                <div class="fw-bold text-pink fs-5">{{ $order->order_number }}</div>
                            </div>
                            <div class="col-md-2">
                                <span class="text-muted small text-uppercase fw-semibold">Date</span>
                                <div class="text-dark">{{ $order->created_at->format('M d, Y') }}</div>
                            </div>
                            <div class="col-md-3 text-center">
                                @php
                                    $statusColor = match($order->status) {
                                        'Pending' => 'bg-warning text-dark',
                                        'Packing' => 'btn-pink text-white',
                                        'Shipped' => 'bg-info text-white',
                                        default => 'bg-secondary text-white'
                                    };
                                @endphp
                                <span class="badge {{ $statusColor }} px-3 py-2 rounded-pill shadow-sm">
                                    {{ $order->status }}
                                </span>
                            </div>
                            <div class="col-md-2 fw-bold text-end fs-5 text-dark">
                                ${{ number_format($order->total_amount, 2) }}
                            </div>
                            <div class="col-md-2 text-end">
                                <button class="btn btn-outline-pink btn-sm fw-bold px-3" data-bs-toggle="collapse" data-bs-target="#details-{{ $order->id }}">
                                    DETAILS <i class="fa-solid fa-chevron-down ms-1 small"></i>
                                </button>
                            </div>
                        </div>

                        <div class="collapse mt-3 pt-3 border-top" id="details-{{ $order->id }}">
                            <div class="row g-4 text-start">
                                <div class="col-lg-7">
                                    <h6 class="fw-bold text-uppercase small text-muted mb-3">Items Ordered</h6>
                                    @foreach($order->orderItems as $item)
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="d-flex align-items-center text-start">
                                                <img src="{{ asset('storage/' . ($item->shade->image_path ?? 'placeholders/product.jpg')) }}" class="rounded border border-pink p-1 me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                <div class="small text-start">
                                                    <div class="fw-bold text-dark">{{ $item->shade->product->name }}</div>
                                                    <div class="text-muted small">
                                                        <span class="text-pink fw-semibold">{{ $item->shade->shade_name }}</span> | Qty: {{ $item->quantity }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="small fw-bold text-dark">${{ number_format($item->price * $item->quantity, 2) }}</div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-lg-5">
                                    <div class="bg-light p-3 rounded h-100 border-start border-pink border-4">
                                        <h6 class="fw-bold text-uppercase small text-muted mb-2">Shipping Address</h6>
                                        <div class="small text-dark lh-sm">
                                            <strong>{{ $order->first_name }} {{ $order->last_name }}</strong><br>
                                            {{ $order->address }}<br>
                                            {{ $order->city }}, {{ $order->state }} {{ $order->zip_code }}<br>
                                            <span class="text-muted small mt-2 d-block">
                                                <i class="fa-solid fa-phone me-1"></i> {{ $order->phone }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center py-5 text-muted">No active shipments.</p>
            @endforelse
        </div>

        <div class="tab-pane fade" id="history-content" role="tabpanel">
            @forelse($orderHistory as $history)
                <div class="card border-0 shadow-sm mb-3 overflow-hidden">
                    <div style="height: 4px; background-color: #ec4899;"></div> <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <span class="text-muted small text-uppercase fw-semibold">Order #</span>
                                <div class="fw-bold text-pink fs-5">{{ $history->order_number }}</div>
                            </div>
                            <div class="col-md-2">
                                <span class="text-muted small text-uppercase fw-semibold">Date</span>
                                <div class="text-dark">{{ $history->created_at->format('M d, Y') }}</div>
                            </div>
                            <div class="col-md-3 text-center">
                                @php
                                    $historyStatusColor = match($history->status) {
                                        'Delivered' => 'bg-success text-white',
                                        'Cancelled' => 'bg-danger text-white',
                                        default => 'bg-secondary text-white'
                                    };
                                @endphp
                                <span class="badge {{ $historyStatusColor }} px-3 py-2 rounded-pill shadow-sm">
                                    {{ $history->status }}
                                </span>
                            </div>
                            <div class="col-md-2 fw-bold text-end fs-5 text-dark">
                                ${{ number_format($history->total_amount, 2) }}
                            </div>
                            <div class="col-md-2 text-end">
                                <button class="btn btn-outline-pink btn-sm fw-bold px-3" data-bs-toggle="collapse" data-bs-target="#history-details-{{ $history->id }}">
                                    DETAILS <i class="fa-solid fa-chevron-down ms-1 small"></i>
                                </button>
                            </div>
                        </div>

                        <div class="collapse mt-3 pt-3 border-top" id="history-details-{{ $history->id }}">
                            <div class="row g-4 text-start">
                                <div class="col-lg-7">
                                    <h6 class="fw-bold text-uppercase small text-muted mb-3">Items Purchased</h6>
                                    @foreach($history->orderItems as $item)
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('storage/' . ($item->shade->image_path ?? 'placeholders/product.jpg')) }}" class="rounded border border-pink p-1 me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                <div class="small">
                                                    <div class="fw-bold text-dark">{{ $item->shade->product->name }}</div>
                                                    <div class="text-muted small">
                                                        <span class="text-pink fw-semibold">{{ $item->shade->shade_name }}</span> | Qty: {{ $item->quantity }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="small fw-bold text-dark">${{ number_format($item->price * $item->quantity, 2) }}</div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-lg-5">
                                    <div class="bg-light p-3 rounded h-100 border-start border-pink border-4">
                                        <h6 class="fw-bold text-uppercase small text-muted mb-2">Shipping Address</h6>
                                        <div class="small text-dark lh-sm">
                                            <strong>{{ $history->first_name }} {{ $history->last_name }}</strong><br>
                                            {{ $history->address }}<br>
                                            {{ $history->city }}, {{ $history->state }} {{ $history->zip_code }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center py-5 text-muted">No order history.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection