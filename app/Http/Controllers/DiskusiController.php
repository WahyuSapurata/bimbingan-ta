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
            $data->judul = $storeDiskusiRequest->judul;
            $data->kategori = $storeDiskusiRequest->kategori;
            $data->deskripsi = $storeDiskusiRequest->deskripsi;
            $data->file = $newFile;
            $data->link_meet = $storeDiskusiRequest->link_meet;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Added data success');
    }

    // public function get()
    // {
    //     $data = ListBimbingan::all(); // Mengambil semua data dari ListBimbingan

    //     // Map data untuk mendapatkan informasi dosen dan mahasiswa
    //     $data = $data->map(function ($item) {
    //         $dosen = User::where('uuid', $item->uuid_dosen)->first();
    //         $mahasiswa = User::where('uuid', $item->uuid_mahasiswa)->first();

    //         if ($dosen && $mahasiswa) {
    //             $item->uuid_user = $dosen->uuid;
    //             $item->uuid_mahasiswa = $mahasiswa->uuid;
    //             $item->nama_mahasiswa = $mahasiswa->name;
    //         }

    //         return $item;
    //     });

    //     // Filter data berdasarkan uuid user yang sedang login
    //     $filteredData = $data->filter(function ($item) {
    //         return $item->uuid_user === auth()->user()->uuid;
    //     });

    //     // Mengubah kembali hasil filter menjadi collection
    //     $filteredData = $filteredData->values();

    //     return $this->sendResponse($filteredData, 'Get data success');
    // }

    public function get_diskusi()
    {
        $data = Diskusi::all();
        $data->map(function ($item) {
            $bimbingan = ListBimbingan::where('uuid_dosen', $item->uuid_dosen)->first();
            $dosen = User::where('uuid', $bimbingan->uuid_dosen)->first();

            $item->pembimbing = $bimbingan->pembimbing;
            $item->dosen = $dosen->name;
            $item->uuid_user = $bimbingan->uuid_mahasiswa;

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

    public function detail_diskusi($params)
    {
        $data = Diskusi::where('uuid', $params)->first();
        return $this->sendResponse($data, 'Get data success');
    }
}
