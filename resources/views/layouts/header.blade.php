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
                    <a class="nav-link text-dark {{ request()->is('shop*') ? 'active-nav' : '' }}" href="{{ route('shop.index') }}">Shop</a>
                </li>

                @auth
                    <li class="nav-item dropdown ms-lg-3">
                        <a class="nav-link dropdown-toggle text-dark d-flex align-items-center py-0" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ auth()->user()->image_path ? asset('storage/' . auth()->user()->image_path) : asset('storage/placeholders/default-avatar.jpg') }}" 
                                 class="rounded-circle border border-white shadow-sm" 
                                 style="width: 35px; height: 35px; object-fit: cover;" 
                                 alt="Profile">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm mt-2" aria-labelledby="userDropdown">
                            <li>
                                <div class="dropdown-header d-lg-none">
                                    <div class="fw-bold text-dark">{{ auth()->user()->name }}</div>
                                    <small>{{ auth()->user()->email }}</small>
                                </div>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 {{ request()->is('profile*') ? 'text-pink fw-bold' : '' }}" href="{{ route('profile.show') }}">
                                    <i class="fa-solid fa-user-circle me-2 opacity-75"></i> My Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 {{ request()->is('my-orders*') ? 'text-pink fw-bold' : '' }}" href="{{ route('orders.my') }}">
                                    <i class="fa-solid fa-bag-shopping me-2 opacity-75"></i> My Orders
                                </a>
                            </li>
                            <li><hr class="dropdown-divider opacity-50"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="px-2">
                                    @csrf
                                    <button type="submit" class="btn btn-dark btn-sm w-100 fw-bold py-2 mt-1">
                                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
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
    /* Dropdown Hover Effects */
    .dropdown-item:active {
        background-color: #ec4899;
    }
    .dropdown-item:hover {
        background-color: rgba(236, 72, 153, 0.05);
        color: #ec4899;
    }
    
    /* Ensure the arrow looks okay on the pink nav */
    .dropdown-toggle::after {
        vertical-align: middle;
        color: rgba(0,0,0,0.5);
    }
</style>