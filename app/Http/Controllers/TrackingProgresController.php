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
        // Mengambil semua data pengguna
        $dataFull = ListBimbingan::where('uuid_dosen', auth()->user()->uuid)->get();
        $dataFull->map(function ($item) {
            $mahasiswa = User::where('uuid', $item->uuid_mahasiswa)->first();
            $tracking = TrackingProgres::where('uuid_bimbingan', $item->uuid)->first();

            $item->mahasiswa = $mahasiswa->name;
            $item->progres = $tracking->progres ?? null;

            return $item;
        });

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function get_progres()
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

    public function detail_tracking($params)
    {
        $data = TrackingProgres::where('uuid_bimbingan', $params)->first();
        return $this->sendResponse($data, 'Get data success');
    }
}
