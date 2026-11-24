@extends('layouts.admin')

@section('title', 'Item Master - Catalog')
@section('page_title', 'Item Master')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Operations</a></li>
    <li class="breadcrumb-item active" aria-current="page">Item Master</li>
@endsection

@section('actions')
    <a href="{{ route('admin.items.create') }}" class="btn btn-primary-erp btn-sm d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i>
        <span>Register New Item</span>
    </a>
@endsection

@section('content')
    <!-- Inventory KPI Overview -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card-erp p-3 text-center">
                <div class="text-muted small fw-semibold text-uppercase">Total Catalog SKUs</div>
                <div class="h3 fw-bold my-1 text-primary">{{ number_format($metrics['total_skus']) }}</div>
                <div class="small text-muted">Active Master Items</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card-erp p-3 text-center">
                <div class="text-muted small fw-semibold text-uppercase">Total Quantity on Hand</div>
                <div class="h3 fw-bold my-1 text-dark">{{ number_format($metrics['total_stock'], 2) }}</div>
                <div class="small text-muted">Combined Across Warehouses</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card-erp p-3 text-center">
                <div class="text-muted small fw-semibold text-uppercase">Low Stock Alert</div>
                <div class="h3 fw-bold my-1 text-warning">{{ number_format($metrics['low_stock_count']) }}</div>
                <div class="small text-warning"><i class="bi bi-exclamation-triangle me-1"></i>At/Below Reorder Level</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card-erp p-3 text-center">
                <div class="text-muted small fw-semibold text-uppercase">Out of Stock</div>
                <div class="h3 fw-bold my-1 text-danger">{{ number_format($metrics['out_of_stock_count']) }}</div>
                <div class="small text-danger"><i class="bi bi-x-circle me-1"></i>Zero Inventory</div>
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

    <!-- Search & Filters Toolbar -->
    <div class="card-erp mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.items.index') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, SKU or category..." value="{{ $filters['search'] ?? '' }}">
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
                        <option value="">All Statuses</option>
                        <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="low_stock" {{ ($filters['status'] ?? '') === 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out_of_stock" {{ ($filters['status'] ?? '') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>
                <div class="col-12 col-md-3 d-flex justify-content-md-end gap-2">
                    <button type="submit" class="btn btn-outline-erp flex-grow-1 flex-md-grow-0 d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-filter"></i> Filter
                    </button>
                    @if(!empty($filters['search']) || !empty($filters['category']) || !empty($filters['status']))
                        <a href="{{ route('admin.items.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Items Master Table -->
    <div class="card-erp">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-box-seam text-primary"></i>
                <span class="fw-semibold">Master Items Registry</span>
            </div>
            <div class="small text-muted">Showing {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} of {{ $items->total() }} items</div>
        </div>
        <div class="table-responsive">
            <table class="table table-erp align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Item Details</th>
                        <th>Category</th>
                        <th>Unit</th>
                        <th class="text-end">Cost Price</th>
                        <th class="text-end">Unit Price</th>
                        <th class="text-end">Current Stock</th>
                        <th>Status</th>
                        <th class="text-end" style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
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
                                        <div class="fw-bold">{{ $item->name }}</div>
                                        <span class="font-monospace small text-primary">{{ $item->item_code }}</span>
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
                            <td class="text-end font-monospace text-muted">
                                ${{ number_format((float) $item->cost_price, 2) }}
                            </td>
                            <td class="text-end font-monospace fw-bold text-dark">
                                ${{ number_format((float) $item->unit_price, 2) }}
                            </td>
                            <td class="text-end">
                                <span class="fw-bold {{ (float) $item->current_stock <= (float) $item->reorder_level ? 'text-danger' : 'text-success' }}">
                                    {{ number_format((float) $item->current_stock, 2) }}
                                </span>
                                <div class="text-muted" style="font-size: 0.75rem;">Min: {{ number_format((float) $item->reorder_level, 2) }}</div>
                            </td>
                            <td>
                                @if($item->is_active)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Active</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.stock.history', $item) }}" class="btn btn-outline-erp" title="Stock Movement Ledger" data-bs-toggle="tooltip">
                                        <i class="bi bi-clock-history"></i>
                                    </a>
                                    <a href="{{ route('admin.stock.adjust.form', $item) }}" class="btn btn-outline-erp" title="Stock Adjustment" data-bs-toggle="tooltip">
                                        <i class="bi bi-plus-slash-minus"></i>
                                    </a>
                                    <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-outline-secondary" title="Edit Item" data-bs-toggle="tooltip">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.items.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Toggle active status for this item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-secondary" title="{{ $item->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="bi {{ $item->is_active ? 'bi-toggle-on text-success' : 'bi-toggle-off text-muted' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-box-seam display-6 d-block mb-3 text-secondary"></i>
                                    <h6 class="fw-bold">No items found</h6>
                                    <p class="small mb-3">No inventory master records match your search or filter criteria.</p>
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
