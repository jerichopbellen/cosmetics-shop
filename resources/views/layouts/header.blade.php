<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('shop.index') }}">
            <i class="fa-solid fa-wand-sparkles me-2"></i>GLOW
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#glowNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="glowNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('shop*') ? 'active' : '' }}" 
                       href="{{ route('shop.index') }}">Shop</a>
                </li>

                @if(auth()->check() && auth()->user()->role === 'admin')
                    <li class="nav-item ms-lg-2 border-start ps-lg-3 border-secondary">
                        <small class="text-secondary d-none d-lg-block" style="font-size: 0.7rem;">ADMIN</small>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/brands*') ? 'active' : '' }}" 
                           href="{{ route('brands.index') }}">Brands</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}" 
                           href="{{ route('categories.index') }}">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}" 
                           href="{{ route('products.index') }}">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}" 
                           href="{{ route('admin.orders.index') }}">Orders</a>
                    </li>
                @endif

                <li class="nav-item ms-lg-3">
                    <a class="nav-link position-relative py-lg-0" href="{{ route('cart.index') }}">
                        <i class="fa-solid fa-cart-shopping fa-lg"></i>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                {{ count(session('cart')) }}
                                <span class="visually-hidden">items in cart</span>
                            </span>
                        @endif
                    </a>
                </li>

                @auth
                    <li class="nav-item ms-lg-3">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-light btn-sm" href="{{ route('login') }}">Login</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>