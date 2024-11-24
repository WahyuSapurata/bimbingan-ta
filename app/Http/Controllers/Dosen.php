<?php

namespace App\Http\Controllers;

use App\Http\Requests\Register;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Dosen extends BaseController
{
    public function index()
    {
        $module = 'Registrasi Dosen';
        return view('admin.dosen.index', compact('module'));
    }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = User::where('role', 'dosen')->get();

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function add(Register $register)
    {
        $data = array();
        try {
            $data = new User();
            $data->name = $register->name;
            $data->username = $register->username;
            $data->nip_nim = $register->nip_nim;
            $data->email = $register->email;
            $data->password = Hash::make($register->password);
            $data->role = $register->role;
            $data->status = 'BELUM TERVERIFIKASI';
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Register data success');
    }

    public function update($params)
    {
        try {
            $data = User::where('uuid', $params)->first();
            $data->status = "TERVERIFIKASI";
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse($data, 'Update data success');
    }
}
