<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\HomeController;

// Group of routes for authentication and password reset
Route::group([], function () {
    // Route to display the two-factor authentication form
    Route::get('/2fa', [TwoFactorController::class, 'showTwoFactorForm'])->name('2fa.form');
    
    // Route to verify the two-factor authentication code
    Route::post('/2fa/verify', [TwoFactorController::class, 'verifyTwoFactor'])->name('2fa.verify');

    // Route to display the login form
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    
    // Route to process user login
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    
    // Route to log out the user
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Route to display the registration form
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    
    // Route to process user registration
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

    // Route to display the password reset link request form
    Route::get('/password/reset', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    
    // Route to send the password reset link via email
    Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    
    // Route to display the password reset form using the token
    Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    
    // Route to process the password reset
    Route::post('/password/reset', [PasswordResetController::class, 'reset'])->name('password.update');

    // Route for the user's home page (after login)
    Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware(['auth','2fa']);
});
