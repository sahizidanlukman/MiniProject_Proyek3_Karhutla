<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Akses utama langsung diarahkan ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rute untuk pengguna yang belum login 
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Rute logout untuk pengguna yang sudah login
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');