<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\DashboardController;

Route::middleware(['auth', 'can:viewDashboard'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});


use App\Http\Controllers\Auth\AuthController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.attempt');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot.password');
