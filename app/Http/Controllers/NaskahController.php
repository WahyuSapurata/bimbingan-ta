<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNaskahRequest;
use App\Http\Requests\UpdateNaskahRequest;
use App\Models\ListBimbingan;
use App\Models\Naskah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class NaskahController extends BaseController
{
    public function index()
    {
        $module = 'Naskah';
        return view('mahasiswa.naskah.index', compact('module'));
    }

    public function dosen()
    {
        $module = 'Naskah';
        return view('dosen.naskah.index', compact('module'));
    }

    public function get_naskah_dosen()
    {
        // Mengambil semua data pengguna
        $dataFull = Naskah::where('uuid_dosen', auth()->user()->uuid)->get();
        $dataFull->map(function ($item) {
            $bimbingan = ListBimbingan::where('uuid_mahasiswa', $item->uuid_mahasiswa)->first();
            $mahasiswa = User::where('uuid', $item->uuid_mahasiswa)->first();

            $item->angkatan = $bimbingan->angkatan;
            $item->mahasiswa = $mahasiswa->name;
            $item->nim = $mahasiswa->nip_nim;

            return $item;
        });

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = Naskah::all();
        $dataFull->map(function ($item) {
            $dosen = User::where('uuid', $item->uuid_dosen)->first();
            $item->dosen = $dosen->name;

            return $item;
        });

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function get_dosen()
    {
        // Mengambil semua data pengguna
        $dataFull = ListBimbingan::where('uuid_mahasiswa', auth()->user()->uuid)->get();
        $dataFull->map(function ($item) {
            $dosen = User::where('uuid', $item->uuid_dosen)->first();

            $item->uuid_dosen = $dosen->uuid;
            $item->dosen = $dosen->name;

            return $item;
        });

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function store(StoreNaskahRequest $storeNaskahRequest)
    {
        $data = array();

        $newFile = '';
        if ($storeNaskahRequest->file('file')) {
            $extension = $storeNaskahRequest->file('file')->extension();
            $newFile = $storeNaskahRequest->judul . '-' . now()->timestamp . '.' . $extension;
            $storeNaskahRequest->file('file')->storeAs('naskah', $newFile);
        }

        try {
            $data = new Naskah();
            $data->uuid_mahasiswa = auth()->user()->uuid;
            $data->uuid_dosen = $storeNaskahRequest->uuid_dosen;
            $data->judul = $storeNaskahRequest->judul;
            $data->deskripsi = $storeNaskahRequest->deskripsi;
            $data->file = $newFile;
            $data->status = "Belum Terbaca";
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
            $data = Naskah::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function update(StoreNaskahRequest $storeNaskahRequest, $params)
    {
        $data = Naskah::where('uuid', $params)->first();
        $oldFilePath = public_path('naskah/' . $data->file);

        $newFile = '';
        if ($storeNaskahRequest->file('file')) {
            $extension = $storeNaskahRequest->file('file')->extension();
            $newFile = $storeNaskahRequest->judul . '-' . now()->timestamp . '.' . $extension;
            $storeNaskahRequest->file('file')->storeAs('naskah', $newFile);

            // Hapus foto lama jika ada
            if (File::exists($oldFilePath)) {
                File::delete($oldFilePath);
            }
        }

        try {
            $data->uuid_dosen = $storeNaskahRequest->uuid_dosen;
            $data->uuid_mahasiswa = auth()->user()->uuid;
            $data->judul = $storeNaskahRequest->judul;
            $data->deskripsi = $storeNaskahRequest->deskripsi;
            $data->file = $storeNaskahRequest->file('file') ? $newFile : $data->file;
            $data->status = "Belum Terbaca";
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse($data, 'Update data success');
    }

    public function update_naskah($params)
    {
        try {
            $data = Naskah::where('uuid', $params)->first();
            $data->status = "Dibaca";
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
            $data = Naskah::where('uuid', $params)->first();
            $oldFilePath = public_path('naskah/' . $data->file);
            // Hapus foto lama jika ada
            if (File::exists($oldFilePath)) {
                File::delete($oldFilePath);
            }
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete data success');
    }
}
