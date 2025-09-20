<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;

Route::controller(AuthController::class)->group(function () {
    Route::get('login', 'showLoginForm')->name('login');
    Route::post('login', 'login')->name('login.attempt');
    Route::get('register', 'showRegisterForm')->name('register');
    Route::post('register', 'register')->name('register.attempt');
    Route::post('logout', 'logout')->name('logout');
    Route::get('forgot-password', 'showForgotPasswordForm')->name('forgot.password');
});
