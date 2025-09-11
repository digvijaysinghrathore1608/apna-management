<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.index');
})->name('dashboard');

Route::get('/login', function () {
    return view('auth.pages.index');
})->name('login');
Route::get('/register', function () {
    return view('auth.pages.register');
})->name('register');
Route::get('/forgot-password', function () {
    return view('auth.pages.forgot_password');
})->name('forgot.password');
