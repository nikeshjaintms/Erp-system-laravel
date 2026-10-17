<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Redirect root to Login or Admin
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Panel Routes using Custom Bootstrap Theme
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard.alias');
    Route::get('/inventory', [AdminController::class, 'inventory'])->name('inventory');
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{orderNo?}/download-pdf', [AdminController::class, 'downloadInvoice'])->name('orders.pdf.download');
    Route::get('/orders/{orderNo?}/stream-pdf', [AdminController::class, 'streamInvoice'])->name('orders.pdf.stream');
    Route::get('/customers', [AdminController::class, 'customers'])->name('customers');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::get('/activity-log', [AdminController::class, 'activityLog'])->name('activity-log');
    Route::get('/roles', [AdminController::class, 'roles'])->name('roles');
});
