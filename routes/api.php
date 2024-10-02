<?php

use App\Http\Controllers\Auth;
use App\Http\Controllers\ChatController;
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

            Route::get('/api-riwayat-dosen', [Riwayat::class, 'get']);
            Route::get('/api-detail-riwayat/{params}', [Riwayat::class, 'detail_riwayat_dosen']);

            Route::post('/api-penjadwalan-add', [PenjadwalanController::class, 'store']);

            Route::get('/api-jadwalbimbingan-dosen', [JadwalBimbingan::class, 'get']);

            Route::get('/api-naskah', [NaskahController::class, 'get_naskah_dosen']);
            Route::get('/api-detail-naskah/{params}', [NaskahController::class, 'detail_naskah']);
            Route::get('/api-update-naskah/{params}', [NaskahController::class, 'update_naskah']);
            Route::post('/api-refisi-naskah/{params}', [NaskahController::class, 'update']);

            Route::get('/api-tracking-dosen', [TrackingProgresController::class, 'get']);
            Route::post('/api-tracking-add', [TrackingProgresController::class, 'store']);

            Route::post('/api-diskusi-dosen', [DiskusiController::class, 'store']);

            Route::get('/api-get-user-chat-dosen', [ChatController::class, 'get_user_chat_dosen']);
            Route::get('/api-get-detail-user-chat-dosen/{params}', [ChatController::class, 'getDetailDsn']);
            Route::get('/api-get-chat-dosen/{uuid_receiver}', [ChatController::class, 'getChat']);
            Route::post('/api-chat-add-dosen', [ChatController::class, 'send']);
        });

        // Mahasiswa Routes
        Route::middleware('role:mahasiswa')->group(function () {
            Route::get('/api-bimbingan-mahasiswa', [ListBimbinganController::class, 'get_mahasiswa']);

            Route::get('/api-jadwalbimbingan-mahasiswa', [JadwalBimbingan::class, 'get_mahasiswa']);

            Route::get('/api-riwayat-mahasiswa', [Riwayat::class, 'get_mahasiswa']);
            Route::get('/api-riwayat-mahasiswa/{params}', [Riwayat::class, 'detail_riwayat_mahasiswa']);

            Route::get('/api-diskusi-mahasiswa', [DiskusiController::class, 'get_diskusi']);
            Route::get('/api-detail-diskusi/{params}', [DiskusiController::class, 'detail_diskusi']);

            Route::get('/api-dosen', [NaskahController::class, 'get_dosen']);
            Route::post('/api-naskah-add', [NaskahController::class, 'store']);

            Route::get('/api-tracking-mahasiswa', [TrackingProgresController::class, 'get_progres']);
            Route::get('/api-detail-tacking/{params}', [TrackingProgresController::class, 'detail_tracking']);

            Route::get('/api-get-user-chat-mahasiswa', [ChatController::class, 'get_user_chat_mahasiswa']);
            Route::get('/api-get-detail-user-chat-mahasiswa/{params}', [ChatController::class, 'getDetailMhs']);
            Route::get('/api-get-chat-mahasiswa/{uuid_receiver}', [ChatController::class, 'getChat']);
            Route::post('/api-chat-add-mahasiswa', [ChatController::class, 'send']);

            Route::get('/api-refisi-naskah-mahasiswa', [NaskahController::class, 'get_naskah_mahasiswa_refisi']);
            Route::get('/api-detail-refisi-naskah-mahasiswa/{params}', [NaskahController::class, 'detail_naskah_mahasiswa_refisi']);
            Route::get('/api-update-refisi-naskah-mahasiswa/{params}', [NaskahController::class, 'update_refisi_mahasiswa']);
        });

        Route::get('/api-logout', [Auth::class, 'revoke']);
    });
});
