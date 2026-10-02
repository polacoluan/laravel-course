<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {

    Route::get('login', fn () => view('auth.login'))->name('login');
    Route::post('signin', [LoginController::class, 'login'])->name('signin');

    Route::get('register', fn () => view('auth.register'))->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', LogoutController::class)->name('logout');
    Route::get('/', fn () => view('welcome'))->name('dashboard');
});
