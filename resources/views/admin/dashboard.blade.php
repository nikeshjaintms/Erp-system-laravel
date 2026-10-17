@extends('layouts.admin')

@section('title', 'ERP Dashboard')
@section('page_title', 'ERP Executive Dashboard')

@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('actions')
    <div class="dropdown">
        <button class="btn btn-outline-erp btn-sm dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
            <i class="bi bi-calendar3"></i>
            <span>This Month: Oct 2026</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-erp dropdown-menu-end">
            <li><a class="dropdown-item dropdown-item-erp" href="#">Today</a></li>
            <li><a class="dropdown-item dropdown-item-erp" href="#">This Week</a></li>
            <li><a class="dropdown-item dropdown-item-erp active" href="#">This Month (Oct 2026)</a></li>
            <li><a class="dropdown-item dropdown-item-erp" href="#">Last Quarter (Q3)</a></li>
            <li><a class="dropdown-item dropdown-item-erp" href="#">Year to Date (YTD)</a></li>
        </ul>
    </div>
    <button class="btn btn-primary-erp btn-sm d-flex align-items-center gap-2" onclick="window.print()">
        <i class="bi bi-download"></i>
        <span class="d-none d-sm-inline">Export Report</span>
    </button>
@endsection

@section('content')
    <!-- KPI Metric Cards Grid -->
    <div class="row g-3 mb-4">
        <!-- Revenue Card -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon primary">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <span class="stat-trend up">
                        <i class="bi bi-arrow-up-right"></i> {{ $metrics['revenue']['change'] }}
                    </span>
                </div>
                <div class="stat-value">{{ $metrics['revenue']['value'] }}</div>
                <div class="stat-label">Total Gross Revenue</div>
                <div class="text-muted small mt-1" style="font-size: 0.75rem;">{{ $metrics['revenue']['subtext'] }}</div>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon success">
                        <i class="bi bi-cart-check"></i>
                    </div>
                    <span class="stat-trend up">
                        <i class="bi bi-arrow-up-right"></i> {{ $metrics['orders']['change'] }}
                    </span>
                </div>
                <div class="stat-value">{{ $metrics['orders']['value'] }}</div>
                <div class="stat-label">Processed Orders</div>
                <div class="text-muted small mt-1" style="font-size: 0.75rem;">{{ $metrics['orders']['subtext'] }}</div>
            </div>
        </div>

        <!-- Inventory Value Card -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon warning">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <span class="stat-trend down">
                        <i class="bi bi-arrow-down-right"></i> {{ $metrics['inventory_value']['change'] }}
                    </span>
                </div>
                <div class="stat-value">{{ $metrics['inventory_value']['value'] }}</div>
                <div class="stat-label">Valuation on Hand</div>
                <div class="text-muted small mt-1" style="font-size: 0.75rem;">{{ $metrics['inventory_value']['subtext'] }}</div>
            </div>
        </div>

        <!-- Shipments Card -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon info">
                        <i class="bi bi-truck"></i>
                    </div>
                    <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                        <i class="bi bi-exclamation-circle me-1"></i> Priority
                    </span>
                </div>
                <div class="stat-value">{{ $metrics['pending_shipments']['value'] }}</div>
                <div class="stat-label">Pending Dispatch</div>
                <div class="text-muted small mt-1" style="font-size: 0.75rem;">{{ $metrics['pending_shipments']['subtext'] }}</div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Revenue & Cash Flow Chart -->
        <div class="col-12 col-lg-8">
            <div class="card-erp h-100">
                <div class="card-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-graph-up text-primary"></i>
                        <span>Revenue & Procurement Analytics (2026)</span>
                    </div>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary active">Monthly</button>
                        <button type="button" class="btn btn-outline-secondary">Weekly</button>
                    </div>
                </div>
                <div class="card-body">
                    <div style="height: 320px; position: relative;">
                        <canvas id="revenueFlowChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category & Warehouse Breakdown -->
        <div class="col-12 col-lg-4">
            <div class="card-erp h-100">
                <div class="card-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-pie-chart text-info"></i>
                        <span>Stock Allocation by Hub</span>
                    </div>
                </div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <div style="height: 220px; position: relative;" class="my-auto">
                        <canvas id="warehouseDoughnutChart"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center mb-1 small">
                            <span><i class="bi bi-circle-fill text-primary me-2" style="font-size: 0.55rem;"></i> Main Hub (WH-A)</span>
                            <span class="fw-bold">48% ($279k)</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1 small">
                            <span><i class="bi bi-circle-fill text-info me-2" style="font-size: 0.55rem;"></i> East Coast (WH-B)</span>
                            <span class="fw-bold">26% ($151k)</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center small">
                            <span><i class="bi bi-circle-fill text-warning me-2" style="font-size: 0.55rem;"></i> South Depot (WH-C)</span>
                            <span class="fw-bold">26% ($151k)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables & Side Widgets Row -->
    <div class="row g-4">
        <!-- Recent Orders Table -->
        <div class="col-12 col-lg-8">
            <div class="card-erp">
                <div class="card-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-receipt text-primary"></i>
                        <span>Recent Sales Orders & Fulfillment</span>
                    </div>
                    <a href="{{ route('admin.orders') }}" class="btn btn-outline-erp btn-sm">View All Orders</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-erp align-middle">
                        <thead>
                            <tr>
                                <th>Order Ref</th>
                                <th>Client / Entity</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders') }}" class="fw-bold font-monospace text-primary">#{{ $order['id'] }}</a>
                                        <div class="text-muted small" style="font-size: 0.72rem;">{{ $order['date'] }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $order['avatar'] }}" alt="{{ $order['customer'] }}" class="rounded-circle" style="width: 28px; height: 28px; object-fit: cover;">
                                            <span class="fw-semibold text-truncate" style="max-width: 170px;">{{ $order['customer'] }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2 py-1">{{ $order['items_count'] }} pcs</span>
                                    </td>
                                    <td class="fw-bold text-nowrap">{{ $order['total'] }}</td>
                                    <td>
                                        @if($order['payment_status'] === 'Paid')
                                            <span class="badge-erp success">
                                                <span class="badge-erp-dot"></span> Paid
                                            </span>
                                        @elseif($order['payment_status'] === 'Pending')
                                            <span class="badge-erp warning">
                                                <span class="badge-erp-dot"></span> Pending
                                            </span>
                                        @else
                                            <span class="badge-erp danger">
                                                <span class="badge-erp-dot"></span> Failed
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order['order_status'] === 'Delivered')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill small">
                                                <i class="bi bi-check2-circle me-1"></i> Delivered
                                            </span>
                                        @elseif($order['order_status'] === 'In Transit')
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill small">
                                                <i class="bi bi-truck me-1"></i> In Transit
                                            </span>
                                        @elseif($order['order_status'] === 'Processing')
                                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 rounded-pill small">
                                                <i class="bi bi-arrow-repeat me-1"></i> Processing
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill small">
                                                <i class="bi bi-pause-circle me-1"></i> On Hold
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-icon" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-erp dropdown-menu-end">
                                                <li><a class="dropdown-item dropdown-item-erp" href="#"><i class="bi bi-eye text-primary"></i> View Details</a></li>
                                                <li><a class="dropdown-item dropdown-item-erp" href="#"><i class="bi bi-printer text-secondary"></i> Print Invoice</a></li>
                                                <li><a class="dropdown-item dropdown-item-erp" href="#"><i class="bi bi-truck text-info"></i> Track Shipment</a></li>
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

        <!-- Inventory Alerts & Quick Operations -->
        <div class="col-12 col-lg-4">
            <!-- Critical Stock Widget -->
            <div class="card-erp mb-4">
                <div class="card-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                        <span>Critical Stock Alerts</span>
                    </div>
                    <a href="{{ route('admin.inventory') }}" class="small fw-semibold text-primary">Reorder All</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($lowStockItems as $item)
                            <div class="list-group-item p-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <div>
                                        <span class="font-monospace small fw-bold text-muted">{{ $item['sku'] }}</span>
                                        <div class="fw-semibold text-truncate" style="max-width: 220px;">{{ $item['name'] }}</div>
                                    </div>
                                    <span class="badge {{ $item['status'] === 'Critical' ? 'bg-danger' : 'bg-warning text-dark' }} rounded-pill small">
                                        {{ $item['stock'] }} left
                                    </span>
                                </div>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar {{ $item['status'] === 'Critical' ? 'bg-danger' : 'bg-warning' }}" 
                                         role="progressbar" 
                                         style="width: {{ ($item['stock'] / $item['min']) * 100 }}%">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between text-muted small mt-1" style="font-size: 0.7rem;">
                                    <span>Current: {{ $item['stock'] }} units</span>
                                    <span>Min Threshold: {{ $item['min'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Quick Action Shortcuts -->
            <div class="card-erp">
                <div class="card-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-lightning-charge text-warning"></i>
                        <span>Quick Operations</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.inventory') }}" class="btn btn-outline-erp d-flex align-items-center justify-content-between p-2.5">
                            <span class="d-flex align-items-center gap-2">
                                <i class="bi bi-plus-circle text-primary fs-5"></i>
                                <span class="fw-semibold">Add New Product / SKU</span>
                            </span>
                            <i class="bi bi-chevron-right text-muted small"></i>
                        </a>
                        <a href="{{ route('admin.orders') }}" class="btn btn-outline-erp d-flex align-items-center justify-content-between p-2.5">
                            <span class="d-flex align-items-center gap-2">
                                <i class="bi bi-receipt-cutoff text-success fs-5"></i>
                                <span class="fw-semibold">Generate Sales Order</span>
                            </span>
                            <i class="bi bi-chevron-right text-muted small"></i>
                        </a>
                        <a href="{{ route('admin.customers') }}" class="btn btn-outline-erp d-flex align-items-center justify-content-between p-2.5">
                            <span class="d-flex align-items-center gap-2">
                                <i class="bi bi-person-plus text-info fs-5"></i>
                                <span class="fw-semibold">Onboard New Customer</span>
                            </span>
                            <i class="bi bi-chevron-right text-muted small"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Revenue & Procurement Bar/Line Chart
        const revCtx = document.getElementById('revenueFlowChart');
        if (revCtx) {
            new Chart(revCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
                    datasets: [
                        {
                            label: 'Sales Revenue ($)',
                            data: [85000, 92000, 104000, 98000, 118000, 126000, 134000, 139000, 142000, 148920],
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.12)',
                            tension: 0.35,
                            fill: true,
                            pointBackgroundColor: '#4f46e5',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            borderWidth: 2.5
                        },
                        {
                            label: 'Procurement Cost ($)',
                            data: [45000, 52000, 58000, 51000, 64000, 69000, 72000, 78000, 74000, 81000],
                            borderColor: '#0ea5e9',
                            backgroundColor: 'transparent',
                            borderDash: [5, 5],
                            tension: 0.35,
                            pointRadius: 3,
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                usePointStyle: true,
                                font: { family: "'Plus Jakarta Sans', sans-serif", size: 12 }
                            }
                        },
                        tooltip: {
                            padding: 10,
                            boxPadding: 4,
                            usePointStyle: true
                        }
                    },
                    scales: {
                        y: {
                            grid: { color: 'rgba(148, 163, 184, 0.1)' },
                            ticks: {
                                callback: function(value) { return '$' + (value / 1000) + 'k'; }
                            }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        // Warehouse Stock Doughnut Chart
        const whCtx = document.getElementById('warehouseDoughnutChart');
        if (whCtx) {
            new Chart(whCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Main WH-A', 'East WH-B', 'South WH-C'],
                    datasets: [{
                        data: [48, 26, 26],
                        backgroundColor: ['#4f46e5', '#0ea5e9', '#f59e0b'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    cutout: '72%'
                }
            });
        }
    });
</script>
@endpush
