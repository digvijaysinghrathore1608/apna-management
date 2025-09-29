<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Finance\BranchController;
use App\Http\Controllers\Finance\CustomerController;
use App\Http\Controllers\Finance\GroupController;
use App\Http\Controllers\Finance\LoanApplicationController;
use App\Http\Controllers\Finance\SchemaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'can:microfinance'])
    ->prefix('microfinance')
    ->name('microfinance.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::middleware(['canAny:microfinance.admin'])->group(function () {
            Route::resource('customers', CustomerController::class)->except(['show']);
            Route::resource('loanapplication', LoanApplicationController::class)->except(['show']);
            Route::resource('branch', BranchController::class)->except(['show']);
            Route::resource('groups', GroupController::class)->except(['show']);
            Route::resource('schema', SchemaController::class)->except(['show']);
        });
    });
