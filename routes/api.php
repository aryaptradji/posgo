<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransController;

// Payment Gateway
Route::post('/midtrans/callback', [MidtransController::class, 'handleCallback']);
