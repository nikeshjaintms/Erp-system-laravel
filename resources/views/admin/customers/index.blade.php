@extends('layouts.admin')

@section('title', 'Customers & Client Accounts')
@section('page_title', 'Customers & CRM Accounts')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">CRM</a></li>
    <li class="breadcrumb-item active" aria-current="page">Customers</li>
@endsection

@section('actions')
    <button type="button" class="btn btn-outline-erp btn-sm d-flex align-items-center gap-2">
        <i class="bi bi-cloud-arrow-down"></i>
        <span>Export Contacts</span>
    </button>
    <button type="button" class="btn btn-primary-erp btn-sm d-flex align-items-center gap-2">
        <i class="bi bi-person-plus"></i>
        <span>Add Customer Account</span>
    </button>
@endsection

@section('content')
    <div class="card-erp mb-4">
        <div class="card-body p-3">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search by customer name, account ID, email, or city...">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select class="form-select">
                        <option value="">All Account Statuses</option>
                        <option>Active Accounts</option>
                        <option>Credit Hold</option>
                        <option>Inactive</option>
                    </select>
                </div>
                <div class="col-6 col-md-4 text-end">
                    <button class="btn btn-outline-erp"><i class="bi bi-sliders me-1"></i> Advanced Filter</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="card-erp">
        <div class="card-header">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-building text-primary"></i>
                <span>Corporate Accounts Directory</span>
            </div>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">5 Accounts Listed</span>
        </div>
        <div class="table-responsive">
            <table class="table table-erp align-middle">
                <thead>
                    <tr>
                        <th>Account ID</th>
                        <th>Company Name</th>
                        <th>Primary Contact</th>
                        <th>Location</th>
                        <th>Credit Facility</th>
                        <th>Current Balance</th>
                        <th>Total Orders</th>
                        <th>Account Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                        <tr>
                            <td>
                                <span class="font-monospace fw-bold text-primary">{{ $customer['code'] }}</span>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $customer['name'] }}</div>
                                <div class="small text-muted"><i class="bi bi-envelope me-1"></i>{{ $customer['email'] }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $customer['contact_person'] }}</div>
                                <div class="small text-muted"><i class="bi bi-telephone me-1"></i>{{ $customer['phone'] }}</div>
                            </td>
                            <td>
                                <span class="small"><i class="bi bi-geo-alt text-muted me-1"></i>{{ $customer['city'] }}</span>
                            </td>
                            <td>
                                <span class="small text-muted">Limit:</span>
                                <div class="fw-semibold">{{ $customer['credit_limit'] }}</div>
                            </td>
                            <td>
                                <div class="fw-bold {{ $customer['balance'] !== '$0.00' ? 'text-danger' : 'text-success' }}">
                                    {{ $customer['balance'] }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1">{{ $customer['orders_count'] }} Orders</span>
                            </td>
                            <td>
                                @if($customer['status'] === 'Active')
                                    <span class="badge-erp success"><span class="badge-erp-dot"></span> Active</span>
                                @else
                                    <span class="badge-erp danger"><span class="badge-erp-dot"></span> Credit Hold</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-icon" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-erp dropdown-menu-end">
                                        <li><a class="dropdown-item dropdown-item-erp" href="#"><i class="bi bi-person-lines-fill text-primary"></i> Customer Ledger</a></li>
                                        <li><a class="dropdown-item dropdown-item-erp" href="#"><i class="bi bi-receipt text-success"></i> View Invoices</a></li>
                                        <li><a class="dropdown-item dropdown-item-erp" href="#"><i class="bi bi-pencil text-secondary"></i> Edit Account</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
