<header id="sb-header" class="d-flex align-items-center justify-content-between px-3">
    <!-- Left Section: Toggle Button & Logo -->
    <div class="d-flex align-items-center gap-3">
        <button id="sb-sidebar-toggle" class="btn btn-link text-dark p-0" aria-label="Toggle Sidebar">
            <i class="bi bi-list fs-4"></i>
        </button>
        <div class="d-flex align-items-center gap-2 d-md-none">
            <i class="bi bi-gift-fill text-primary fs-4"></i>
            <span class="fw-bold mb-0 text-dark">Sembako</span>
        </div>
    </div>

    <!-- Right Section: User Profile Dropdown -->
    <div class="dropdown">
        <button class="btn btn-link text-decoration-none dropdown-toggle text-dark d-flex align-items-center gap-2 p-0" 
                type="button" 
                id="profileDropdown" 
                data-bs-toggle="dropdown" 
                aria-expanded="false">
            <div class="text-end d-none d-sm-block me-1">
                <div class="fw-semibold lh-1" style="font-size: 0.9rem;">{{ Auth::user()->nama }}</div>
                <span class="user-role-badge mt-1 d-inline-block">{{ str_replace('_', ' ', Auth::user()->role) }}</span>
            </div>
            <!-- Avatar placeholder -->
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" 
                 style="width: 36px; height: 36px; font-size: 0.95rem;">
                {{ strtoupper(substr(Auth::user()->nama, 0, 2)) }}
            </div>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-light mt-2" aria-labelledby="profileDropdown">
            <li class="dropdown-header d-sm-none">
                <div class="fw-semibold text-dark">{{ Auth::user()->nama }}</div>
                <div class="text-muted" style="font-size: 0.8rem;">{{ str_replace('_', ' ', Auth::user()->role) }}</div>
            </li>
            <li class="dropdown-header text-muted" style="font-size: 0.8rem;">{{ Auth::user()->email }}</li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('profile.edit') }}">
                    <i class="bi bi-person text-secondary"></i>
                    <span>Profile</span>
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Log Out</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</header>
