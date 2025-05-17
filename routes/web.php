<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\client\ClientController;
use App\Http\Controllers\admin\AdminController;

// client 

Route::get('/', [ClientController::class, 'index'])->name('client.index');

Route::post('/process-payment', [ClientController::class, 'processPayment'])->name('payment.process');

Route::get('/payment-callback', [ClientController::class, 'paymentCallback'])->name('payment.callback');

// admin

// Routes for guests only (not logged in)
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminController::class, 'loginPage'])->name('login'); 
    Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
});

// Routes for authenticated users only
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
    Route::get('/admin/logout', function () { 
    return redirect()->route('admin.dashboard'); 
});
});
