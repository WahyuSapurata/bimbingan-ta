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

Broadcast::channel('chat.{receiver_uuid}', function ($user, $receiver_uuid) {
    // Mengizinkan akses untuk pengirim dan penerima
    return in_array($user->uuid, [$receiver_uuid, auth()->user()->uuid]);
});
