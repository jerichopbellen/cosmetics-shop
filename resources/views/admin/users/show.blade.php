@extends('layouts.base')

@section('body')
<div class="container py-5">
    {{-- Breadcrumb Navigation --}}
    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-muted small">
            <i class="fa-solid fa-chevron-left me-1"></i> Back to User Directory
        </a>
    </div>

    {{-- Profile Header Card --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div class="d-flex align-items-center">
                    @if($user->image_path)
                        <img src="{{ asset('storage/' . $user->image_path) }}" alt="{{ $user->name }}" class="rounded-circle shadow-sm me-3" style="width: 60px; height: 60px; object-fit: cover;">
                    @else
                        <div class="text-white d-flex align-items-center justify-content-center fw-bold rounded-circle shadow-sm me-3" 
                             style="width: 60px; height: 60px; font-size: 1.5rem; background-color: #ec4899;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h2 class="fw-bold mb-0 text-dark">{{ $user->name }}</h2>
                        <p class="text-muted mb-0 small">
                            <i class="fa-regular fa-envelope me-1"></i> {{ $user->email }} 
                            <span class="mx-2 opacity-25">|</span>
                            <span style="color: #ec4899;">Joined {{ $user->created_at->format('M d, Y') }}</span>
                        </p>
                    </div>
                </div>

                <div class="mt-3 mt-md-0 d-flex gap-2 align-items-center">
                    @if($user->email_verified_at)
                        <span class="badge bg-info-subtle border border-info px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.75rem; color: #004085;">
                            <i class="fa-solid fa-envelope-circle-check me-1"></i> VERIFIED
                        </span>
                    @else
                        <span class="badge bg-warning-subtle border border-warning px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.75rem; color: #856404;">
                            <i class="fa-solid fa-hourglass-half me-1"></i> PENDING
                        </span>
                    @endif

                    @if($user->is_active)
                        <span class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.75rem;">
                            <i class="fa-solid fa-check-circle me-1"></i> ACTIVE
                        </span>
                    @else
                        <span class="badge bg-dark text-white border px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.75rem;">
                            <i class="fa-solid fa-ban me-1"></i> INACTIVE
                        </span>
                    @endif

                    <span class="badge {{ $user->role === 'admin' ? 'bg-pink text-white' : 'bg-light text-dark border' }} px-3 py-2 text-uppercase rounded-pill shadow-sm" 
                          style="font-size: 0.75rem; {{ $user->role === 'admin' ? 'background-color: #ec4899 !important;' : '' }}">
                        {{ strtoupper($user->role) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left: Order History --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm overflow-hidden mb-4">
                <div style="height: 4px; background-color: #ec4899;"></div>
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-history me-2" style="color: #ec4899;"></i>Order History</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3 border-0">Order ID</th>
                                    <th class="border-0 text-center">Items</th>
                                    <th class="border-0 text-center">Status</th>
                                    <th class="text-end pe-4 border-0">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->orders->sortByDesc('created_at') as $order)
                                    <tr>
                                        <td class="ps-4">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="fw-bold text-decoration-none" style="color: #ec4899;">
                                                #{{ $order->order_number }}
                                            </a>
                                            <div class="text-muted" style="font-size: 0.7rem;">{{ $order->created_at->format('M d, Y') }}</div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark border fw-normal">{{ $order->orderItems->count() }} items</span>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $statusClass = match($order->status) {
                                                    'Pending' => 'bg-warning text-dark',
                                                    'Delivered' => 'bg-success text-white',
                                                    'Cancelled' => 'bg-dark text-white',
                                                    default => 'bg-pink text-white'
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }} rounded-pill px-3" style="font-size: 0.7rem;">
                                                {{ strtoupper($order->status) }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4 fw-bold text-dark">
                                            {{-- Calculation from OrderItems since column was dropped --}}
                                            ₱{{ number_format($order->orderItems->sum(fn($i) => $i->price * $i->quantity), 2) }}
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

        {{-- Right: Metrics & Controls --}}
        <div class="col-lg-4">
            {{-- Metrics with Percentile Ranking --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4 text-center">
                    <p class="text-muted small text-uppercase mb-1 tracking-wider">Lifetime Spend</p>
                    <h3 class="fw-bold mb-2" style="color: #ec4899;">₱{{ number_format($userTotal, 2) }}</h3>
                    
                    @if($userRank !== 'No Rank')
                        <span class="badge rounded-pill px-3 py-2 shadow-sm" 
                              style="background-color: #fce7f3; color: #ec4899; font-size: 0.8rem; border: 1px solid #f9a8d4;">
                            <i class="fa-solid fa-trophy me-1"></i> {{ $userRank }} of Customers
                        </span>
                    @else
                        <span class="badge bg-light text-muted border px-3 py-2 rounded-pill" style="font-size: 0.8rem;">
                            No Ranking Available
                        </span>
                    @endif
                </div>
            </div>

            {{-- Role Control --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <label class="fw-bold text-dark small mb-3 text-uppercase tracking-wider">System Access</label>
                    <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="input-group">
                            <select name="role" class="form-select shadow-none" style="border-color: #ec4899;">
                                <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <button class="btn text-white" type="submit" style="background-color: #ec4899;" @if(auth()->id()===$user->id) disabled @endif>
                                <i class="fa-solid fa-save"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Status Control --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <label class="fw-bold text-dark small mb-3 text-uppercase tracking-wider">Account Status</label>
                    <form action="{{ route('admin.users.updateStatus', $user->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="input-group">
                            <select name="is_active" class="form-select shadow-none" style="border-color: {{ $user->is_active ? '#198754' : '#212529' }};">
                                <option value="1" {{ $user->is_active ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <button class="btn {{ $user->is_active ? 'btn-success' : 'btn-dark' }}" type="submit" @if(auth()->id()===$user->id) disabled @endif>
                                <i class="fa-solid fa-power-off"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection