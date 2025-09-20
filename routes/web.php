<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\DashboardController;

Route::middleware(['auth', 'can:viewDashboard'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});
