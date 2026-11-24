<aside class="admin-sidebar" id="adminSidebar">
    <!-- Sidebar Header / Brand -->
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <div class="brand-icon">
                <i class="bi bi-grid-1x2-fill"></i>
            </div>
            <div class="brand-text">
                <span class="brand-title">APEX ERP</span>
                <span class="brand-sub">Enterprise Suite</span>
            </div>
        </a>
    </div>

    <!-- Sidebar Navigation -->
    <div class="sidebar-content">
        <!-- Main Navigation -->
        <div class="nav-section-title">Main</div>
        
        <div class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="nav-link-main">
                    <i class="bi bi-speedometer2 nav-icon"></i>
                    <span class="nav-text">Dashboard</span>
                </span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-0.5 nav-badge" style="font-size: 0.65rem;">Live</span>
            </a>
        </div>

        <!-- Operations Navigation -->
        <div class="nav-section-title">Operations</div>

        <div class="nav-item">
            <a href="{{ route('admin.items.index') }}" class="nav-link-item {{ request()->routeIs('admin.items*') ? 'active' : '' }}">
                <span class="nav-link-main">
                    <i class="bi bi-box-seam nav-icon"></i>
                    <span class="nav-text">Item Master</span>
                </span>
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.stock.index') }}" class="nav-link-item {{ request()->routeIs('admin.stock*') ? 'active' : '' }}">
                <span class="nav-link-main">
                    <i class="bi bi-boxes nav-icon"></i>
                    <span class="nav-text">Stock Ledger</span>
                </span>
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.orders') }}" class="nav-link-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                <span class="nav-link-main">
                    <i class="bi bi-cart3 nav-icon"></i>
                    <span class="nav-text">Sales & Orders</span>
                </span>
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0.5 nav-badge" style="font-size: 0.65rem;">+24</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.customers') }}" class="nav-link-item {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
                <span class="nav-link-main">
                    <i class="bi bi-people nav-icon"></i>
                    <span class="nav-text">Customers & CRM</span>
                </span>
            </a>
        </div>

        <div class="nav-item">
            <a href="#procurementMenu" data-bs-toggle="collapse" class="nav-link-item {{ request()->is('admin/procurement*') ? 'active' : '' }}" role="button" aria-expanded="false">
                <span class="nav-link-main">
                    <i class="bi bi-truck nav-icon"></i>
                    <span class="nav-text">Procurement</span>
                </span>
                <i class="bi bi-chevron-down nav-arrow" style="font-size: 0.75rem;"></i>
            </a>
            <div class="collapse" id="procurementMenu">
                <ul class="sidebar-submenu">
                    <li class="submenu-item">
                        <a href="#" class="submenu-link">Purchase Orders</a>
                    </li>
                    <li class="submenu-item">
                        <a href="#" class="submenu-link">Suppliers & Vendors</a>
                    </li>
                    <li class="submenu-item">
                        <a href="#" class="submenu-link">Goods Received (GRN)</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Finance & Accounting -->
        <div class="nav-section-title">Finance</div>

        <div class="nav-item">
            <a href="#financeMenu" data-bs-toggle="collapse" class="nav-link-item" role="button" aria-expanded="false">
                <span class="nav-link-main">
                    <i class="bi bi-wallet2 nav-icon"></i>
                    <span class="nav-text">Billing & Invoices</span>
                </span>
                <i class="bi bi-chevron-down nav-arrow" style="font-size: 0.75rem;"></i>
            </a>
            <div class="collapse" id="financeMenu">
                <ul class="sidebar-submenu">
                    <li class="submenu-item">
                        <a href="#" class="submenu-link">Invoices</a>
                    </li>
                    <li class="submenu-item">
                        <a href="#" class="submenu-link">Payments Received</a>
                    </li>
                    <li class="submenu-item">
                        <a href="#" class="submenu-link">Expenses & Petty Cash</a>
                    </li>
                    <li class="submenu-item">
                        <a href="#" class="submenu-link">Tax Reports (GST/VAT)</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- HR & Workforce -->
        <div class="nav-section-title">Human Resources</div>

        <div class="nav-item">
            <a href="#" class="nav-link-item">
                <span class="nav-link-main">
                    <i class="bi bi-person-badge nav-icon"></i>
                    <span class="nav-text">Staff & Payroll</span>
                </span>
            </a>
        </div>

        <!-- System Administration -->
        <div class="nav-section-title">Administration</div>

        <div class="nav-item">
            <a href="{{ route('admin.users') }}" class="nav-link-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <span class="nav-link-main">
                    <i class="bi bi-people nav-icon"></i>
                    <span class="nav-text">Users Management</span>
                </span>
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.settings') }}" class="nav-link-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                <span class="nav-link-main">
                    <i class="bi bi-sliders nav-icon"></i>
                    <span class="nav-text">System Settings</span>
                </span>
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.roles') }}" class="nav-link-item {{ request()->routeIs('admin.roles*') ? 'active' : '' }}">
                <span class="nav-link-main">
                    <i class="bi bi-shield-check nav-icon"></i>
                    <span class="nav-text">Roles & Permissions</span>
                </span>
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.activity-log') }}" class="nav-link-item {{ request()->routeIs('admin.activity-log*') ? 'active' : '' }}">
                <span class="nav-link-main">
                    <i class="bi bi-journal-text nav-icon"></i>
                    <span class="nav-text">Audit & Activity Logs</span>
                </span>
            </a>
        </div>
    </div>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="user-mini-card">
            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80" alt="Admin User" class="user-avatar">
            <div class="user-info">
                <div class="user-name">Sarah Jenkins</div>
                <div class="user-role">Super Administrator</div>
            </div>
            <div class="d-flex align-items-center gap-1 ms-auto">
                <a href="{{ route('admin.settings') }}" class="text-white-50 hover-text-white p-1" title="Quick Settings">
                    <i class="bi bi-gear"></i>
                </a>
                <a href="{{ route('logout') }}" class="text-danger-emphasis p-1" title="Sign Out">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</aside>
