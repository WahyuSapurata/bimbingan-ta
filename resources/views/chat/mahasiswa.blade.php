@extends('layouts.layout')
@section('content')
    <style>
        .user-list {
            list-style: none;
            padding: 0;
        }

        .user-list li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
        }

        .user-list li:hover {
            background-color: #f0f0f0;
        }
    </style>
    <div class="container">
        <h2>Daftar Pengguna</h2>
        <ul class="user-list">
            @foreach ($formattedData as $user)
                @if ($user->uuid_mahasiswa == auth()->user()->uuid)
                    <!-- Jangan tampilkan pengguna yang sedang login -->
                    <li class="d-flex justify-content-between"
                        onclick="window.location.href='{{ url('/mahasiswa/detail-mahasiswa/' . $user->receiver_uuid) }}'">
                        <div class="fw-bolder fs-4">{{ $user->nama }}</div>
                        <div class="d-flex align-items-center gap-2">
                            <div
                                class="bg-danger d-flex align-items-center justify-content-center w-25px h-25px rounded-circle text-white">
                                {{ $user->total_receiver }}</div>
                            <div>{{ \Carbon\Carbon::parse($user->jam_receiver)->format('H:i') }}</div>
                        </div>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
@endsection
