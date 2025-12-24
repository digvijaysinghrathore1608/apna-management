<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientCredentialController;
use App\Http\Controllers\Services\EmailController;
use App\Http\Controllers\Temp\TempController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json(['message' => 'API working fine!']);
});

Route::apiResource('employees', TempController::class);

Route::get('/ses-test', function () {
    Mail::raw('AWS SES SMTP is working successfully 🚀', function ($message) {
        $message->to('dsrathore9549@gmail.com')
            ->subject('SES SMTP Test');
    });

    return 'Email sent';
});

Route::post('/services/email/send', [EmailController::class, 'send_email']);



Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'superadmin'])->prefix('client-credentials')->controller(ClientCredentialController::class)->group(function () {
    Route::post('/', 'store');        // Create
    Route::get('/', 'index');         // Get all
    Route::get('{id}', 'show');       // Get one
    Route::put('{id}', 'update');     // Update
    Route::delete('{id}', 'destroy'); // Delete
});
