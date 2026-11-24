@extends('layouts.admin')

@section('title', 'Edit Item - ' . $item->item_code)
@section('page_title', 'Edit Item Master')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Operations</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.items.index') }}">Item Master</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $item->item_code }}</li>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-9">
            <div class="card-erp">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-pencil-square text-primary"></i>
                        <span class="fw-semibold">Edit Item: {{ $item->item_code }}</span>
                    </div>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                        Current Balance: {{ number_format((float) $item->current_stock, 2) }} {{ $item->unit }}
                    </span>
                </div>
                <form action="{{ route('admin.items.update', $item) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body p-4">
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h6 class="alert-heading fw-bold mb-1"><i class="bi bi-exclamation-octagon-fill me-1"></i> Please check input errors:</h6>
                                <ul class="mb-0 small ps-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-semibold">Item Code / SKU <span class="text-danger">*</span></label>
                                <input type="text" name="item_code" class="form-control font-monospace @error('item_code') is-invalid @enderror" value="{{ old('item_code', $item->item_code) }}" required>
                                @error('item_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-8">
                                <label class="form-label small fw-semibold">Item Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $item->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-semibold">Category</label>
                                <select name="category" class="form-select @error('category') is-invalid @enderror">
                                    <option value="">Select Category...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ old('category', $item->category) === $category ? 'selected' : '' }}>{{ $category }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-semibold">Unit of Measure (UOM) <span class="text-danger">*</span></label>
                                <select name="unit" class="form-select @error('unit') is-invalid @enderror" required>
                                    <option value="">Select Unit...</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit }}" {{ old('unit', $item->unit) === $unit ? 'selected' : '' }}>{{ $unit }}</option>
                                    @endforeach
                                </select>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-semibold">Cost Price ($) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" name="cost_price" class="form-control @error('cost_price') is-invalid @enderror" value="{{ old('cost_price', $item->cost_price) }}" required>
                                @error('cost_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-semibold">Unit Selling Price ($) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" name="unit_price" class="form-control @error('unit_price') is-invalid @enderror" value="{{ old('unit_price', $item->unit_price) }}" required>
                                @error('unit_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-semibold">Reorder Threshold <span class="text-danger">*</span></label>
                                <input type="number" step="0.0001" min="0" name="reorder_level" class="form-control @error('reorder_level') is-invalid @enderror" value="{{ old('reorder_level', $item->reorder_level) }}" required>
                                @error('reorder_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-check form-switch pt-2">
                                    <input class="form-check-input" type="checkbox" role="switch" name="is_active" id="isActiveSwitch" value="1" {{ old('is_active', $item->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold small" for="isActiveSwitch">Active in Catalog & Stock Ledger</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-semibold">Product Description & Technical Specifications</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $item->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light p-3 border-top d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.items.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Cancel
                        </a>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.stock.adjust.form', $item) }}" class="btn btn-outline-erp">
                                <i class="bi bi-plus-slash-minus me-1"></i> Stock Adjustment
                            </a>
                            <button type="submit" class="btn btn-primary-erp d-flex align-items-center gap-2">
                                <i class="bi bi-check2-circle"></i>
                                <span>Update Item</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
