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
    // Izinkan hanya pengguna yang merupakan pengirim atau penerima
    return (int) $user->uuid === (int) $receiver_uuid || $user->uuid === auth()->user()->uuid;
});
