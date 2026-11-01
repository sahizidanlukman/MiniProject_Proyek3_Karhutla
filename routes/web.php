<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FireReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('reports.index');
});

// Route Guest (Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Route Terautentikasi (Sudah Login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Rute Laporan
    Route::get('/reports', [FireReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [FireReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [FireReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}', [FireReportController::class, 'show'])->name('reports.show');

    // Rute Edit & Update khusus Pelapor
    Route::get('/reports/{report}/edit', [FireReportController::class, 'edit'])->name('reports.edit');
    Route::put('/reports/{report}', [FireReportController::class, 'update'])->name('reports.update');
});