<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\client\ClientController;

// client 

Route::get('/', [ClientController::class, 'index'])->name('client.index');

Route::post('/process-payment', [ClientController::class, 'processPayment'])->name('payment.process');

Route::get('/payment-callback', [ClientController::class, 'paymentCallback'])->name('payment.callback');

// admin


