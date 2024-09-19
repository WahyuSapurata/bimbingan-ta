<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTrackingProgresRequest;
use App\Http\Requests\UpdateTrackingProgresRequest;
use App\Models\ListBimbingan;
use App\Models\TrackingProgres;
use App\Models\User;

class TrackingProgresController extends BaseController
{
    public function store(StoreTrackingProgresRequest $StoreTrackingProgresRequest)
    {
        $data = array();

        $newTtd = '';
        if ($StoreTrackingProgresRequest->file('ttd')) {
            $extension = $StoreTrackingProgresRequest->file('ttd')->extension();
            $newTtd = 'ttd' . now()->timestamp . '.' . $extension;
            $StoreTrackingProgresRequest->file('ttd')->storeAs('ttd', $newTtd);
        }

        try {
            $data = new TrackingProgres();
            $data->uuid_bimbingan = $StoreTrackingProgresRequest->uuid_bimbingan;
            $data->progres = $StoreTrackingProgresRequest->progres;
            $data->target = $StoreTrackingProgresRequest->target;
            $data->catatan = $StoreTrackingProgresRequest->catatan;
            $data->feedback = $StoreTrackingProgresRequest->feedback;
            $data->konsultasi = $StoreTrackingProgresRequest->konsultasi;
            $data->ttd = $newTtd;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Added data success');
    }

    public function get()
    {
        $data = TrackingProgres::all();
        $data->map(function ($item) {
            $bimbingan = ListBimbingan::where('uuid', $item->uuid_bimbingan)->first();
            $mahasiswa = User::where('uuid', $bimbingan->uuid_mahasiswa)->first();

            $dosen = User::where('uuid', $bimbingan->uuid_dosen)->first();

            $item->angkatan = $bimbingan->angkatan;
            $item->nama_mahasiswa = $mahasiswa->name;
            $item->nim = $mahasiswa->nip_nim;

            $item->uuid_user = $dosen->uuid;

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

    public function get_progres()
    {
        $data = TrackingProgres::all();
        $data->map(function ($item) {
            $bimbingan = ListBimbingan::where('uuid', $item->uuid_bimbingan)->first();
            $mahasiswa = User::where('uuid', $bimbingan->uuid_mahasiswa)->first();
            $dosen = User::where('uuid', $bimbingan->uuid_dosen)->first();

            $item->dosen = $dosen->name;
            $item->judul = $bimbingan->judul;
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
