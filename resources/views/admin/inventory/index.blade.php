@extends('layouts.admin')

@section('title', 'Inventory & Stock Management')
@section('page_title', 'Inventory & Stock Management')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Operations</a></li>
    <li class="breadcrumb-item active" aria-current="page">Inventory</li>
@endsection

@section('actions')
    <button type="button" class="btn btn-outline-erp btn-sm d-flex align-items-center gap-2">
        <i class="bi bi-file-earmark-spreadsheet"></i>
        <span>Export CSV</span>
    </button>
    <button type="button" class="btn btn-primary-erp btn-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addProductModal">
        <i class="bi bi-plus-lg"></i>
        <span>Add New Product</span>
    </button>
@endsection

@section('content')
    <!-- Top Inventory KPI Metrics -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card-erp p-3 text-center">
                <div class="text-muted small fw-semibold text-uppercase">Total Catalog SKUs</div>
                <div class="h3 fw-bold my-1 text-primary">1,480</div>
                <div class="small text-success"><i class="bi bi-arrow-up"></i> +12 new this month</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card-erp p-3 text-center">
                <div class="text-muted small fw-semibold text-uppercase">Total Quantity in Hand</div>
                <div class="h3 fw-bold my-1">84,920</div>
                <div class="small text-muted">Across 4 Warehouses</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card-erp p-3 text-center">
                <div class="text-muted small fw-semibold text-uppercase">Low Stock Alert</div>
                <div class="h3 fw-bold my-1 text-warning">8</div>
                <div class="small text-warning">Requires Procurement</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card-erp p-3 text-center">
                <div class="text-muted small fw-semibold text-uppercase">Out of Stock</div>
                <div class="h3 fw-bold my-1 text-danger">3</div>
                <div class="small text-danger">Immediate Action Needed</div>
            </div>
        </div>
    </div>

    <!-- Inventory Filter & Search Bar -->
    <div class="card-erp mb-4">
        <div class="card-body p-3">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control border-start-0" placeholder="Filter by product name, SKU or barcode...">
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <select class="form-select">
                        <option value="">All Categories</option>
                        <option value="Mechanical Parts">Mechanical Parts</option>
                        <option value="Electronics & Motors">Electronics & Motors</option>
                        <option value="Hydraulics">Hydraulics</option>
                        <option value="Pneumatics">Pneumatics</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select class="form-select">
                        <option value="">All Warehouses</option>
                        <option value="WH-A">Main Warehouse (WH-A)</option>
                        <option value="WH-B">East Coast Hub (WH-B)</option>
                        <option value="WH-C">South Depot (WH-C)</option>
                    </select>
                </div>
                <div class="col-12 col-md-3 d-flex justify-content-md-end gap-2">
                    <button class="btn btn-outline-erp flex-grow-1 flex-md-grow-0 d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-filter"></i> Filters
                    </button>
                    <button class="btn btn-outline-secondary d-flex align-items-center gap-1">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory Data Table -->
    <div class="card-erp">
        <div class="card-header">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-boxes text-primary"></i>
                <span>Stock Master Inventory</span>
            </div>
            <div class="small text-muted">Showing {{ count($products) }} items</div>
        </div>
        <div class="table-responsive">
            <table class="table table-erp align-middle">
                <thead>
                    <tr>
                        <th style="width: 40px;">
                            <input class="form-check-input" type="checkbox">
                        </th>
                        <th>Product Info</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Stock Level</th>
                        <th>Cost Price</th>
                        <th>Sale Price</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>
                                <input class="form-check-input" type="checkbox">
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="stat-icon primary p-2 rounded" style="width: 38px; height: 38px; font-size: 1rem;">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $product['name'] }}</div>
                                        <span class="font-monospace small text-muted">{{ $product['sku'] }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $product['category'] }}</span>
                            </td>
                            <td>
                                <span class="small text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $product['warehouse'] }}</span>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $product['stock'] }} units</div>
                                <div class="progress" style="height: 5px; width: 100px;">
                                    <div class="progress-bar {{ $product['stock'] <= $product['reorder_level'] ? ($product['stock'] == 0 ? 'bg-danger' : 'bg-warning') : 'bg-success' }}"
                                         style="width: {{ $product['stock'] == 0 ? 5 : min(100, ($product['stock'] / ($product['reorder_level'] * 2)) * 100) }}%">
                                    </div>
                                </div>
                                <span class="text-muted" style="font-size: 0.7rem;">Min: {{ $product['reorder_level'] }}</span>
                            </td>
                            <td class="text-muted">{{ $product['cost_price'] }}</td>
                            <td class="fw-bold text-primary">{{ $product['unit_price'] }}</td>
                            <td>
                                @if($product['status'] === 'In Stock')
                                    <span class="badge-erp success"><span class="badge-erp-dot"></span> In Stock</span>
                                @elseif($product['status'] === 'Low Stock')
                                    <span class="badge-erp warning"><span class="badge-erp-dot"></span> Low Stock</span>
                                @elseif($product['status'] === 'Critical')
                                    <span class="badge-erp danger"><span class="badge-erp-dot"></span> Critical</span>
                                @else
                                    <span class="badge bg-secondary text-white px-2 py-1 rounded-pill small">Out of Stock</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-secondary" title="Quick Stock Adjustment" data-bs-toggle="tooltip">
                                        <i class="bi bi-plus-slash-minus"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary" title="Edit Product" data-bs-toggle="tooltip">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Pagination footer -->
        <div class="p-3 border-top d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
            <span class="small text-muted">Showing 1 to {{ count($products) }} of 1,480 products</span>
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content card-erp border-0 shadow-lg">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold" id="addProductModalLabel">
                        <i class="bi bi-box-seam text-primary me-2"></i> Register New Stock Item
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Product registered successfully (Theme Demo)');">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-semibold">SKU / Item Code</label>
                                <input type="text" class="form-control" placeholder="e.g. PRD-HYD-009" required>
                            </div>
                            <div class="col-12 col-md-8">
                                <label class="form-label small fw-semibold">Product Title</label>
                                <input type="text" class="form-control" placeholder="e.g. Industrial Hydraulic Filter" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-semibold">Category</label>
                                <select class="form-select" required>
                                    <option value="">Select Category...</option>
                                    <option>Mechanical Parts</option>
                                    <option>Electronics & Motors</option>
                                    <option>Hydraulics</option>
                                    <option>Pneumatics</option>
                                    <option>Hardware & Fasteners</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-semibold">Warehouse Location</label>
                                <select class="form-select" required>
                                    <option value="">Assign Warehouse...</option>
                                    <option>Main Warehouse (WH-A)</option>
                                    <option>East Coast Hub (WH-B)</option>
                                    <option>South Depot (WH-C)</option>
                                    <option>West Logistics (WH-D)</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label small fw-semibold">Cost Price ($)</label>
                                <input type="number" step="0.01" class="form-control" placeholder="0.00" required>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label small fw-semibold">Selling Price ($)</label>
                                <input type="number" step="0.01" class="form-control" placeholder="0.00" required>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label small fw-semibold">Initial Stock Qty</label>
                                <input type="number" class="form-control" placeholder="0" required>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label small fw-semibold">Reorder Threshold</label>
                                <input type="number" class="form-control" placeholder="10" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Product Description & Notes</label>
                                <textarea class="form-control" rows="3" placeholder="Specifications, vendor batch notes, or compliance marks..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary-erp">Save Product to Inventory</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
