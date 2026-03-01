<nav class="navbar navbar-expand-lg navbar-dark bg-light shadow-sm" style="background-color: #ffc0cb !important;">
    <div class="container">
        <a class="navbar-brand fw-bold text-dark" href="{{ route('shop.index') }}">
            <i class="fa-solid fa-wand-sparkles me-2"></i>GLOW
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#glowNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="glowNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link text-dark {{ request()->is('shop*') ? 'active-nav' : '' }}" 
                       href="{{ route('shop.index') }}">Shop</a>
                </li>

                @if(auth()->check() && auth()->user()->role === 'admin')
                    <li class="nav-item ms-lg-2 border-start ps-lg-3 border-secondary" style="border-color: rgba(0,0,0,0.1) !important;">
                        <small class="text-dark d-none d-lg-block fw-bold opacity-75" style="font-size: 0.7rem;">ADMIN</small>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark {{ request()->is('admin/brands*') ? 'active-nav' : '' }}" 
                           href="{{ route('brands.index') }}">Brands</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark {{ request()->is('admin/categories*') ? 'active-nav' : '' }}" 
                           href="{{ route('categories.index') }}">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark {{ request()->is('admin/products*') ? 'active-nav' : '' }}" 
                           href="{{ route('products.index') }}">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark {{ request()->is('admin/orders*') ? 'active-nav' : '' }}" 
                           href="{{ route('admin.orders.index') }}">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark {{ request()->is('admin/users*') ? 'active-nav' : '' }}" 
                           href="{{ route('admin.users.index') }}">Users</a>
                    </li>
                @endif

                <li class="nav-item ms-lg-3">
                    <a class="nav-link text-dark position-relative py-lg-0 {{ request()->is('cart*') ? 'active-nav' : '' }}" href="{{ route('cart.index') }}">
                        <i class="fa-solid fa-cart-shopping fa-lg"></i>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                {{ count(session('cart')) }}
                                <span class="visually-hidden">items in cart</span>
                            </span>
                        @endif
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-dark position-relative py-lg-0 {{ request()->is('my-orders*') ? 'active-nav' : '' }}" href="{{ route('orders.my') }}">
                        <i class="fa-solid fa-list fa-lg"></i>
                    </a>
                </li>

                @auth
                    <li class="nav-item ms-lg-3">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-dark btn-sm fw-bold">Logout</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-dark btn-sm fw-bold {{ request()->is('login') ? 'active' : '' }}" href="{{ route('login') }}">Login</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<style>
    /* Styling for the active state */
    .nav-link.active-nav {
        font-weight: 800 !important;
        position: relative;
    }

    /* Adds a sleek underline to the active desktop link */
    @media (min-width: 992px) {
        .nav-link.active-nav::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 10%;
            width: 80%;
            height: 2px;
            background-color: #000;
            border-radius: 2px;
        }
    }
    
    /* Hover effect for better interactivity */
    .nav-link:hover {
        opacity: 0.7;
    }
</style>