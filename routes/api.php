<?php

use App\Http\Controllers\Auth;
use App\Http\Controllers\DiskusiController;
use App\Http\Controllers\JadwalBimbingan;
use App\Http\Controllers\ListBimbinganController;
use App\Http\Controllers\NaskahController;
use App\Http\Controllers\PenjadwalanController;
use App\Http\Controllers\Riwayat;
use App\Http\Controllers\TrackingProgresController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('cors')->group(function () {
    Route::post('/api-register', [Auth::class, 'register_proses']);
    Route::post('/api-login', [Auth::class, 'do_login']);

    Route::middleware('auth:sanctum')->group(function () {
        // Admin Routes
        Route::middleware('role:dosen')->group(function () {
            Route::get('/api-bimbingan', [ListBimbinganController::class, 'get_dosen']);

            Route::get('/api-riwayat/{params}', [Riwayat::class, 'get']);

            Route::post('/api-penjadwalan-add', [PenjadwalanController::class, 'store']);

            Route::get('/api-jadwalbimbingan-dosen', [JadwalBimbingan::class, 'get']);

            Route::get('/api-naskah', [NaskahController::class, 'get_naskah_dosen']);

            Route::get('/api-tracking-dosen', [TrackingProgresController::class, 'get']);
            Route::post('/api-tracking-add', [TrackingProgresController::class, 'store']);

            Route::post('/api-diskusi-dosen', [DiskusiController::class, 'store']);
            Route::get('/api-mahasiswa', [DiskusiController::class, 'get']);
        });

        // Mahasiswa Routes
        Route::middleware('role:mahasiswa')->group(function () {
            Route::get('/api-jadwalbimbingan-mahasiswa', [JadwalBimbingan::class, 'get_mahasiswa']);

            Route::get('/api-riwayat', [Riwayat::class, 'get_mahasiswa']);

            Route::get('/api-diskusi-mahasiswa', [DiskusiController::class, 'get']);

            Route::get('/api-dosen', [NaskahController::class, 'get_dosen']);
            Route::post('/api-naskah-add', [NaskahController::class, 'store']);

            Route::get('/api-tracking-mahasiswa', [TrackingProgresController::class, 'get_progres']);
        });

        Route::get('/api-logout', [Auth::class, 'revoke']);
    });
});
