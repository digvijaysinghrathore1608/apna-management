<?php

use App\Http\Controllers\Invoice\InvoiceController;
use App\Http\Controllers\Invoice\InvoiceTemplateController;
use Illuminate\Support\Facades\Route;



Route::middleware(['client.service:invoice'])->prefix('invoice')->group(function () {
    Route::controller(InvoiceTemplateController::class)->prefix('template')->group(function () {
        Route::get('/', 'index');
        Route::post('/store', 'store');
    });

    Route::controller(InvoiceController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{invoice_number}', 'show');
        Route::post('/store', 'store');
        Route::post('/update/{invoice_number}', 'update');
    });
});
