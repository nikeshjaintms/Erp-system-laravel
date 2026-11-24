@extends('layouts.admin')

@section('title', 'Register New Item - Item Master')
@section('page_title', 'Register New Item')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Operations</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.items.index') }}">Item Master</a></li>
    <li class="breadcrumb-item active" aria-current="page">New Item</li>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-9">
            <div class="card-erp">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="bi bi-box-seam text-primary"></i>
                    <span class="fw-semibold">Item Specification & Master Parameters</span>
                </div>
                <form action="{{ route('admin.items.store') }}" method="POST">
                    @csrf
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
                                <input type="text" name="item_code" class="form-control font-monospace @error('item_code') is-invalid @enderror" placeholder="e.g. ITM-0001" value="{{ old('item_code') }}" required>
                                @error('item_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-8">
                                <label class="form-label small fw-semibold">Item Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Heavy Duty Industrial Bearing 40mm" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-semibold">Category</label>
                                <select name="category" class="form-select @error('category') is-invalid @enderror">
                                    <option value="">Select Category...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ old('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
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
                                        <option value="{{ $unit }}" {{ old('unit') === $unit ? 'selected' : '' }}>{{ $unit }}</option>
                                    @endforeach
                                </select>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-3">
                                <label class="form-label small fw-semibold">Cost Price ($) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" name="cost_price" class="form-control @error('cost_price') is-invalid @enderror" placeholder="0.00" value="{{ old('cost_price', '0.00') }}" required>
                                @error('cost_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-3">
                                <label class="form-label small fw-semibold">Unit Selling Price ($) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" name="unit_price" class="form-control @error('unit_price') is-invalid @enderror" placeholder="0.00" value="{{ old('unit_price', '0.00') }}" required>
                                @error('unit_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-3">
                                <label class="form-label small fw-semibold">Opening Stock <span class="text-danger">*</span></label>
                                <input type="number" step="0.0001" min="0" name="opening_stock" class="form-control @error('opening_stock') is-invalid @enderror" placeholder="0.00" value="{{ old('opening_stock', '0') }}" required>
                                @error('opening_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-3">
                                <label class="form-label small fw-semibold">Reorder Threshold <span class="text-danger">*</span></label>
                                <input type="number" step="0.0001" min="0" name="reorder_level" class="form-control @error('reorder_level') is-invalid @enderror" placeholder="10" value="{{ old('reorder_level', '10') }}" required>
                                @error('reorder_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-semibold">Opening Stock Date</label>
                                <input type="date" name="opening_stock_date" class="form-control @error('opening_stock_date') is-invalid @enderror" value="{{ old('opening_stock_date', date('Y-m-d')) }}">
                                @error('opening_stock_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6 d-flex align-items-center mt-md-4 pt-md-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" name="is_active" id="isActiveSwitch" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold small" for="isActiveSwitch">Active in Catalog & Stock Ledger</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-semibold">Product Description & Technical Specifications</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Enter materials, dimensions, compliance standard, or storage notes...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light p-3 border-top d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.items.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Back to Catalog
                        </a>
                        <button type="submit" class="btn btn-primary-erp d-flex align-items-center gap-2">
                            <i class="bi bi-check2-circle"></i>
                            <span>Save Item Master</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
