<?php

use App\Http\Controllers\DigiLocker\DigiLockerController;
use Illuminate\Support\Facades\Route;



// Route::middleware(['client.service:digilocker'])->prefix('digilocker')->group(function () {
Route::prefix('digilocker')->group(function () {

    Route::controller(DigiLockerController::class)->group(function () {
        Route::post('/initiate', 'initiate');
        Route::get('/check-status/{verification_id}', 'check_status');
        Route::post('/fetch-document', 'fetch_document');

        Route::get('/callback', [DigiLockerController::class, 'callback'])->name('digilocker.callback');
    });
});
