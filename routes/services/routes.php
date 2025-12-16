
<?php

use App\Http\Controllers\Services\EmailController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'can:services'])
    ->prefix('services')
    ->name('services.')
    ->group(function () {
        //Email service
        Route::resource('email', EmailController::class);
    });
