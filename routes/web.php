<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Auth routes (hanya bisa diakses guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
});

// Logout hanya untuk user yang sudah login
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Protected routes with role middleware
Route::middleware(['auth'])->group(function () {
    // Routes accessible by all authenticated users
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Admin only routes
    Route::middleware(['role:ADMIN'])->group(function () {
        // Add admin specific routes here
    });

    // Contributor routes
    Route::middleware(['role:ADMIN,CONTRIBUTOR'])->group(function () {
        // Add contributor specific routes here
    });
});
