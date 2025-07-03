<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ServicePlanController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // Client Management
    Route::resource('clients', ClientController::class);
    Route::get('clients/{client}/subscriptions', [ClientController::class, 'subscriptions'])->name('clients.subscriptions');
    Route::get('clients/{client}/invoices', [ClientController::class, 'invoices'])->name('clients.invoices');
    Route::get('clients/{client}/payments', [ClientController::class, 'payments'])->name('clients.payments');
    
    // Device Management
    Route::resource('devices', DeviceController::class);
    Route::post('devices/{device}/assign', [DeviceController::class, 'assign'])->name('devices.assign');
    
    // Service Plans
    Route::resource('service-plans', ServicePlanController::class)->names('service-plans');
    
    // Billing & Invoicing
    Route::resource('invoices', InvoiceController::class);
    Route::post('invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
    Route::post('invoices/{invoice}/pay', [InvoiceController::class, 'markAsPaid'])->name('invoices.pay');
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
    
    // Payments
    Route::resource('payments', PaymentController::class);
    Route::get('payments/record/{invoice?}', [PaymentController::class, 'recordPayment'])->name('payments.record');
    Route::post('payments/{payment}/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
    
    // Support Tickets
    Route::resource('tickets', TicketController::class);
    Route::post('tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');
    Route::post('tickets/{ticket}/resolve', [TicketController::class, 'resolve'])->name('tickets.resolve');
    Route::post('tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('tickets.updateStatus');
    
    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('reports/clients', [ReportController::class, 'clients'])->name('reports.clients');
    Route::get('reports/usage', [ReportController::class, 'usage'])->name('reports.usage');
    Route::get('reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');
    
    // User Management (Super Admin only)
    Route::middleware('permission:manage-users')->group(function () {
        Route::resource('users', UserController::class);
        Route::post('users/{user}/assign-role', [UserController::class, 'assignRole'])->name('users.assign-role');
        Route::post('users/{user}/revoke-role', [UserController::class, 'revokeRole'])->name('users.revoke-role');
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    });
});

/*
|--------------------------------------------------------------------------
| API Routes (for future mobile app or integrations)
|--------------------------------------------------------------------------
*/
Route::prefix('api')->middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('devices', DeviceController::class);
    Route::apiResource('invoices', InvoiceController::class);
    Route::apiResource('payments', PaymentController::class);
    Route::apiResource('tickets', TicketController::class);
});
