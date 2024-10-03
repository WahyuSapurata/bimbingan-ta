<?php

namespace App\Http\Controllers;

use App\Events\RealTimeNotification;
use App\Http\Requests\StorePenjadwalanRequest;
use App\Http\Requests\UpdatePenjadwalanRequest;
use App\Models\ListBimbingan;
use App\Models\Penjadwalan;
use App\Models\User;

class PenjadwalanController extends BaseController
{
    public function index()
    {
        $module = 'Penjadwalan';
        return view('dosen.penjadwalan.index', compact('module'));
    }

    public function store(StorePenjadwalanRequest $storePenjadwalanRequest)
    {
        $data = array();
        try {
            $data = new Penjadwalan();
            $data->uuid_bimbingan = $storePenjadwalanRequest->uuid_bimbingan;
            $data->tanggal = $storePenjadwalanRequest->tanggal;
            $data->waktu = $storePenjadwalanRequest->waktu;
            $data->metode = $storePenjadwalanRequest->metode;
            $data->catatan = $storePenjadwalanRequest->catatan;
            $data->save();

            // Trigger event untuk notifikasi
            $data_user = ListBimbingan::where('uuid', $storePenjadwalanRequest->uuid_bimbingan)->first();
            $dosen = User::where('uuid', $data_user->uuid_dosen)->first();
            $data->nama = $dosen->name;
            event(new RealTimeNotification($data, $data_user->uuid_mahasiswa));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Added data success');
    }

    public function show($params)
    {
        $data = array();
        try {
            $data = ListBimbingan::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function delete($params)
    {
        $data = array();
        try {
            $data = Penjadwalan::where('uuid', $params)->first();
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete data success');
    }
}
