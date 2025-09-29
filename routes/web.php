<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\DashboardController;

Route::middleware(['auth'])->group(function () {
    Route::view('', 'welcome')->name('welcome');
});
