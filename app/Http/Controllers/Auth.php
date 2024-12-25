<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestsAuth;
use App\Http\Requests\Register;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash;

class Auth extends BaseController
{
    // web

    public function show()
    {
        return view('auth.login');
    }

    public function login_proses(RequestsAuth $authRequest)
    {
        $credential = $authRequest->getCredentials();
        $user = User::where('username', $authRequest->username)->first();

        if (!$user || !FacadesAuth::attempt($credential)) {
            return redirect()->route('login.login-akun')
                ->with('failed', 'Username atau Password salah')
                ->withInput($authRequest->only('username'));
        }

        if ($user->status == 'BELUM TERVERIFIKASI') {
            FacadesAuth::logout(); // Logout jika user belum terverifikasi
            return redirect()->route('login.login-akun')
                ->with('failed', 'Akun Belum Terverifikasi Admin')
                ->withInput($authRequest->only('username'));
        }

        // Jika autentikasi berhasil dan user terverifikasi
        return $this->authenticated();
    }

    public function authenticated()
    {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard-admin');
        } elseif (auth()->user()->role === 'dosen') {
            return redirect()->route('dosen.dashboard-dosen');
        } elseif (auth()->user()->role === 'mahasiswa') {
            return redirect()->route('mahasiswa.dashboard-mahasiswa');
        }
    }

    public function logout()
    {
        FacadesAuth::logout();
        return redirect()->route('login.login-akun')->with('success', 'Berhasil Logout');
    }

    // api

    public function register_akun()
    {
        return view('auth.register');
    }

    public function register_proses(Register $register)
    {
        dd($register->all());
        $data = array();
        try {
            $data = new User();
            $data->name = $register->name;
            $data->username = $register->username;
            $data->nip_nim = $register->nip_nim;
            $data->email = $register->email;
            $data->password = $register->password;
            $data->role = $register->role;
            $data->status = 'BELUM TERVERIFIKASI';
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Register data success');
    }

    public function temporary_login($params)
    {
        $user = User::where('uuid', $params)->first();
        $token = $user->createToken('tokenAPI')->plainTextToken;
        $data = [
            'token' => $token,
            'user' => $user
        ];
        return $this->sendResponse($data, 'Get data success');
    }

    public function do_login(RequestsAuth $authRequest)
    {
        // Cari user berdasarkan username
        $user = User::where('username', $authRequest->username)->first();

        // Cek apakah user ditemukan
        if (!$user) {
            return $this->sendError('Unauthorised.', ['error' => 'Username atau Password Salah'], 401);
        }

        // Cek apakah status user adalah "Belum Aktiv"
        if ($user->status == "Belum Aktiv") {
            return $this->sendError('Unauthorised.', ['error' => 'Akun belum diverifikasi Admin'], 401);
        }

        // Cek kredensial login
        $credentials = $authRequest->only('username', 'password');
        if (FacadesAuth::attempt($credentials)) {
            // Buat token jika login berhasil
            $token = $user->createToken('tokenAPI')->plainTextToken;
            $data = [
                'token' => $token,
                'user' => $user
            ];

            return $this->sendResponse($data, 'Berhasil login.');
        } else {
            // Jika kredensial salah
            return $this->sendError('Unauthorised.', ['error' => 'Username atau Password Salah'], 401);
        }
    }

    public function revoke(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->sendResponse('Success', 'Berhasil logout.');
    }

    // public function get_user()
    // {
    //     $user = auth()->user();
    //     return $this->sendResponse($user, 'Berhasil get data');
    // }
}
