<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Finance\BranchController;
use App\Http\Controllers\Finance\CustomerController;
use App\Http\Controllers\Finance\ExpensesController;
use App\Http\Controllers\Finance\FamilyController;
use App\Http\Controllers\Finance\GroupController;
use App\Http\Controllers\Finance\LoanApplicationController;
use App\Http\Controllers\Finance\SchemaController;
use App\Http\Controllers\Finance\WitnessController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'can:microfinance'])
    ->prefix('microfinance')
    ->name('microfinance.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::middleware(['canAny:microfinance.admin'])->group(function () {
            Route::resource('customers', CustomerController::class)->except(['show']);
            Route::group(['prefix' => 'customers', 'as' => 'customers.', 'controller' => CustomerController::class], function () {
                Route::post('generate-loan/{id}', 'generate_loan')->name('generate_loan');
            });


            Route::resource('branch', BranchController::class)->except(['show']);
            Route::resource('groups', GroupController::class)->except(['show']);
            Route::resource('schema', SchemaController::class)->except(['show']);


            Route::resource('loanapplication', LoanApplicationController::class)->except(['show']);
            Route::prefix('loanapplication/edit')->as('loanapplication.edit.')->controller(LoanApplicationController::class)->group(function () {
                Route::get('{id}/step1', 'edit')->name('step1');
                Route::get('{id}/step2', 'edit_step2')->name('step2');
                Route::get('{id}/step3', 'edit_step3')->name('step3');
                Route::get('{id}/step4', 'edit_step4')->name('step4');

                Route::patch('{id}/step1', 'update')->name('step1');
            });
            Route::prefix('customers')
                ->as('customers.')
                ->group(function () {
                    Route::resource('family', FamilyController::class)
                        ->names('family');
                });

            Route::prefix('loanapplication')
                ->as('loanapplication.')
                ->group(function () {
                    Route::resource('expenses', ExpensesController::class)
                        ->names('expenses');
                });
            Route::prefix('loanapplication')
                ->as('loanapplication.')
                ->group(function () {
                    Route::resource('witness', WitnessController::class)
                        ->names('witness');
                });
        });
    });
