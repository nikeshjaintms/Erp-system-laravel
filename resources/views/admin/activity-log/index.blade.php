@extends('layouts.admin')

@section('title', 'System Audit & Activity Logs')
@section('page_title', 'System Audit & Activity Logs')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Administration</a></li>
    <li class="breadcrumb-item active" aria-current="page">Activity Logs</li>
@endsection

@section('actions')
    <button type="button" class="btn btn-outline-erp btn-sm d-flex align-items-center gap-2">
        <i class="bi bi-download"></i>
        <span>Export Log Archive</span>
    </button>
@endsection

@section('content')
    <!-- Overview Metric Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card-erp p-3 text-center">
                <div class="text-muted small fw-semibold text-uppercase">Total Logged Events</div>
                <div class="h3 fw-bold my-1 text-primary">{{ count($activities) }}</div>
                <div class="small text-muted">Audited with Spatie Activitylog</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card-erp p-3 text-center">
                <div class="text-muted small fw-semibold text-uppercase">Admin Sessions</div>
                <div class="h3 fw-bold my-1 text-success">1 Active</div>
                <div class="small text-success">Current Operator: Sarah Jenkins</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card-erp p-3 text-center">
                <div class="text-muted small fw-semibold text-uppercase">Security Status</div>
                <div class="h3 fw-bold my-1 text-info">Normal</div>
                <div class="small text-info">No unauthorized attempts</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card-erp p-3 text-center">
                <div class="text-muted small fw-semibold text-uppercase">Log Retention</div>
                <div class="h3 fw-bold my-1">365 Days</div>
                <div class="small text-muted">Automated prune enabled</div>
            </div>
        </div>
    </div>

    <!-- Activity Log Table -->
    <div class="card-erp">
        <div class="card-header">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-journal-text text-primary"></i>
                <span>Audit Trail Records</span>
            </div>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">Spatie Activitylog v5</span>
        </div>
        <div class="table-responsive">
            <table class="table table-erp align-middle">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>User / Operator</th>
                        <th>Action Performed</th>
                        <th>Event Type</th>
                        <th>IP &amp; Properties</th>
                        <th class="text-end">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $log)
                        <tr>
                            <td>
                                <div class="fw-semibold text-nowrap">{{ $log['created_at'] }}</div>
                                <span class="text-muted small" style="font-size: 0.72rem;">{{ $log['diff_for_humans'] }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $log['avatar'] }}" alt="{{ $log['causer'] }}" class="rounded-circle" style="width: 28px; height: 28px; object-fit: cover;">
                                    <div>
                                        <div class="fw-semibold text-nowrap">{{ $log['causer'] }}</div>
                                        <span class="text-muted small font-monospace" style="font-size: 0.7rem;">{{ $log['role'] }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-medium">{{ $log['description'] }}</div>
                                @if(!empty($log['subject']))
                                    <span class="badge bg-light text-dark border font-monospace" style="font-size: 0.7rem;">Subject: {{ $log['subject'] }}</span>
                                @endif
                            </td>
                            <td>
                                @if($log['event'] === 'created' || $log['event'] === 'bootstrap')
                                    <span class="badge-erp success"><span class="badge-erp-dot"></span> {{ strtoupper($log['event']) }}</span>
                                @elseif($log['event'] === 'updated' || $log['event'] === 'download')
                                    <span class="badge-erp info"><span class="badge-erp-dot"></span> {{ strtoupper($log['event']) }}</span>
                                @elseif($log['event'] === 'login')
                                    <span class="badge-erp primary"><span class="badge-erp-dot"></span> {{ strtoupper($log['event']) }}</span>
                                @else
                                    <span class="badge-erp warning"><span class="badge-erp-dot"></span> {{ strtoupper($log['event']) }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="font-monospace small text-muted"><i class="bi bi-globe2 me-1"></i>{{ $log['ip'] }}</span>
                            </td>
                            <td class="text-end">
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill small px-2 py-0.5">
                                    <i class="bi bi-shield-check me-1"></i> Verified
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                No activity logs recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
