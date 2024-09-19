<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePenjadwalanRequest;
use App\Http\Requests\UpdatePenjadwalanRequest;
use App\Models\ListBimbingan;
use App\Models\Penjadwalan;

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
