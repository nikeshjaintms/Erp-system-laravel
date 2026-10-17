@extends('layouts.admin')

@section('title', 'ERP System Settings')
@section('page_title', 'ERP System Settings & Configuration')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Administration</a></li>
    <li class="breadcrumb-item active" aria-current="page">Settings</li>
@endsection

@section('actions')
    <button type="submit" form="erpSettingsForm" class="btn btn-primary-erp btn-sm d-flex align-items-center gap-2">
        <i class="bi bi-check-lg"></i>
        <span>Save Changes</span>
    </button>
@endsection

@section('content')
    <div class="row g-4">
        <!-- Settings Navigation Pills -->
        <div class="col-12 col-md-3">
            <div class="card-erp p-2">
                <div class="nav flex-column nav-pills" id="settingsTab" role="tablist">
                    <button class="nav-link text-start active d-flex align-items-center gap-2 py-2.5 px-3 rounded-3" id="company-tab" data-bs-toggle="pill" data-bs-target="#company" type="button" role="tab">
                        <i class="bi bi-buildings"></i> Company Profile
                    </button>
                    <button class="nav-link text-start d-flex align-items-center gap-2 py-2.5 px-3 rounded-3" id="erp-tab" data-bs-toggle="pill" data-bs-target="#erpConfig" type="button" role="tab">
                        <i class="bi bi-sliders2"></i> ERP Preferences
                    </button>
                    <button class="nav-link text-start d-flex align-items-center gap-2 py-2.5 px-3 rounded-3" id="tax-tab" data-bs-toggle="pill" data-bs-target="#tax" type="button" role="tab">
                        <i class="bi bi-calculator"></i> Taxes & Fiscal Year
                    </button>
                    <button class="nav-link text-start d-flex align-items-center gap-2 py-2.5 px-3 rounded-3" id="mail-tab" data-bs-toggle="pill" data-bs-target="#mail" type="button" role="tab">
                        <i class="bi bi-envelope-at"></i> Email & Notifications
                    </button>
                    <button class="nav-link text-start d-flex align-items-center gap-2 py-2.5 px-3 rounded-3" id="security-tab" data-bs-toggle="pill" data-bs-target="#security" type="button" role="tab">
                        <i class="bi bi-shield-lock"></i> Security & Backup
                    </button>
                </div>
            </div>
        </div>

        <!-- Settings Content Forms -->
        <div class="col-12 col-md-9">
            <div class="tab-content" id="settingsTabContent">
                <!-- Company Profile Tab -->
                <div class="tab-pane fade show active" id="company" role="tabpanel">
                    <div class="card-erp">
                        <div class="card-header">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-building text-primary"></i>
                                <span>Company & Legal Entity Details</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="erpSettingsForm" action="#" method="POST" onsubmit="event.preventDefault(); alert('System settings saved successfully (Theme Demo)');">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-semibold">Legal Enterprise Name</label>
                                        <input type="text" class="form-control" value="Apex Global Solutions Inc.">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-semibold">Business Registration / CIN</label>
                                        <input type="text" class="form-control" value="US-DEL-2024-998812">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-semibold">Primary Business Email</label>
                                        <input type="email" class="form-control" value="finance@apexsolutions.com">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-semibold">Contact Phone Number</label>
                                        <input type="text" class="form-control" value="+1 (555) 902-1200">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-semibold">Headquarters Address</label>
                                        <input type="text" class="form-control" value="100 Enterprise Way, Suite 400, Chicago, IL 60601">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label small fw-semibold">City</label>
                                        <input type="text" class="form-control" value="Chicago">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label small fw-semibold">State / Region</label>
                                        <input type="text" class="form-control" value="Illinois">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label small fw-semibold">Postal / ZIP Code</label>
                                        <input type="text" class="form-control" value="60601">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- ERP Preferences Tab -->
                <div class="tab-pane fade" id="erpConfig" role="tabpanel">
                    <div class="card-erp">
                        <div class="card-header">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-sliders2 text-primary"></i>
                                <span>ERP Regional & Operational Preferences</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-semibold">Base Operating Currency</label>
                                    <select class="form-select">
                                        <option value="USD" selected>USD ($) - United States Dollar</option>
                                        <option value="EUR">EUR (€) - Euro</option>
                                        <option value="GBP">GBP (£) - British Pound</option>
                                        <option value="INR">INR (₹) - Indian Rupee</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-semibold">Inventory Valuation Method</label>
                                    <select class="form-select">
                                        <option value="FIFO" selected>FIFO (First In, First Out)</option>
                                        <option value="LIFO">LIFO (Last In, First Out)</option>
                                        <option value="WAC">Weighted Average Cost (WAC)</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-semibold">Date Format</label>
                                    <select class="form-select">
                                        <option selected>YYYY-MM-DD (2026-10-17)</option>
                                        <option>DD/MM/YYYY (17/10/2026)</option>
                                        <option>MM/DD/YYYY (10/17/2026)</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-semibold">Timezone</label>
                                    <select class="form-select">
                                        <option selected>UTC -05:00 America/Chicago</option>
                                        <option>UTC +00:00 Europe/London</option>
                                        <option>UTC +05:30 Asia/Kolkata</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Taxes & Fiscal Tab -->
                <div class="tab-pane fade" id="tax" role="tabpanel">
                    <div class="card-erp">
                        <div class="card-header">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-calculator text-primary"></i>
                                <span>Tax Configuration & Fiscal Calendar</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-semibold">Federal Tax ID / VAT Number</label>
                                    <input type="text" class="form-control" value="TAX-990-234-110">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-semibold">Standard Sales Tax (%)</label>
                                    <input type="number" class="form-control" value="9.00" step="0.01">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-semibold">Fiscal Year Start Month</label>
                                    <select class="form-select">
                                        <option selected>April</option>
                                        <option>January</option>
                                        <option>July</option>
                                        <option>October</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Email & Notifications Tab -->
                <div class="tab-pane fade" id="mail" role="tabpanel">
                    <div class="card-erp">
                        <div class="card-header">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-envelope-at text-primary"></i>
                                <span>SMTP Gateway & Email Dispatch</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12 col-md-8">
                                    <label class="form-label small fw-semibold">SMTP Host</label>
                                    <input type="text" class="form-control" value="smtp.mailgun.org">
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label small fw-semibold">SMTP Port</label>
                                    <input type="number" class="form-control" value="587">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-semibold">Sender Email Address</label>
                                    <input type="email" class="form-control" value="notifications@apexerp.com">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-semibold">Sender Display Name</label>
                                    <input type="text" class="form-control" value="Apex ERP System">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security & Backup Tab -->
                <div class="tab-pane fade" id="security" role="tabpanel">
                    <div class="card-erp">
                        <div class="card-header">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-shield-lock text-primary"></i>
                                <span>Database Backups & Security Policies</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center p-3 border rounded-3 mb-3">
                                <div>
                                    <div class="fw-bold">Automated Daily Database Snapshots</div>
                                    <div class="text-muted small">Daily automated backup taken at 02:00 AM UTC. Last snapshot: Today 02:00 AM.</div>
                                </div>
                                <button class="btn btn-outline-erp btn-sm">Take Backup Now</button>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3 border rounded-3">
                                <div>
                                    <div class="fw-bold">Two-Factor Authentication (2FA) for Admins</div>
                                    <div class="text-muted small">Enforce OTP/Authenticator for all managerial and accountant roles.</div>
                                </div>
                                <div class="form-check form-switch fs-5">
                                    <input class="form-check-input" type="checkbox" role="switch" checked>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
