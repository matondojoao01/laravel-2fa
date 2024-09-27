<?php

use Illuminate\Support\Facades\Route;

// Grouping routes under TwoFactorAuth namespace
Route::namespace('Matondo\app\Http\Controllers')->group(function () {
    Route::get('/2fa', 'TwoFactorController@showTwoFactorForm')->name('2fa.form');
    Route::post('/2fa/verify', 'TwoFactorController@verifyTwoFactor')->name('2fa.verify');
});

// Grouping routes under Auth namespace
Route::namespace('Matondo\app\Http\Controllers\Auth')->group(function () {
    Route::get('/login', 'LoginController@showLoginForm')->name('login.form');
    Route::post('/login', 'LoginController@login')->name('login');
    Route::post('/logout', 'LoginController@logout')->name('logout');
});
