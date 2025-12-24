<?php

use App\Http\Controllers\Services\DigiLockerController;
use Illuminate\Support\Facades\Route;



Route::middleware(['client.service:digilocker'])->prefix('digilocker')->group(function () {

    Route::controller(DigiLockerController::class)->group(function () {
        Route::post('/initiate', 'initiate');
        Route::get('/check-status/{verification_id}', 'check_status');
    });
});
