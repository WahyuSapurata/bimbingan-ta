<?php

namespace App\Http\Controllers;

use App\Models\ListBimbingan;
use App\Models\TrackingProgres;
use App\Models\User;
use Illuminate\Http\Request;

class Riwayat extends BaseController
{
    public function get($params)
    {
        $data = TrackingProgres::where('uuid_bimbingan', $params)->get();
        return $this->sendResponse($data, 'Get data success');
    }

    public function get_mahasiswa()
    {
        $data = TrackingProgres::all();
        $data->map(function ($item) {
            $bimbingan = ListBimbingan::where('uuid', $item->uuid_bimbingan)->first();
            $mahasiswa = User::where('uuid', $bimbingan->uuid_mahasiswa)->first();
            $dosen = User::where('uuid', $bimbingan->uuid_dosen)->first();

            $item->dosen = $dosen->name;
            $item->uuid_user = $mahasiswa->uuid;

            return $item;
        });

        // Filter data berdasarkan uuid mahasiswa yang sedang login
        $filteredData = $data->filter(function ($item) {
            return isset($item->uuid_user) && $item->uuid_user === auth()->user()->uuid;
        });

        // Mengubah kembali hasil filter menjadi collection dengan indeks yang berurutan
        $filteredData = $filteredData->values();

        return $this->sendResponse($filteredData, 'Get data success');
    }
}
