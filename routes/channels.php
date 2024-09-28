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
Broadcast::channel('chat.{receiver_uuid}', function ($user, $receiver_uuid) {
    return $user->uuid === $receiver_uuid; // Perbandingan UUID sebagai string
});

// Untuk channel penjadwalan
Broadcast::channel('penjadwalan.{uuid_user}', function ($user, $uuid_user) {
    return $user->uuid === $uuid_user; // Perbandingan UUID sebagai string
});
