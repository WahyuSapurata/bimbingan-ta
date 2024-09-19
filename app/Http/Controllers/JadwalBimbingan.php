<?php

namespace App\Http\Controllers;

use App\Models\ListBimbingan;
use App\Models\Penjadwalan;
use App\Models\User;
use Illuminate\Http\Request;

class JadwalBimbingan extends BaseController
{
    public function index()
    {
        $module = "Jadwal Bimbingan";
        return view('dosen.jadwalbimbingan.index', compact('module'));
    }

    public function get()
    {
        $data = Penjadwalan::all(); // Mengambil semua data dari Penjadwalan

        // Map data untuk menambahkan informasi bimbingan, mahasiswa, dan dosen
        $data = $data->map(function ($item) {
            $bimbingan = ListBimbingan::where('uuid', $item->uuid_bimbingan)->first();

            // Pastikan $bimbingan ditemukan sebelum memproses mahasiswa dan dosen
            if ($bimbingan) {
                $mahasiswa = User::where('uuid', $bimbingan->uuid_mahasiswa)->first();
                $dosen = User::where('uuid', $bimbingan->uuid_dosen)->first();

                // Jika $mahasiswa dan $dosen ditemukan, tambahkan properti yang diperlukan
                if ($mahasiswa && $dosen) {
                    $item->nama = $mahasiswa->name;
                    $item->nim = $mahasiswa->nip_nim;
                    $item->angkatan = $bimbingan->angkatan;
                    $item->judul = $bimbingan->judul;
                    $item->uuid_user = $dosen->uuid;
                }
            }

            return $item;
        });

        // Filter data berdasarkan uuid user yang sedang login
        $filteredData = $data->filter(function ($item) {
            return isset($item->uuid_user) && $item->uuid_user === auth()->user()->uuid;
        });

        // Mengubah kembali hasil filter menjadi collection dengan indeks yang berurutan
        $filteredData = $filteredData->values();

        return $this->sendResponse($filteredData, 'Get data success');
    }

    public function mahasiswa()
    {
        $module = "Jadwal Bimbingan";
        return view('mahasiswa.jadwalbimbingan.index', compact('module'));
    }

    public function get_mahasiswa()
    {
        $data = Penjadwalan::all(); // Mengambil semua data dari Penjadwalan

        // Map data untuk menambahkan informasi bimbingan, mahasiswa, dan dosen
        $data = $data->map(function ($item) {
            $bimbingan = ListBimbingan::where('uuid', $item->uuid_bimbingan)->first();

            // Pastikan $bimbingan ditemukan sebelum memproses mahasiswa dan dosen
            if ($bimbingan) {
                $mahasiswa = User::where('uuid', $bimbingan->uuid_mahasiswa)->first();
                $dosen = User::where('uuid', $bimbingan->uuid_dosen)->first();

                // Jika $mahasiswa dan $dosen ditemukan, tambahkan properti yang diperlukan
                if ($mahasiswa && $dosen) {
                    $item->nama = $dosen->name;
                    $item->pembimbing = $bimbingan->pembimbing;
                    $item->uuid_user = $mahasiswa->uuid;
                }
            }

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
