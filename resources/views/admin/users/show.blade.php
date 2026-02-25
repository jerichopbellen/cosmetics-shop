@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-muted small">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Users
        </a>
        <div class="d-flex justify-content-between align-items-end mt-2">
            <div>
                <h2 class="fw-bold mb-0">{{ $user->name }}</h2>
                <p class="text-muted mb-0">{{ $user->email }} • Customer since {{ $user->created_at->format('M Y') }}</p>
            </div>
            <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-dark' }} px-3 py-2 text-uppercase">
                {{ $user->role }}
            </span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Order History</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Order #</th>
                                    <th>Products</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->orders as $order)
                                    <tr>
                                        <td class="ps-4">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="fw-bold text-decoration-none text-primary">
                                                {{ $order->order_number }}
                                            </a>
                                            <div class="text-muted small">{{ $order->created_at->format('M d, Y') }}</div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-2 py-2">
                                                @foreach($order->orderItems as $item)
                                                    <div class="d-flex align-items-center">
                                                        @php
                                                            $image = $item->shade->product->images->first()->image_path ?? 'placeholders/product.jpg';
                                                        @endphp
                                                        <img src="{{ asset('storage/' . $image) }}" 
                                                             class="rounded border me-2" 
                                                             style="width: 35px; height: 35px; object-fit: cover;">
                                                        <span class="small">
                                                            {{ $item->shade->product->name }} 
                                                            <span class="text-muted">({{ $item->quantity }})</span>
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = match($order->status) {
                                                    'Pending' => 'bg-warning text-dark',
                                                    'Delivered' => 'bg-success',
                                                    'Cancelled' => 'bg-danger',
                                                    default => 'bg-primary'
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }}">{{ $order->status }}</span>
                                        </td>
                                        <td class="text-end pe-4 fw-bold">
                                            ${{ number_format($order->total_amount, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            No orders found for this user.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Update Permissions</h5>
                    <p class="text-muted small mb-4">Change this user's role. Admins have access to the dashboard and management tools.</p>
                    
                    <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold">System Role</label>
                            <select name="role" class="form-select shadow-none">
                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User (Customer)</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin (Full Access)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 py-2 fw-bold" 
                                @if(auth()->id() === $user->id) disabled @endif
                                onclick="return confirm('Confirm role change for {{ $user->name }}?')">
                            SAVE CHANGES
                        </button>
                        
                        @if(auth()->id() === $user->id)
                            <small class="text-danger d-block mt-2 text-center italic">
                                You cannot change your own role.
                            </small>
                        @endif
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-uppercase small text-muted mb-3">Account Overview</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small">Total Lifetime Spend:</span>
                        <span class="fw-bold text-dark">${{ number_format($user->orders->where('status', 'Delivered')->sum('total_amount'), 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small">Completed Orders:</span>
                        <span class="fw-bold text-dark">{{ $user->orders->where('status', 'Delivered')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection