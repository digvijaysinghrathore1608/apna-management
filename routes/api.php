<?php

use App\Http\Controllers\Temp\TempController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json(['message' => 'API working fine!']);
});

Route::apiResource('employees', TempController::class);