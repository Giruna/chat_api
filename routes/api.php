<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\VerifyEmailController;

Route::post('/register', [RegisterController::class, 'register']);

// Email verification link (signed)
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
    ->name('verification.verify');

// Resend verification (accepts email) — rate limited in controller
Route::post('/email/verification-notification', [VerifyEmailController::class, 'resend']);
