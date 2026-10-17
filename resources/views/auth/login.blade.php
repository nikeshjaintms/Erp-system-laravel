<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sign In | APEX ERP</title>

    <!-- Local Vendor Stylesheets (Offline & Local) -->
    <link rel="stylesheet" href="{{ asset('admin-assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/vendor/bootstrap-icons/bootstrap-icons.min.css') }}">

    <!-- Local Custom ERP Theme CSS -->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/admin-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/login-theme.css') }}">
</head>
<body>
    <div class="login-viewport">
        <!-- Top-Right Theme Toggle Button -->
        <button type="button" class="btn-icon theme-corner-btn" id="themeToggleBtn" title="Toggle Light / Dark Mode">
            <i class="bi bi-sun-fill text-warning" id="themeIcon"></i>
        </button>

        <!-- Centered Single Login Card -->
        <div class="auth-card-clean">
            <!-- Brand Logo & Title -->
            <div class="text-center mb-4">
                <div class="brand-logo-badge mb-2">
                    <i class="bi bi-grid-1x2-fill"></i>
                </div>
                <h4 class="brand-logo-text">APEX ERP</h4>
            </div>

            <!-- Header -->
            <div class="text-center mb-4">
                <h3 class="fw-bold mb-1" style="letter-spacing: -0.02em;">Welcome Back</h3>
                <p class="text-muted small mb-0">Sign in to your account to continue.</p>
            </div>

            <!-- Status Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 p-3 mb-3 rounded-3" role="alert">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    <div class="small fw-semibold">{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 p-3 mb-3 rounded-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                    <div class="small fw-semibold">{{ $errors->first() }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Sign In Form -->
            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <!-- Email Input -->
                <div class="mb-3">
                    <label for="email" class="form-label-human">Email address</label>
                    <div class="input-group-human">
                        <input type="email" 
                               class="input-human @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', 'admin@apexerp.com') }}" 
                               placeholder="name@company.com" 
                               required 
                               autocomplete="email" 
                               autofocus>
                        <i class="bi bi-envelope input-lead-icon"></i>
                    </div>
                </div>

                <!-- Password Input -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="password" class="form-label-human mb-0">Password</label>
                        <a href="#" class="small text-primary text-decoration-none" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
                            Forgot password?
                        </a>
                    </div>
                    <div class="input-group-human">
                        <input type="password" 
                               class="input-human @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               value="password"
                               placeholder="Enter your password" 
                               required 
                               autocomplete="current-password">
                        <i class="bi bi-lock input-lead-icon"></i>
                        <button type="button" class="password-eye-btn" id="togglePasswordBtn" title="Show/Hide Password">
                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me Checkbox -->
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                    <label class="form-check-label small text-muted user-select-none" for="remember">
                        Keep me signed in on this device
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit-human mb-3">
                    <span>Sign In to ERP Portal</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <!-- Support Note -->
            <div class="text-center pt-2">
                <span class="small text-muted" style="font-size: 0.8rem;">
                    Need help? Contact <a href="mailto:support@company.com" class="text-decoration-none">IT Support</a>
                </span>
            </div>
        </div>

        <!-- Footer Copyright -->
        <div class="login-footer-copy">
            &copy; {{ date('Y') }} APEX ERP Systems. All rights reserved.
        </div>
    </div>

    <!-- Password Recovery Modal -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content card-erp border-0 shadow-lg">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold" id="forgotPasswordModalLabel">
                        Password Reset
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">
                        Enter your corporate email address and we'll send you a password reset link.
                    </p>
                    <div class="mb-3">
                        <label class="form-label-human">Email address</label>
                        <input type="email" class="form-control" placeholder="name@company.com" value="admin@apexerp.com">
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary-erp" onclick="alert('Password reset instructions sent.');" data-bs-dismiss="modal">
                        Send Reset Link
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Local Vendor Scripts (Offline & Local) -->
    <script src="{{ asset('admin-assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/admin-theme.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Password Show/Hide Toggle
            const togglePasswordBtn = document.getElementById('togglePasswordBtn');
            const passwordInput = document.getElementById('password');
            const togglePasswordIcon = document.getElementById('togglePasswordIcon');

            if (togglePasswordBtn && passwordInput) {
                togglePasswordBtn.addEventListener('click', function () {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    togglePasswordIcon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
                });
            }
        });
    </script>
</body>
</html>
