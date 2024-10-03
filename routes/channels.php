<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

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
Broadcast::channel('presence-chat.{chatId}', function ($user, $chatId) {
    // Log data awal
    Log::info('Checking presence-chat channel:', [
        'user_uuid' => $user->uuid,
        'chatId' => $chatId,
    ]);

    // Pisahkan chatId menjadi dua UUID
    $uuids = explode('_', $chatId);
    Log::info('Parsed UUIDs:', ['uuids' => $uuids]);

    if (count($uuids) !== 2) {
        Log::warning('Invalid chatId format:', ['chatId' => $chatId]);
        return false;
    }

    // Pastikan pengguna adalah salah satu dari kedua UUID
    if (!in_array($user->uuid, $uuids)) {
        Log::warning('User not authorized for chatId:', [
            'user_uuid' => $user->uuid,
            'chatId' => $chatId,
        ]);
        return false;
    }

    // Mengembalikan data pengguna untuk Presence Channel
    $presenceData = ['uuid' => $user->uuid, 'name' => $user->name];
    Log::info('User authorized for channel:', $presenceData);
    return $presenceData;
});

Broadcast::channel('notifications.{student_uuid}', function ($user, $student_uuid) {
    return $user->uuid === $student_uuid; // Memastikan user hanya bisa mendengar channel yang sesuai
});
