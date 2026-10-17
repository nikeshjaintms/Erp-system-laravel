@extends('layouts.admin')

@section('title', 'Sales Orders & Invoicing')
@section('page_title', 'Sales Orders & Commercial Invoices')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Operations</a></li>
    <li class="breadcrumb-item active" aria-current="page">Sales Orders</li>
@endsection

@section('actions')
    <button type="button" class="btn btn-outline-erp btn-sm d-flex align-items-center gap-2">
        <i class="bi bi-printer"></i>
        <span>Print Dispatch Sheets</span>
    </button>
    <button type="button" class="btn btn-primary-erp btn-sm d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i>
        <span>Create Sales Order</span>
    </button>
@endsection

@section('content')
    <!-- Order Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon primary"><i class="bi bi-cash-stack"></i></div>
                    <span class="stat-trend up"><i class="bi bi-arrow-up-right"></i> +9.4%</span>
                </div>
                <div class="stat-value">$74,180.50</div>
                <div class="stat-label">Processed This Week</div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon success"><i class="bi bi-check2-all"></i></div>
                    <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1">98.2% Rate</span>
                </div>
                <div class="stat-value">184 Orders</div>
                <div class="stat-label">On-Time Fulfillment Rate</div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon warning"><i class="bi bi-clock-history"></i></div>
                    <span class="stat-trend down"><i class="bi bi-dash"></i> 5 Unpaid</span>
                </div>
                <div class="stat-value">$18,340.00</div>
                <div class="stat-label">Overdue Outstanding Invoices</div>
            </div>
        </div>
    </div>

    <!-- Orders Filter Tabs & Table -->
    <div class="card-erp">
        <div class="card-header border-bottom-0 pb-0">
            <ul class="nav nav-tabs card-header-tabs" id="orderTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#allOrders"><i class="bi bi-grid-3x3-gap me-1"></i> All Orders ({{ count($orders) }})</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#pendingOrders"><i class="bi bi-hourglass-split me-1"></i> Pending Payment (1)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#shippingOrders"><i class="bi bi-truck me-1"></i> In Transit (1)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#completedOrders"><i class="bi bi-check-circle me-1"></i> Delivered (2)</a>
                </li>
            </ul>
        </div>
        <div class="card-body border-top">
            <div class="row g-2 mb-3">
                <div class="col-12 col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-transparent text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search by SO number or company name...">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <input type="date" class="form-control form-control-sm" value="2026-10-17">
                </div>
                <div class="col-6 col-md-3">
                    <select class="form-select form-select-sm">
                        <option value="">All Sales Reps</option>
                        <option>Alex Turner</option>
                        <option>David Miller</option>
                        <option>Elena Rostova</option>
                        <option>Marcus Vance</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 text-md-end">
                    <button class="btn btn-outline-secondary btn-sm w-100"><i class="bi bi-sliders me-1"></i> More Filters</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-erp align-middle">
                    <thead>
                        <tr>
                            <th>Order No</th>
                            <th>Customer & Account</th>
                            <th>Order Date</th>
                            <th>Subtotal + Tax</th>
                            <th>Sales Rep</th>
                            <th>Payment</th>
                            <th>Dispatch Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>
                                    <div class="fw-bold font-monospace text-primary">{{ $order['order_no'] }}</div>
                                    <span class="small text-muted"><i class="bi bi-file-text me-1"></i>Standard Tax Invoice</span>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $order['customer'] }}</div>
                                    <span class="badge bg-light text-secondary border font-monospace" style="font-size: 0.7rem;">{{ $order['customer_code'] }}</span>
                                </td>
                                <td>
                                    <span class="small text-muted">{{ $order['date'] }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $order['amount'] }}</div>
                                    <span class="text-muted small" style="font-size: 0.72rem;">Incl. {{ $order['tax'] }} VAT</span>
                                </td>
                                <td>
                                    <span class="small"><i class="bi bi-person me-1 text-muted"></i>{{ $order['sales_rep'] }}</span>
                                </td>
                                <td>
                                    @if($order['payment'] === 'Paid')
                                        <span class="badge-erp success"><span class="badge-erp-dot"></span> Paid</span>
                                    @elseif($order['payment'] === 'Pending')
                                        <span class="badge-erp warning"><span class="badge-erp-dot"></span> Pending</span>
                                    @else
                                        <span class="badge-erp danger"><span class="badge-erp-dot"></span> Overdue</span>
                                    @endif
                                </td>
                                <td>
                                    @if($order['status'] === 'Delivered')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill small">
                                            <i class="bi bi-check2 me-1"></i> Delivered
                                        </span>
                                    @elseif($order['status'] === 'In Transit')
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill small">
                                            <i class="bi bi-truck me-1"></i> In Transit
                                        </span>
                                    @elseif($order['status'] === 'Processing')
                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 rounded-pill small">
                                            <i class="bi bi-gear me-1"></i> Processing
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill small">
                                            <i class="bi bi-pause-fill me-1"></i> On Hold
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-icon" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-erp dropdown-menu-end">
                                            <li><a class="dropdown-item dropdown-item-erp" href="{{ route('admin.orders.pdf.download', $order['order_no']) }}"><i class="bi bi-file-earmark-pdf text-danger"></i> Download Invoice PDF</a></li>
                                            <li><a class="dropdown-item dropdown-item-erp" href="{{ route('admin.orders.pdf.stream', $order['order_no']) }}" target="_blank"><i class="bi bi-eye text-primary"></i> Preview PDF Invoice</a></li>
                                            <li><a class="dropdown-item dropdown-item-erp" href="#"><i class="bi bi-box-seam text-secondary"></i> Dispatch Delivery Note</a></li>
                                            <li><a class="dropdown-item dropdown-item-erp" href="#"><i class="bi bi-envelope text-info"></i> Email to Client</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
