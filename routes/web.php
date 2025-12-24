<?php

use App\Http\Controllers\Services\DigiLockerController;
use Illuminate\Support\Facades\Route;


Route::view('', 'welcome')->name('welcome');

//digilocker routes
Route::prefix('digilocker')->group(function () {
    Route::get('digilocker/callback', [DigiLockerController::class, 'callback'])->name('digilocker.callback');
});
