<header class="admin-navbar">
    <!-- Left Section: Toggle & Search -->
    <div class="navbar-left">
        <!-- Sidebar Toggle Hamburger -->
        <button type="button" class="btn-icon" id="sidebarToggleBtn" aria-label="Toggle Navigation">
            <i class="bi bi-list fs-4"></i>
        </button>

        <!-- Global Search Bar -->
        <div class="nav-search-bar">
            <i class="bi bi-search search-icon"></i>
            <input type="text" class="form-control" id="globalSearchInput" placeholder="Search orders, SKU, clients..." autocomplete="off">
            <span class="search-shortcut">Ctrl+K</span>
        </div>
    </div>

    <!-- Right Section: Actions, Notifications, Theme Switcher & Profile -->
    <div class="navbar-right">
        <!-- Quick Action Dropdown -->
        <div class="dropdown d-none d-md-block">
            <button class="btn btn-primary-erp btn-sm dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-plus-lg"></i>
                <span>Quick Action</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-erp dropdown-menu-end">
                <li><a class="dropdown-item dropdown-item-erp" href="{{ route('admin.orders') }}"><i class="bi bi-cart-plus text-primary"></i> Create New Order</a></li>
                <li><a class="dropdown-item dropdown-item-erp" href="{{ route('admin.inventory') }}"><i class="bi bi-box-arrow-in-down text-success"></i> Add Stock / Product</a></li>
                <li><a class="dropdown-item dropdown-item-erp" href="{{ route('admin.customers') }}"><i class="bi bi-person-plus text-info"></i> Register Customer</a></li>
                <li><hr class="dropdown-divider border-secondary-subtle"></li>
                <li><a class="dropdown-item dropdown-item-erp" href="#"><i class="bi bi-file-earmark-pdf text-danger"></i> Generate Tax Invoice</a></li>
            </ul>
        </div>

        <!-- Light/Dark Mode Switcher -->
        <button type="button" class="btn-icon" id="themeToggleBtn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Toggle Theme (Dark/Light)">
            <i class="bi bi-sun-fill text-warning" id="themeIcon"></i>
        </button>

        <!-- Notifications Dropdown -->
        <div class="dropdown">
            <button type="button" class="btn-icon" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications">
                <i class="bi bi-bell"></i>
                <span class="badge-dot"></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-erp p-0 shadow-lg" style="width: 320px;">
                <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-bold">Notifications</h6>
                    <span class="badge bg-danger-subtle text-danger rounded-pill">3 Unread</span>
                </div>
                <div class="list-group list-group-flush" style="max-height: 280px; overflow-y: auto;">
                    <a href="#" class="list-group-item list-group-item-action p-3 border-bottom bg-light-subtle">
                        <div class="d-flex align-items-start gap-2">
                            <span class="p-2 rounded-circle bg-warning-subtle text-warning">
                                <i class="bi bi-exclamation-triangle"></i>
                            </span>
                            <div class="flex-grow-1">
                                <div class="small fw-bold">Low Stock Warning</div>
                                <div class="text-muted small">Industrial Bearing #B-902 is down to 4 units.</div>
                                <div class="text-muted" style="font-size: 0.7rem;">10 minutes ago</div>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action p-3 border-bottom">
                        <div class="d-flex align-items-start gap-2">
                            <span class="p-2 rounded-circle bg-success-subtle text-success">
                                <i class="bi bi-currency-dollar"></i>
                            </span>
                            <div class="flex-grow-1">
                                <div class="small fw-bold">Payment Received</div>
                                <div class="text-muted small">Invoice #INV-2026-894 paid in full ($12,450.00).</div>
                                <div class="text-muted" style="font-size: 0.7rem;">1 hour ago</div>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action p-3">
                        <div class="d-flex align-items-start gap-2">
                            <span class="p-2 rounded-circle bg-primary-subtle text-primary">
                                <i class="bi bi-truck"></i>
                            </span>
                            <div class="flex-grow-1">
                                <div class="small fw-bold">Shipment Dispatched</div>
                                <div class="text-muted small">Order #ORD-5012 out for delivery via Freightliner.</div>
                                <div class="text-muted" style="font-size: 0.7rem;">3 hours ago</div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="p-2 text-center border-top">
                    <a href="#" class="small fw-semibold text-primary">View All System Alerts</a>
                </div>
            </div>
        </div>

        <!-- User Profile Dropdown -->
        <div class="dropdown">
            <button class="btn p-0 border-0 d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80" alt="Sarah Jenkins" class="user-avatar" style="width: 38px; height: 38px;">
                <div class="d-none d-xl-flex flex-column text-start" style="line-height: 1.2;">
                    <span class="small fw-bold">Sarah Jenkins</span>
                    <span class="text-muted" style="font-size: 0.7rem;">Administrator</span>
                </div>
                <i class="bi bi-chevron-down d-none d-xl-inline text-muted" style="font-size: 0.7rem;"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-erp dropdown-menu-end">
                <li class="px-3 py-2 border-bottom">
                    <div class="fw-bold">Sarah Jenkins</div>
                    <div class="text-muted small">sarah.jenkins@company.com</div>
                </li>
                <li><a class="dropdown-item dropdown-item-erp mt-1" href="{{ route('admin.settings') }}"><i class="bi bi-person"></i> My Profile</a></li>
                <li><a class="dropdown-item dropdown-item-erp" href="{{ route('admin.settings') }}"><i class="bi bi-sliders"></i> System Settings</a></li>
                <li><a class="dropdown-item dropdown-item-erp" href="#"><i class="bi bi-shield-lock"></i> Security & 2FA</a></li>
                <li><hr class="dropdown-divider border-secondary-subtle"></li>
                <li><a class="dropdown-item dropdown-item-erp text-danger" href="{{ route('logout') }}"><i class="bi bi-box-arrow-right"></i> Sign Out</a></li>
            </ul>
        </div>
    </div>
</header>
