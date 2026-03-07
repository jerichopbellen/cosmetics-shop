<nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background-color: #ffc0cb !important;">
    <div class="container">
        <a class="navbar-brand fw-bold text-dark" href="{{ route('shop.index') }}">
            <i class="fa-solid fa-wand-sparkles me-2"></i>GLOW
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#glowNavbar">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <div class="collapse navbar-collapse" id="glowNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                
                <li class="nav-item">
                    <a class="nav-link text-dark {{ request()->is('shop*') ? 'active-nav' : '' }}" href="{{ route('shop.index') }}">Shop</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-dark position-relative" href="{{ route('cart.index') }}">
                        <i class="fa-solid fa-cart-shopping"></i>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-pink border border-white" style="font-size: 0.6rem;">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>
                </li>

                @auth
                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link text-dark fw-bold {{ request()->is('admin*') ? 'text-pink' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="fa-solid fa-lock me-1 small"></i> Admin
                            </a>
                        </li>
                    @endif

                    <li class="nav-item dropdown ms-lg-3">
                        <a class="nav-link dropdown-toggle text-dark d-flex align-items-center py-0" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <img src="{{ auth()->user()->image_path ? asset('storage/' . auth()->user()->image_path) : asset('storage/placeholders/default-avatar.jpg') }}" 
                                 class="rounded-circle border border-white shadow-sm" 
                                 style="width: 35px; height: 35px; object-fit: cover;">
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm mt-2" style="z-index: 1050;">
                            <li>
                                <div class="dropdown-header">
                                    <div class="fw-bold text-dark">{{ auth()->user()->name }}</div>
                                    <small class="text-muted">{{ auth()->user()->email }}</small>
                                </div>
                            </li>

                            @if(auth()->user()->role === 'admin')
                                <li><hr class="dropdown-divider opacity-50"></li>
                                <li class="dropdown-header text-uppercase small fw-bold text-pink" style="font-size: 0.65rem; letter-spacing: 1px;">Management</li>
                                
                                <li><a class="dropdown-item py-2" href="{{ route('products.index') }}"><i class="fa-solid fa-box me-2 opacity-75"></i> Products</a></li>
                                <li><a class="dropdown-item py-2" href="{{ route('categories.index') }}"><i class="fa-solid fa-tags me-2 opacity-75"></i> Categories</a></li>
                                <li><a class="dropdown-item py-2" href="{{ route('brands.index') }}"><i class="fa-solid fa-copyright me-2 opacity-75"></i> Brands</a></li>
                                <li><a class="dropdown-item py-2" href="{{ route('admin.orders.index') }}"><i class="fa-solid fa-receipt me-2 opacity-75"></i> Orders</a></li>
                                <li><a class="dropdown-item py-2" href="{{ route('admin.users.index') }}"><i class="fa-solid fa-user-shield me-2 opacity-75"></i> Users</a></li>
                            @endif

                            <li><hr class="dropdown-divider opacity-50"></li>
                            <li class="dropdown-header text-uppercase small fw-bold text-muted" style="font-size: 0.65rem; letter-spacing: 1px;">My Account</li>
                            
                            <li><a class="dropdown-item py-2 {{ request()->is('my-profile') ? 'active-pink' : '' }}" href="{{ route('profile.show') }}"><i class="fa-solid fa-circle-user me-2 opacity-75"></i> Profile</a></li>
                            <li><a class="dropdown-item py-2 {{ request()->is('my-orders') ? 'active-pink' : '' }}" href="{{ route('orders.my') }}"><i class="fa-solid fa-bag-shopping me-2 opacity-75"></i> My Orders</a></li>
                            
                            <li><hr class="dropdown-divider opacity-50"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="px-2">
                                    @csrf
                                    <button type="submit" class="btn btn-dark btn-sm w-100 fw-bold py-2 mt-1 shadow-sm">
                                        <i class="fa-solid fa-power-off me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-dark btn-sm fw-bold px-3 rounded-pill" href="{{ route('login') }}">Login</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<style>
    .text-pink { color: #ec4899 !important; }
    .bg-pink { background-color: #ec4899 !important; }
    .active-nav { border-bottom: 2px solid #ec4899; font-weight: bold; }
    .dropdown-menu { min-width: 220px; border-radius: 12px; padding: 0.5rem; }
    .dropdown-item { border-radius: 8px; transition: 0.2s; }
    .dropdown-item:hover { background-color: rgba(236, 72, 153, 0.05); color: #ec4899; }
    .active-pink { background-color: #ec4899 !important; color: white !important; }
    .active-pink i { color: white !important; }
</style>