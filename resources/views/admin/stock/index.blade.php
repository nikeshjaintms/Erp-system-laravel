@extends('layouts.admin')

@section('title', 'Stock Ledger & Balances')
@section('page_title', 'Stock Ledger & Balances')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Operations</a></li>
    <li class="breadcrumb-item active" aria-current="page">Inventory &amp; Stock Management</li>
@endsection

@section('actions')
    <a href="{{ route('admin.items.create') }}" class="btn btn-outline-erp btn-sm d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i>
        <span>Register New Item</span>
    </a>
    <a href="{{ route('admin.items.index') }}" class="btn btn-primary-erp btn-sm d-flex align-items-center gap-2">
        <i class="bi bi-box-seam"></i>
        <span>Item Master</span>
    </a>
@endsection

@section('content')
    <!-- Top Inventory KPI Metrics -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card-erp p-3 text-center">
                <div class="text-muted small fw-semibold text-uppercase">Total Catalog SKUs</div>
                <div class="h3 fw-bold my-1 text-primary">{{ number_format($metrics['total_skus']) }}</div>
                <div class="small text-muted">Stock Tracked Products</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card-erp p-3 text-center">
                <div class="text-muted small fw-semibold text-uppercase">Total Units in Hand</div>
                <div class="h3 fw-bold my-1 text-dark">{{ number_format($metrics['total_stock'], 2) }}</div>
                <div class="small text-muted">Active Warehouse Inventory</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card-erp p-3 text-center">
                <div class="text-muted small fw-semibold text-uppercase">Low Stock Alert</div>
                <div class="h3 fw-bold my-1 text-warning">{{ number_format($metrics['low_stock_count']) }}</div>
                <div class="small text-warning"><i class="bi bi-exclamation-triangle me-1"></i>Reorder Recommended</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card-erp p-3 text-center">
                <div class="text-muted small fw-semibold text-uppercase">Out of Stock</div>
                <div class="h3 fw-bold my-1 text-danger">{{ number_format($metrics['out_of_stock_count']) }}</div>
                <div class="small text-danger"><i class="bi bi-x-circle me-1"></i>Immediate Action Needed</div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Inventory Filter & Search Bar -->
    <div class="card-erp mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.stock.index') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Filter by product name or SKU..." value="{{ $filters['search'] ?? '' }}">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ ($filters['category'] ?? '') === $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Stock Levels</option>
                        <option value="low_stock" {{ ($filters['status'] ?? '') === 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out_of_stock" {{ ($filters['status'] ?? '') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>Active Items</option>
                    </select>
                </div>
                <div class="col-12 col-md-3 d-flex justify-content-md-end gap-2">
                    <button type="submit" class="btn btn-outline-erp flex-grow-1 flex-md-grow-0 d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-filter"></i> Filters
                    </button>
                    @if(!empty($filters['search']) || !empty($filters['category']) || !empty($filters['status']))
                        <a href="{{ route('admin.stock.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Stock Balances Table -->
    <div class="card-erp">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-boxes text-primary"></i>
                <span class="fw-semibold">Real-Time Stock Balances</span>
            </div>
            <div class="small text-muted">Showing {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} of {{ $items->total() }} items</div>
        </div>
        <div class="table-responsive">
            <table class="table table-erp align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Product Info</th>
                        <th>Category</th>
                        <th>Unit</th>
                        <th style="min-width: 170px;">Stock Level vs Threshold</th>
                        <th class="text-end">Current Balance</th>
                        <th class="text-end">Inventory Value</th>
                        <th>Status</th>
                        <th class="text-end" style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        @php
                            $current = (float) $item->current_stock;
                            $reorder = (float) $item->reorder_level;
                            $ratio = $reorder > 0 ? min(100, max(5, ($current / ($reorder * 2)) * 100)) : 100;
                            $progressClass = $current <= 0 ? 'bg-danger' : ($current <= $reorder ? 'bg-warning' : 'bg-success');
                        @endphp
                        <tr>
                            <td>
                                <span class="font-monospace text-muted small">{{ $item->id }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="stat-icon primary p-2 rounded" style="width: 38px; height: 38px; font-size: 1rem; flex-shrink: 0;">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.stock.history', $item) }}" class="fw-bold text-decoration-none text-dark">
                                            {{ $item->name }}
                                        </a>
                                        <div class="font-monospace small text-primary">{{ $item->item_code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($item->category)
                                    <span class="badge bg-light text-dark border">{{ $item->category }}</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-dark border border-secondary-subtle font-monospace">{{ $item->unit }}</span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="fw-semibold">{{ number_format($current, 2) }} {{ $item->unit }}</span>
                                    <span class="text-muted">Min: {{ number_format($reorder, 2) }}</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar {{ $progressClass }}" role="progressbar" style="width: {{ $ratio }}%;"></div>
                                </div>
                            </td>
                            <td class="text-end font-monospace fw-bold {{ $current <= $reorder ? 'text-danger' : 'text-dark' }}">
                                {{ number_format($current, 2) }}
                            </td>
                            <td class="text-end font-monospace text-muted">
                                ${{ number_format($current * (float) $item->cost_price, 2) }}
                            </td>
                            <td>
                                @if($item->stock_status === 'In Stock')
                                    <span class="badge-erp success"><span class="badge-erp-dot"></span> In Stock</span>
                                @elseif($item->stock_status === 'Low Stock')
                                    <span class="badge-erp warning"><span class="badge-erp-dot"></span> Low Stock</span>
                                @elseif($item->stock_status === 'Critical')
                                    <span class="badge-erp danger"><span class="badge-erp-dot"></span> Critical</span>
                                @else
                                    <span class="badge bg-secondary text-white px-2 py-1 rounded-pill small">Out of Stock</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.stock.adjust.form', $item) }}" class="btn btn-outline-erp" title="Quick Stock Adjustment" data-bs-toggle="tooltip">
                                        <i class="bi bi-plus-slash-minus"></i>
                                    </a>
                                    <a href="{{ route('admin.stock.history', $item) }}" class="btn btn-outline-secondary" title="View Stock Movement Ledger" data-bs-toggle="tooltip">
                                        <i class="bi bi-clock-history"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-boxes display-6 d-block mb-3 text-secondary"></i>
                                    <h6 class="fw-bold">No stock balances to display</h6>
                                    <p class="small mb-3">Add items to your catalog to track balances and inventory movements.</p>
                                    <a href="{{ route('admin.items.create') }}" class="btn btn-primary-erp btn-sm">
                                        <i class="bi bi-plus-lg me-1"></i> Register New Item
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($items->hasPages())
            <div class="card-footer bg-transparent border-top p-3 d-flex justify-content-between align-items-center">
                <div class="small text-muted">
                    Page {{ $items->currentPage() }} of {{ $items->lastPage() }}
                </div>
                {{ $items->links() }}
            </div>
        @endif
    </div>
@endsection
