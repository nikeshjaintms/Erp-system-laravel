@extends('layouts.admin')

@section('title', 'Users Management')
@section('page_title', 'Users Management')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Administration</a></li>
<li class="breadcrumb-item active" aria-current="page">Users</li>
@endsection

@section('actions')
<button type="button" class="btn btn-outline-erp btn-sm d-flex align-items-center gap-2">
    <i class="bi bi-cloud-arrow-down"></i>
    <span>Export Users</span>
</button>
<button type="button" class="btn btn-primary-erp btn-sm d-flex align-items-center gap-2">
    <i class="bi bi-person-plus"></i>
    <span>Add New User</span>
</button>
@endsection

@section('content')
<!-- Filter & Search Toolbar -->
<div class="card-erp mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.users') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-transparent text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search by name, email, or phone..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">All Account Statuses</option>
                    @foreach(($statuses ?? $statues) as $status)
                        <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                            {{ ucfirst($status->label()) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-4 text-end d-flex justify-content-end gap-2">
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i> Clear</a>
                @endif
                <button type="submit" class="btn btn-outline-erp"><i class="bi bi-filter me-1"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Users Directory Table -->
<div class="card-erp">
    <div class="card-header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-people text-primary"></i>
            <span>Users Directory</span>
        </div>
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
            {{ $users->total() }} {{ Str::plural('User', $users->total()) }} Listed
        </span>
    </div>
    <div class="table-responsive">
        <table class="table table-erp align-middle">
            <thead>
                <tr>
                    <th style="width: 70px;">#</th>
                    <th>User</th>
                    <th>Email Address</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Account Status</th>
                    <th class="text-end" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <span class="font-monospace fw-bold text-primary">#{{ $user->id }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="user-avatar-badge rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center border border-primary-subtle" style="width: 40px; height: 40px; font-size: 0.85rem; flex-shrink: 0;">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="fw-bold">{{ $user->name }}</div>
                                <div class="small text-muted"><i class="bi bi-person me-1"></i>ID: {{ $user->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $user->email }}</div>
                        <div class="small text-muted">
                            <i class="bi bi-calendar3 me-1"></i>Joined {{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}
                        </div>
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $user->phone ?? '—' }}</div>
                        <div class="small text-muted"><i class="bi bi-telephone me-1"></i>Contact</div>
                    </td>
                    <td>
                        @forelse($user->roles as $role)
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1">{{ $role->name }}</span>
                        @empty
                            <span class="badge bg-light text-muted border px-2 py-1">User</span>
                        @endforelse
                    </td>
                    <td>
                        @if($user->status && $user->status->value === 'active')
                            <span class="badge-erp success"><span class="badge-erp-dot"></span> Active</span>
                        @elseif($user->status && $user->status->value === 'inactive')
                            <span class="badge-erp warning"><span class="badge-erp-dot"></span> Inactive</span>
                        @elseif($user->status && $user->status->value === 'suspended')
                            <span class="badge-erp danger"><span class="badge-erp-dot"></span> Suspended</span>
                        @else
                            <span class="badge-erp success"><span class="badge-erp-dot"></span> Active</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-icon" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-erp dropdown-menu-end shadow-lg">
                                <li>
                                    <a class="dropdown-item dropdown-item-erp" href="#">
                                        <i class="bi bi-person text-primary"></i> View Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item dropdown-item-erp" href="#">
                                        <i class="bi bi-pencil text-secondary"></i> Edit User
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1 border-secondary-subtle"></li>
                                <li>
                                    <a class="dropdown-item dropdown-item-erp text-danger" href="#">
                                        <i class="bi bi-trash"></i> Delete User
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-people fs-1 d-block mb-2 text-secondary"></i>
                        <p class="mb-0 fw-semibold">No users found matching your search.</p>
                        <small>Try adjusting your search filters above.</small>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="card-footer bg-transparent border-top p-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
        <span class="small text-muted">Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries</span>
        <div>{{ $users->links('pagination::bootstrap-5') }}</div>
    </div>
    @endif
</div>
@endsection