<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Auth\LoginController;

Route::group([], function () {
    Route::get('/2fa', [TwoFactorController::class, 'showTwoFactorForm'])->name('2fa.form');
    Route::post('/2fa/verify', [TwoFactorController::class, 'verifyTwoFactor'])->name('2fa.verify');

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
