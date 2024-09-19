<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDiskusiRequest;
use App\Http\Requests\UpdateDiskusiRequest;
use App\Models\Diskusi;
use App\Models\ListBimbingan;
use App\Models\User;

class DiskusiController extends BaseController
{
    public function store(StoreDiskusiRequest $storeDiskusiRequest)
    {
        $data = array();

        $newFile = '';
        if ($storeDiskusiRequest->file('file')) {
            $extension = $storeDiskusiRequest->file('file')->extension();
            $newFile = $storeDiskusiRequest->judul . '-' . now()->timestamp . '.' . $extension;
            $storeDiskusiRequest->file('file')->storeAs('diskusi', $newFile);
        }

        try {
            $data = new Diskusi();
            $data->uuid_dosen = auth()->user()->uuid;
            $data->uuid_mahasiswa = $storeDiskusiRequest->uuid_mahasiswa;
            $data->judul = $storeDiskusiRequest->judul;
            $data->kategori = $storeDiskusiRequest->kategori;
            $data->deskripsi = $storeDiskusiRequest->deskripsi;
            $data->file = $newFile;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Added data success');
    }

    public function get()
    {
        $data = ListBimbingan::all(); // Mengambil semua data dari ListBimbingan

        // Map data untuk mendapatkan informasi dosen dan mahasiswa
        $data = $data->map(function ($item) {
            $dosen = User::where('uuid', $item->uuid_dosen)->first();
            $mahasiswa = User::where('uuid', $item->uuid_mahasiswa)->first();

            if ($dosen && $mahasiswa) {
                $item->uuid_user = $dosen->uuid;
                $item->uuid_mahasiswa = $mahasiswa->uuid;
                $item->nama_mahasiswa = $mahasiswa->name;
            }

            return $item;
        });

        // Filter data berdasarkan uuid user yang sedang login
        $filteredData = $data->filter(function ($item) {
            return $item->uuid_user === auth()->user()->uuid;
        });

        // Mengubah kembali hasil filter menjadi collection
        $filteredData = $filteredData->values();

        return $this->sendResponse($filteredData, 'Get data success');
    }
}
