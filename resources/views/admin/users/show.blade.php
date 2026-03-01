@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-muted small">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Users
        </a>
        <div class="d-flex justify-content-between align-items-end mt-2">
            <div>
                <h2 class="fw-bold mb-0 text-dark">{{ $user->name }}</h2>
                <p class="text-muted mb-0">{{ $user->email }} • <span class="text-pink">Customer since {{ $user->created_at->format('M Y') }}</span></p>
            </div>
            <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-pink' }} px-3 py-2 text-uppercase rounded-pill shadow-sm">
                {{ $user->role }}
            </span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div style="height: 4px; background-color: #ec4899;"></div>
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-history me-2 text-pink"></i>Order History</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 border-0">Order #</th>
                                    <th class="border-0">Products</th>
                                    <th class="border-0">Status</th>
                                    <th class="text-end pe-4 border-0">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->orders as $order)
                                    <tr>
                                        <td class="ps-4">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="fw-bold text-decoration-none text-pink">
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
                                                             class="rounded border-pink me-2" 
                                                             style="width: 35px; height: 35px; object-fit: cover;">
                                                        <span class="small text-dark">
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
                                                    default => 'bg-pink'
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }} rounded-pill">{{ $order->status }}</span>
                                        </td>
                                        <td class="text-end pe-4 fw-bold text-dark">
                                            ${{ number_format($order->total_amount, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="fas fa-box-open d-block mb-2 fs-3 opacity-25"></i>
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
            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                <div style="height: 4px; background-color: #ec4899;"></div>
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 text-dark">Update Permissions</h5>
                    <p class="text-muted small mb-4">Admins have access to the dashboard and management tools.</p>
                    
                    <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">System Role</label>
                            <select name="role" class="form-select border-pink shadow-none">
                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User (Customer)</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin (Full Access)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-pink w-100 py-2 fw-bold shadow-sm" 
                                @if(auth()->id() === $user->id) disabled @endif
                                onclick="return confirm('Confirm role change for {{ $user->name }}?')">
                            <i class="fas fa-user-shield me-1"></i> SAVE CHANGES
                        </button>
                        
                        @if(auth()->id() === $user->id)
                            <small class="text-danger d-block mt-3 text-center fst-italic">
                                <i class="fas fa-exclamation-triangle me-1"></i> You cannot change your own role.
                            </small>
                        @endif
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm bg-white overflow-hidden">
                <div style="height: 4px; background-color: #343a40;"></div> <div class="card-body p-4">
                    <h6 class="fw-bold text-uppercase small text-muted mb-3 tracking-wider">Account Overview</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Lifetime Spend:</span>
                        <span class="fw-bold text-pink">${{ number_format($user->orders->where('status', 'Delivered')->sum('total_amount'), 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Completed Orders:</span>
                        <span class="fw-bold text-dark">{{ $user->orders->where('status', 'Delivered')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection