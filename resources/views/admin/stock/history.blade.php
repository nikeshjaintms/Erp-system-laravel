@extends('layouts.admin')

@section('title', 'Stock Movement History - ' . $item->item_code)
@section('page_title', 'Stock Movement History')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Operations</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.stock.index') }}">Stock Ledger</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $item->item_code }} History</li>
@endsection

@section('actions')
    <a href="{{ route('admin.stock.adjust.form', $item) }}" class="btn btn-outline-erp btn-sm d-flex align-items-center gap-2">
        <i class="bi bi-plus-slash-minus"></i>
        <span>Adjust Stock</span>
    </a>
    <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2">
        <i class="bi bi-pencil"></i>
        <span>Edit Item</span>
    </a>
    <a href="{{ route('admin.stock.index') }}" class="btn btn-primary-erp btn-sm d-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i>
        <span>Back to Stock Ledger</span>
    </a>
@endsection

@section('content')
    <!-- Item Ledger Header Card -->
    <div class="card-erp mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center g-3">
                <div class="col-12 col-md-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon primary p-3 rounded" style="width: 54px; height: 54px; font-size: 1.5rem;">
                            <i class="bi bi-boxes"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h4 class="fw-bold mb-0 text-dark">{{ $item->name }}</h4>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace px-2 py-1">
                                    {{ $item->item_code }}
                                </span>
                            </div>
                            <div class="text-muted small mt-1">
                                <span class="me-3"><i class="bi bi-tag me-1"></i>{{ $item->category ?? 'General' }}</span>
                                <span class="me-3"><i class="bi bi-rulers me-1"></i>UOM: {{ $item->unit }}</span>
                                <span><i class="bi bi-bell me-1"></i>Reorder Threshold: {{ number_format((float) $item->reorder_level, 2) }} {{ $item->unit }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 text-md-end">
                    <div class="p-3 bg-light rounded border d-inline-block text-center text-md-end min-w-150">
                        <div class="text-muted small fw-semibold text-uppercase">Current In-Hand Balance</div>
                        <div class="h3 fw-bold my-1 {{ (float) $item->current_stock <= (float) $item->reorder_level ? 'text-danger' : 'text-success' }}">
                            {{ number_format((float) $item->current_stock, 2) }} <span class="fs-6 fw-normal text-muted">{{ $item->unit }}</span>
                        </div>
                        <div class="small">
                            Status: <span class="fw-semibold">{{ $item->stock_status }}</span>
                        </div>
                    </div>
                </div>
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

    <!-- Movement History Filters -->
    <div class="card-erp mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.stock.history', $item) }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-3">
                    <select name="type" class="form-select">
                        <option value="">All Movement Types</option>
                        <option value="received" {{ ($filters['type'] ?? '') === 'received' ? 'selected' : '' }}>Received (+)</option>
                        <option value="issued" {{ ($filters['type'] ?? '') === 'issued' ? 'selected' : '' }}>Issued (-)</option>
                        <option value="returned" {{ ($filters['type'] ?? '') === 'returned' ? 'selected' : '' }}>Returned (+)</option>
                        <option value="adjustment" {{ ($filters['type'] ?? '') === 'adjustment' ? 'selected' : '' }}>Adjustments (+/-)</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <input type="date" name="date_from" class="form-control" placeholder="From Date" value="{{ $filters['date_from'] ?? '' }}">
                </div>
                <div class="col-6 col-md-3">
                    <input type="date" name="date_to" class="form-control" placeholder="To Date" value="{{ $filters['date_to'] ?? '' }}">
                </div>
                <div class="col-12 col-md-3 d-flex justify-content-md-end gap-2">
                    <button type="submit" class="btn btn-outline-erp flex-grow-1 flex-md-grow-0 d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-filter"></i> Filter
                    </button>
                    @if(!empty($filters['type']) || !empty($filters['date_from']) || !empty($filters['date_to']))
                        <a href="{{ route('admin.stock.history', $item) }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Movement Audit Ledger Table -->
    <div class="card-erp">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-clock-history text-primary"></i>
                <span class="fw-semibold">Immutable Movement History & Audit Log</span>
            </div>
            <div class="small text-muted">Showing {{ $movements->firstItem() ?? 0 }} - {{ $movements->lastItem() ?? 0 }} of {{ $movements->total() }} postings</div>
        </div>
        <div class="table-responsive">
            <table class="table table-erp align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Posting Date & Time</th>
                        <th>Type</th>
                        <th class="text-end">Quantity</th>
                        <th class="text-end">Balance After</th>
                        <th>Reference / Document</th>
                        <th>Remarks / Reason</th>
                        <th>Authorized User</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $movement)
                        @php
                            $isInbound = $movement->type->isInbound($movement->adjustment_direction);
                        @endphp
                        <tr>
                            <td>
                                <span class="font-monospace text-muted small">#{{ $movement->id }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $movement->created_at->format('M d, Y') }}</div>
                                <div class="small text-muted font-monospace">{{ $movement->created_at->format('H:i:s') }}</div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $movement->type->badgeClass() }}-subtle text-{{ $movement->type->badgeClass() }} border border-{{ $movement->type->badgeClass() }}-subtle rounded-pill">
                                    {{ $movement->type->label() }}
                                    @if($movement->adjustment_direction)
                                        ({{ $movement->adjustment_direction->value === 'increase' ? '+' : '-' }})
                                    @endif
                                </span>
                            </td>
                            <td class="text-end font-monospace fw-bold {{ $isInbound ? 'text-success' : 'text-danger' }}">
                                {{ $isInbound ? '+' : '-' }}{{ number_format((float) $movement->quantity, 2) }} {{ $item->unit }}
                            </td>
                            <td class="text-end font-monospace fw-bold text-dark">
                                {{ number_format((float) $movement->balance_after, 2) }}
                            </td>
                            <td>
                                @if($movement->reference)
                                    <span class="badge bg-light text-dark border font-monospace">
                                        {{ class_basename($movement->reference_type) }} #{{ $movement->reference_id }}
                                    </span>
                                @else
                                    <span class="text-muted small">Manual Posting</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-secondary small">{{ $movement->remarks ?? '—' }}</span>
                            </td>
                            <td>
                                <div class="small fw-semibold">{{ $movement->creator->name ?? 'System' }}</div>
                                <div class="small text-muted">{{ $movement->creator->email ?? '' }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-clock-history display-6 d-block mb-3 text-secondary"></i>
                                    <h6 class="fw-bold">No movement entries recorded</h6>
                                    <p class="small mb-3">No stock receipts, dispatches or adjustments match your query.</p>
                                    <a href="{{ route('admin.stock.adjust.form', $item) }}" class="btn btn-outline-erp btn-sm">
                                        <i class="bi bi-plus-slash-minus me-1"></i> Make Initial Stock Adjustment
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($movements->hasPages())
            <div class="card-footer bg-transparent border-top p-3 d-flex justify-content-between align-items-center">
                <div class="small text-muted">
                    Page {{ $movements->currentPage() }} of {{ $movements->lastPage() }}
                </div>
                {{ $movements->links() }}
            </div>
        @endif
    </div>
@endsection
