<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\JenisSuratController;
use App\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| Web Routes - Project Administrasi Sekolah (LKPD Sumatif XI RPL)
|--------------------------------------------------------------------------
*/

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (Required Login)
Route::middleware('auth')->group(function () {

    // Dashboard (All roles: Admin, Guru, Siswa)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Pengajuan Resource Routes
    Route::resource('pengajuan', PengajuanController::class);

    // Action Approval for Staff / Guru & Admin
    Route::middleware('role:admin,guru')->group(function () {
        Route::patch('/pengajuan/{pengajuan}/status', [PengajuanController::class, 'updateStatus'])->name('pengajuan.update-status');
    });

    // Admin Only Management Routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('siswa', SiswaController::class)->except(['show']);
        Route::resource('jenis-surat', JenisSuratController::class)->except(['show', 'create', 'edit']);
        Route::resource('users', UserController::class)->except(['show', 'create', 'edit']);
    });
    Route::patch('/siswa/{siswa}/toggle-status', [SiswaController::class, 'toggleStatus'])
    ->name('siswa.toggle-status');
});
