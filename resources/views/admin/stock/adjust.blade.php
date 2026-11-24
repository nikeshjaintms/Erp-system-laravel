@extends('layouts.admin')

@section('title', 'Stock Adjustment - ' . $item->item_code)
@section('page_title', 'Stock Balance Adjustment')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Operations</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.stock.index') }}">Stock Ledger</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.stock.history', $item) }}">{{ $item->item_code }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">Adjust</li>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card-erp">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-plus-slash-minus text-primary"></i>
                        <span class="fw-semibold">Physical Count / Stock Adjustment Entry</span>
                    </div>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace">
                        {{ $item->item_code }}
                    </span>
                </div>
                <form action="{{ route('admin.stock.adjust.store', $item) }}" method="POST">
                    @csrf
                    <div class="card-body p-4">
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h6 class="alert-heading fw-bold mb-1"><i class="bi bi-exclamation-octagon-fill me-1"></i> Adjustment Failed:</h6>
                                <ul class="mb-0 small ps-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Item Snapshot Banner -->
                        <div class="p-3 bg-light rounded border mb-4">
                            <div class="row align-items-center">
                                <div class="col-12 col-md-7">
                                    <div class="fw-bold fs-6">{{ $item->name }}</div>
                                    <div class="text-muted small">
                                        <span class="me-2">Category: {{ $item->category ?? 'General' }}</span>
                                        <span>UOM: <span class="badge bg-secondary-subtle text-dark">{{ $item->unit }}</span></span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-5 text-md-end mt-2 mt-md-0">
                                    <span class="text-muted small d-block">Current In-Hand Stock:</span>
                                    <span class="fs-4 fw-bold font-monospace text-primary">
                                        {{ number_format((float) $item->current_stock, 2) }} {{ $item->unit }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-semibold">Current System Balance</label>
                                <input type="text" class="form-control font-monospace bg-light" value="{{ number_format((float) $item->current_stock, 2) }} {{ $item->unit }}" readonly>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-semibold">New Verified Physical Count <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.0001" min="0" name="new_quantity" id="newQuantityInput" class="form-control font-monospace @error('new_quantity') is-invalid @enderror" placeholder="Enter counted quantity..." value="{{ old('new_quantity') }}" required>
                                    <span class="input-group-text bg-light text-muted">{{ $item->unit }}</span>
                                    @error('new_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="alert alert-secondary py-2 px-3 small d-flex align-items-center gap-2 mb-0" id="deltaPreviewAlert">
                                    <i class="bi bi-info-circle text-primary"></i>
                                    <span id="deltaText">Enter a new physical count to see the calculated net adjustment.</span>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-semibold">Reason for Adjustment & Audit Notes <span class="text-danger">*</span></label>
                                <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="3" placeholder="e.g. Monthly physical stock audit variance, damaged unit write-off, or supplier packing correction..." required>{{ old('reason') }}</textarea>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light p-3 border-top d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.stock.history', $item) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary-erp d-flex align-items-center gap-2">
                            <i class="bi bi-check2-circle"></i>
                            <span>Post Stock Adjustment</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const currentStock = {{ (float) $item->current_stock }};
            const input = document.getElementById('newQuantityInput');
            const alertBox = document.getElementById('deltaPreviewAlert');
            const deltaText = document.getElementById('deltaText');

            function updateDelta() {
                const val = parseFloat(input.value);
                if (isNaN(val)) {
                    deltaText.textContent = 'Enter a new physical count to see the calculated net adjustment.';
                    alertBox.className = 'alert alert-secondary py-2 px-3 small d-flex align-items-center gap-2 mb-0';
                    return;
                }

                const delta = (val - currentStock);
                if (delta > 0) {
                    deltaText.innerHTML = `<strong>Addition (+${delta.toFixed(2)} {{ $item->unit }})</strong> will be posted to inventory ledger.`;
                    alertBox.className = 'alert alert-success py-2 px-3 small d-flex align-items-center gap-2 mb-0';
                } else if (delta < 0) {
                    deltaText.innerHTML = `<strong>Deduction (${delta.toFixed(2)} {{ $item->unit }})</strong> will be posted to inventory ledger.`;
                    alertBox.className = 'alert alert-warning py-2 px-3 small d-flex align-items-center gap-2 mb-0';
                } else {
                    deltaText.innerHTML = `No change in stock (Delta is 0.00).`;
                    alertBox.className = 'alert alert-info py-2 px-3 small d-flex align-items-center gap-2 mb-0';
                }
            }

            input.addEventListener('input', updateDelta);
            if (input.value) {
                updateDelta();
            }
        });
    </script>
@endsection
