<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">
            <i class="fa-solid fa-wand-sparkles me-2"></i>GLOW ADMIN
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav ms-auto">
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
                <li class="nav-item ms-lg-3">
                    <a class="btn btn-outline-light btn-sm mt-1" href="#">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>