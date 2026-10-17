@extends('layouts.admin')

@section('title', 'Roles & Permissions')
@section('page_title', 'Roles & Permissions Management')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Administration</a></li>
    <li class="breadcrumb-item active" aria-current="page">Roles &amp; Permissions</li>
@endsection

@section('actions')
    <button type="button" class="btn btn-primary-erp btn-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createRoleModal">
        <i class="bi bi-shield-plus"></i>
        <span>Create New Role</span>
    </button>
@endsection

@section('content')
    <!-- Roles Cards Grid -->
    <div class="row g-4 mb-4">
        @foreach($roles as $role)
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card-erp h-100 p-3 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill font-monospace" style="font-size: 0.7rem;">
                                {{ count($role['permissions']) }} Permissions
                            </span>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-icon" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-erp dropdown-menu-end">
                                    <li><a class="dropdown-item dropdown-item-erp" href="#"><i class="bi bi-pencil text-primary"></i> Edit Permissions</a></li>
                                    <li><a class="dropdown-item dropdown-item-erp" href="#"><i class="bi bi-people text-info"></i> View Assigned Users</a></li>
                                </ul>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-1">{{ $role['name'] }}</h5>
                        <p class="text-muted small mb-3">{{ $role['description'] }}</p>
                    </div>

                    <div class="border-top pt-3 mt-auto">
                        <div class="d-flex align-items-center justify-content-between small">
                            <span class="text-muted"><i class="bi bi-person me-1"></i>{{ $role['users_count'] }} Assigned Users</span>
                            <span class="fw-semibold text-primary">Active Role</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Permissions Matrix Table -->
    <div class="card-erp">
        <div class="card-header">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-shield-lock text-primary"></i>
                <span>Granular Permission Matrix (Spatie Permission v8)</span>
            </div>
            <span class="badge bg-light text-dark border">7 Permissions Defined</span>
        </div>
        <div class="table-responsive">
            <table class="table table-erp align-middle">
                <thead>
                    <tr>
                        <th>Permission Name</th>
                        <th>Module / Scope</th>
                        <th>Super Admin</th>
                        <th>Warehouse Manager</th>
                        <th>Sales Executive</th>
                        <th>Accountant</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissionMatrix as $perm)
                        <tr>
                            <td>
                                <div class="fw-semibold font-monospace text-primary">{{ $perm['name'] }}</div>
                                <span class="text-muted small" style="font-size: 0.72rem;">{{ $perm['description'] }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border">{{ $perm['module'] }}</span>
                            </td>
                            <td>
                                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            </td>
                            <td>
                                @if(in_array('Warehouse Manager', $perm['roles']))
                                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                @else
                                    <i class="bi bi-dash-circle text-muted fs-5"></i>
                                @endif
                            </td>
                            <td>
                                @if(in_array('Sales Executive', $perm['roles']))
                                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                @else
                                    <i class="bi bi-dash-circle text-muted fs-5"></i>
                                @endif
                            </td>
                            <td>
                                @if(in_array('Accountant', $perm['roles']))
                                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                @else
                                    <i class="bi bi-dash-circle text-muted fs-5"></i>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Role Modal -->
    <div class="modal fade" id="createRoleModal" tabindex="-1" aria-labelledby="createRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content card-erp border-0 shadow-lg">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold" id="createRoleModalLabel">
                        <i class="bi bi-shield-plus text-primary me-2"></i> Define New ERP Role
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Role created successfully with Spatie Permission!');">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Role Name</label>
                            <input type="text" class="form-control" placeholder="e.g. Procurement Specialist" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Role Description</label>
                            <input type="text" class="form-control" placeholder="Brief responsibility overview...">
                        </div>
                        <label class="form-label small fw-semibold mb-2">Assign Permissions</label>
                        <div class="row g-2">
                            <div class="col-6 col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked id="p1">
                                    <label class="form-check-label small" for="p1">view-dashboard</label>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="p2">
                                    <label class="form-check-label small" for="p2">manage-inventory</label>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="p3">
                                    <label class="form-check-label small" for="p3">manage-orders</label>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="p4">
                                    <label class="form-check-label small" for="p4">manage-customers</label>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="p5">
                                    <label class="form-check-label small" for="p5">manage-settings</label>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="p6">
                                    <label class="form-check-label small" for="p6">view-activity-log</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary-erp" data-bs-dismiss="modal">Save Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
