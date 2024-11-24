<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['namespace' => 'App\Http\Controllers'], function () {

    Route::get('/', 'Dashboard@index')->name('home.index');

    Route::group(['prefix' => 'login', 'middleware' => ['guest'], 'as' => 'login.'], function () {
        Route::get('/login-akun', 'Auth@show')->name('login-akun');
        Route::post('/login-proses', 'Auth@login_proses')->name('login-proses');

        Route::get('/register-akun', 'Auth@register_akun')->name('register-akun');
        Route::post('/register-proses', 'Auth@register_proses')->name('register-proses');
    });

    Route::group(['prefix' => 'admin', 'middleware' => ['auth'], 'as' => 'admin.'], function () {
        Route::get('/dashboard-admin', 'Dashboard@dashboard')->name('dashboard-admin');

        Route::get('/registrasi', 'Dosen@index')->name('registrasi');
        Route::get('/registrasi-get', 'Dosen@get')->name('registrasi-get');
        Route::post('/registrasi-update/{params}', 'Dosen@update')->name('registrasi-update');
        Route::post('/add-dosen', 'Dosen@add')->name('add-dosen');

        Route::get('/mahasiswa', 'Mahasiswa@index')->name('mahasiswa');
        Route::get('/mahasiswa-get', 'Mahasiswa@get')->name('mahasiswa-get');
        Route::post('/mahasiswa-update/{params}', 'Mahasiswa@update')->name('mahasiswa-update');

        Route::get('/bimbingan', 'ListBimbinganController@index')->name('bimbingan');
        Route::get('/get-bimbingan', 'ListBimbinganController@get')->name('get-bimbingan');
        Route::get('/show-bimbingan/{params}', 'ListBimbinganController@show')->name('show-bimbingan');
        Route::post('/add-bimbingan', 'ListBimbinganController@store')->name('add-bimbingan');
        Route::post('/update-bimbingan/{params}', 'ListBimbinganController@update')->name('update-bimbingan');
        Route::delete('/delete-bimbingan/{params}', 'ListBimbinganController@delete')->name('delete-bimbingan');
    });

    Route::group(['prefix' => 'dosen', 'middleware' => ['auth'], 'as' => 'dosen.'], function () {
        Route::get('/dashboard-dosen', 'Dashboard@dashboard_dosen')->name('dashboard-dosen');

        Route::get('/bimbingan', 'ListBimbinganController@dosen')->name('bimbingan');
        Route::get('/get-bimbingan', 'ListBimbinganController@get_dosen')->name('get-bimbingan');

        Route::get('/penjadwalan', 'PenjadwalanController@index')->name('penjadwalan');
        Route::get('/get-penjadwalan', 'PenjadwalanController@get')->name('get-penjadwalan');
        Route::get('/show-penjadwalan/{params}', 'PenjadwalanController@show')->name('show-penjadwalan');
        Route::post('/add-penjadwalan', 'PenjadwalanController@store')->name('add-penjadwalan');
        Route::delete('/delete-penjadwalan/{params}', 'PenjadwalanController@delete')->name('delete-penjadwalan');

        Route::get('/jadwalbimbingan', 'JadwalBimbingan@index')->name('jadwalbimbingan');
        Route::get('/get-jadwalbimbingan', 'JadwalBimbingan@get')->name('get-jadwalbimbingan');

        Route::get('/naskah', 'NaskahController@dosen')->name('naskah');
        Route::get('/get-naskah', 'NaskahController@get_naskah_dosen')->name('get-naskah');
        Route::get('/update-naskah/{params}', 'NaskahController@update_naskah')->name('update-naskah');

        Route::get('/chat-dosen', 'ChatController@dosen')->name('chat-dosen');
        Route::get('/detail-dosen/{params}', 'ChatController@detail_dosen')->name('detail-dosen');
        Route::get('/get-message/{params}', 'ChatController@getChat');
        Route::post('/send-message', 'ChatController@send');
    });

    Route::group(['prefix' => 'mahasiswa', 'middleware' => ['auth'], 'as' => 'mahasiswa.'], function () {
        Route::get('/dashboard-mahasiswa', 'Dashboard@dashboard_mahasiswa')->name('dashboard-mahasiswa');

        Route::get('/jadwalbimbingan', 'JadwalBimbingan@mahasiswa')->name('jadwalbimbingan');
        Route::get('/get-jadwalbimbingan', 'JadwalBimbingan@get_mahasiswa')->name('get-jadwalbimbingan');

        Route::get('/naskah', 'NaskahController@index')->name('naskah');
        Route::get('/get-naskah', 'NaskahController@get')->name('get-naskah');
        Route::get('/get-dosen', 'NaskahController@get_dosen')->name('get-dosen');
        Route::get('/show-naskah/{params}', 'NaskahController@show')->name('show-naskah');
        Route::post('/add-naskah', 'NaskahController@store')->name('add-naskah');
        Route::post('/update-naskah/{params}', 'NaskahController@update')->name('update-naskah');
        Route::delete('/delete-naskah/{params}', 'NaskahController@delete')->name('delete-naskah');

        Route::get('/chat-mahasiswa', 'ChatController@mahasiswa')->name('chat-mahasiswa');
        Route::get('/detail-mahasiswa/{params}', 'ChatController@detail_mahasiswa')->name('detail-mahasiswa');
        Route::get('/get-message/{params}', 'ChatController@getChat');
        Route::post('/send-message', 'ChatController@send');
    });

    Route::get('/logout', 'Auth@logout')->name('logout');
});
