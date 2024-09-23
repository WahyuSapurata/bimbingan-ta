<?php

namespace App\Http\Controllers;

use App\Models\ListBimbingan;
use App\Models\TrackingProgres;
use App\Models\User;
use Illuminate\Http\Request;

class Riwayat extends BaseController
{
    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = ListBimbingan::where('uuid_dosen', auth()->user()->uuid)->get();
        $dataFull->map(function ($item) {
            $mahasiswa = User::where('uuid', $item->uuid_mahasiswa)->first();

            $item->mahasiswa = $mahasiswa->name;
            $item->nim = $mahasiswa->nip_nim;

            return $item;
        });

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function detail_riwayat_dosen($params)
    {
        $data = TrackingProgres::where('uuid_bimbingan', $params)->get();
        $data->map(function ($item) {
            $bimbingan = ListBimbingan::where('uuid', $item->uuid_bimbingan)->first();
            $dosen = User::where('uuid', $bimbingan->uuid_dosen)->first();

            $item->dosen = $dosen->name;

            return $item;
        });
        return $this->sendResponse($data, 'Get data success');
    }

    public function get_mahasiswa()
    {
        // Mengambil semua data pengguna
        $dataFull = ListBimbingan::where('uuid_mahasiswa', auth()->user()->uuid)->get();
        $dataFull->map(function ($item) {
            $dosen = User::where('uuid', $item->uuid_dosen)->first();

            $item->dosen = $dosen->name;

            return $item;
        });

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function detail_riwayat_mahasiswa($params)
    {
        $data = TrackingProgres::where('uuid_bimbingan', $params)->get();
        $data->map(function ($item) {
            $bimbingan = ListBimbingan::where('uuid', $item->uuid_bimbingan)->first();
            $dosen = User::where('uuid', $bimbingan->uuid_dosen)->first();

            $item->dosen = $dosen->name;

            return $item;
        });
        return $this->sendResponse($data, 'Get data success');
    }
}
