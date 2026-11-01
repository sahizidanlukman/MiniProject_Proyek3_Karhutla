<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FireReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('reports.index');
});

// Route Guest (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Route Terautentikasi (Sudah Login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Endpoint Asynchronous fetch() informasi wilayah 
    Route::get('/areas/{area}/summary', [AreaController::class, 'summary'])->name('areas.summary');
    Route::get('/reports/recap', [FireReportController::class, 'recap'])
        ->middleware('role:relawan,koordinator')
        ->name('reports.recap');

    // Fitur buat & edit laporan khusus Pelapor (AC-02, AC-05, AC-07)
    Route::middleware('role:pelapor')->group(function () {
        Route::get('/reports/create', [FireReportController::class, 'create'])->name('reports.create');
        Route::post('/reports', [FireReportController::class, 'store'])->name('reports.store');
        Route::get('/reports/{report}/edit', [FireReportController::class, 'edit'])->name('reports.edit');
        Route::put('/reports/{report}', [FireReportController::class, 'update'])->name('reports.update');
    });

    // Daftar & Detail Laporan 
    Route::get('/reports', [FireReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [FireReportController::class, 'show'])->name('reports.show');

    // Aksi Peninjauan oleh Relawan Pemantau atau Koordinator 
    Route::patch('/reports/{report}/review', [FireReportController::class, 'review'])
        ->middleware('role:relawan')
        ->name('reports.review');

    // Aksi Penerusan & Penyelesaian khusus Koordinator Relawan 
    Route::patch('/reports/{report}/coordinate', [FireReportController::class, 'coordinate'])
        ->middleware('role:koordinator')
        ->name('reports.coordinate');
});