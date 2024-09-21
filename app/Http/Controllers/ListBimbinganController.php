<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreListBimbinganRequest;
use App\Http\Requests\UpdateListBimbinganRequest;
use App\Models\ListBimbingan;
use App\Models\User;

class ListBimbinganController extends BaseController
{
    public function index()
    {
        $module = 'List Bimbingan';
        return view('admin.bimbingan.index', compact('module'));
    }

    public function dosen()
    {
        $module = 'List Bimbingan';
        return view('dosen.bimbingan.index', compact('module'));
    }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = ListBimbingan::all();
        $dataFull->map(function ($item) {
            $dosen = User::where('uuid', $item->uuid_dosen)->first();
            $mahasiswa = User::where('uuid', $item->uuid_mahasiswa)->first();

            $item->dosen = $dosen->name;
            $item->mahasiswa = $mahasiswa->name;

            return $item;
        });

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function get_dosen()
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

    public function store(StoreListBimbinganRequest $storeListBimbinganRequest)
    {
        $data = array();
        try {
            $data = new ListBimbingan();
            $data->uuid_dosen = $storeListBimbinganRequest->uuid_dosen;
            $data->uuid_mahasiswa = $storeListBimbinganRequest->uuid_mahasiswa;
            $data->angkatan = $storeListBimbinganRequest->angkatan;
            $data->judul = $storeListBimbinganRequest->judul;
            $data->jenis_bimbingan = $storeListBimbinganRequest->jenis_bimbingan;
            $data->pembimbing = $storeListBimbinganRequest->pembimbing;
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

    public function update(StoreListBimbinganRequest $storeListBimbinganRequest, $params)
    {
        try {
            $data = ListBimbingan::where('uuid', $params)->first();
            $data->uuid_dosen = $storeListBimbinganRequest->uuid_dosen;
            $data->uuid_mahasiswa = $storeListBimbinganRequest->uuid_mahasiswa;
            $data->angkatan = $storeListBimbinganRequest->angkatan;
            $data->judul = $storeListBimbinganRequest->judul;
            $data->jenis_bimbingan = $storeListBimbinganRequest->jenis_bimbingan;
            $data->pembimbing = $storeListBimbinganRequest->pembimbing;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse($data, 'Update data success');
    }

    public function delete($params)
    {
        $data = array();
        try {
            $data = ListBimbingan::where('uuid', $params)->first();
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete data success');
    }
}
