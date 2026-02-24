@extends('layouts.base')

@section('body')
<div class="container py-5">
    <h2 class="fw-bold mb-4">My Orders</h2>

    <div class="mb-5">
        <h4 class="fw-bold mb-3">Current Orders</h4>
        @forelse($currentOrders as $order)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <span class="text-muted small">Order #</span>
                            <div class="fw-bold">{{ $order->order_number }}</div>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted small">Placed on</span>
                            <div>{{ $order->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="col-md-2 text-center">
                            @php
                                $statusClass = match($order->status) {
                                    'Pending' => 'bg-warning text-dark',
                                    'Packing' => 'bg-info text-white',
                                    'Shipped' => 'bg-primary',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ $order->status }}</span>
                        </div>
                        <div class="col-md-2 fw-bold text-end">
                            ${{ number_format($order->total_amount, 2) }}
                        </div>
                        <div class="col-md-2 text-end">
                            <button class="btn btn-outline-dark btn-sm" data-bs-toggle="collapse" data-bs-target="#details-{{ $order->id }}">
                                Details
                            </button>
                        </div>
                    </div>

                    <div class="collapse mt-3 pt-3 border-top" id="details-{{ $order->id }}">
                        @foreach($order->orderItems as $item)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    @php
                                        // Get the image from the shade first, then the product gallery, then placeholder
                                        $itemImage = $item->shade->image_path 
                                                    ?? ($item->shade->product->images->first()?->image_path ?? 'placeholders/product.jpg');
                                    @endphp
                                    
                                    <img src="{{ asset('storage/' . $itemImage) }}" 
                                        class="rounded border me-3" 
                                        style="width: 50px; height: 50px; object-fit: cover;"
                                        alt="Product Image">
                                        
                                    <div class="small">
                                        <div class="fw-bold text-dark">{{ $item->shade->product->name }}</div>
                                        <div class="text-muted">
                                            {{ $item->shade->shade_name }} <span class="mx-1">|</span> Qty: {{ $item->quantity }}
                                        </div>
                                    </div>
                                </div>
                                <div class="small fw-bold text-dark">${{ number_format($item->price * $item->quantity, 2) }}</div>
                            </div>
                        @endforeach
                        @if($order->tracking_id)
                            <div class="mt-3 p-2 bg-light rounded small">
                                <strong>Tracking ID:</strong> {{ $order->tracking_id }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">You have no active orders at the moment.</p>
        @endforelse
    </div>

    <div>
        <h4 class="fw-bold mb-3 text-muted">Order History</h4>
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Order #</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orderHistory as $history)
                            <tr>
                                <td class="ps-4 fw-bold text-muted">{{ $history->order_number }}</td>
                                <td>{{ $history->created_at->format('M d, Y') }}</td>
                                <td>${{ number_format($history->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge {{ $history->status == 'Delivered' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $history->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No past orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection