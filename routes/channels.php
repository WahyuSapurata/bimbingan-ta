<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Untuk channel chat
Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    // Pisahkan chatId menjadi dua UUID
    $uuids = explode('_', $chatId);
    if (count($uuids) !== 2) {
        return false;
    }

    // Pastikan pengguna adalah salah satu dari kedua UUID
    if (!in_array($user->uuid, $uuids)) {
        return false;
    }

    // Mengembalikan data pengguna untuk Presence Channel
    return ['uuid' => $user->uuid, 'name' => $user->name];
});

// Untuk channel penjadwalan
Broadcast::channel('penjadwalan.{uuid_user}', function ($user, $uuid_user) {
    return $user->uuid === $uuid_user; // Perbandingan UUID sebagai string
});
