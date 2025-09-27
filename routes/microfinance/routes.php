<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Finance\CustomerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'can:microfinance'])->prefix('microfinance')->name('microfinance.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['canAny:microfinance.default'])
        ->prefix('customers')
        ->as('customers.')
        ->group(function () {
            Route::controller(CustomerController::class)->group(function () {
                Route::get('', 'index')->name('index');
                Route::get('create', 'create')->name('create');
                Route::post('store', 'store')->name('store');
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('edit/{id}', 'update')->name('edit');
            });
        });
});
