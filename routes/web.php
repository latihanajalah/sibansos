<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JenisBantuanController;
use App\Http\Controllers\PenerimaController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\SurveiController;
use App\Http\Controllers\VerifikasiController;
use App\Http\Controllers\PersetujuanController;
use App\Http\Controllers\PenyaluranController;
use Illuminate\Support\Facades\Route;

// ─── Public Routes (no auth required) ─────────────────────────────────────
Route::get('/', [LandingPageController::class, 'index'])->name('landing');
Route::get('/cek-status', [LandingPageController::class, 'cekStatus'])->name('cek-status');
Route::get('/status', [LandingPageController::class, 'cekStatus'])->name('status');

Route::get('/dashboard', function () {
    $redirectRoute = match (auth()->user()->role) {
        'super_admin' => 'super_admin.dashboard',
        'admin'       => 'admin.dashboard',
        'petugas'     => 'petugas.dashboard',
        'pimpinan'    => 'pimpinan.dashboard',
        default       => 'dashboard',
    };
    return redirect()->route($redirectRoute);
})->middleware(['auth', 'verified'])->name('dashboard');

// Super Admin Route Group
Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('super_admin.dashboard');
    Route::resource('users', UserController::class);
});

// Admin Route Group
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
});

// Petugas Route Group
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('petugas.dashboard');
});

// Pimpinan Route Group
Route::middleware(['auth', 'role:pimpinan'])->prefix('pimpinan')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('pimpinan.dashboard');
});

// Super Admin & Admin shared — Jenis Bantuan
Route::middleware(['auth', 'role:super_admin,admin'])
    ->prefix('master')
    ->group(function () {
        Route::resource('jenis-bantuan', JenisBantuanController::class);
        Route::resource('penerima', PenerimaController::class);
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('pengajuan', PengajuanController::class);
    Route::resource('survei', SurveiController::class)->except(['destroy']);
    Route::delete('/dokumen/{dokumen}', [SurveiController::class, 'destroyDokumen'])->name('dokumen.destroy');
});

Route::middleware(['auth', 'role:super_admin,admin'])
    ->prefix('verifikasi')
    ->name('verifikasi.')
    ->group(function () {
        Route::get('/', [VerifikasiController::class, 'index'])->name('index');
        Route::get('/{pengajuan}', [VerifikasiController::class, 'show'])->name('show');
        Route::post('/{pengajuan}', [VerifikasiController::class, 'verify'])->name('verify');
    });

Route::middleware(['auth', 'role:pimpinan,super_admin'])
    ->prefix('persetujuan')
    ->name('persetujuan.')
    ->group(function () {
        Route::get('/', [PersetujuanController::class, 'index'])->name('index');
        Route::get('/{pengajuan}', [PersetujuanController::class, 'show'])->name('show');
        Route::post('/{pengajuan}', [PersetujuanController::class, 'approve'])->name('approve');
    });

// Penyaluran routes – index/show open to all roles; create/store restricted by middleware AND FormRequest
Route::middleware(['auth', 'role:super_admin,admin,petugas'])->group(function () {
    Route::get('penyaluran/create', [PenyaluranController::class, 'create'])->name('penyaluran.create');
    Route::post('penyaluran', [PenyaluranController::class, 'store'])->name('penyaluran.store');
});

Route::middleware(['auth', 'role:super_admin,admin,petugas,pimpinan'])->group(function () {
    Route::get('penyaluran', [PenyaluranController::class, 'index'])->name('penyaluran.index');
    Route::get('penyaluran/{penyaluran}', [PenyaluranController::class, 'show'])->name('penyaluran.show');
});

// Laporan & Statistik – super_admin, admin, pimpinan
Route::middleware(['auth', 'role:super_admin,admin,pimpinan'])
    ->prefix('laporan')
    ->name('laporan.')
    ->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/pengajuan', [LaporanController::class, 'pengajuan'])->name('pengajuan');
        Route::get('/penyaluran', [LaporanController::class, 'penyaluran'])->name('penyaluran');
        Route::get('/export-pdf/{jenis}', [LaporanController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/export-excel/{jenis}', [LaporanController::class, 'exportExcel'])->name('export.excel');
    });

require __DIR__.'/auth.php';
