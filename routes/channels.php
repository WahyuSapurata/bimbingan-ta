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

Broadcast::channel('public-chat.{receiver_uuid}', function ($user, $receiver_uuid) {
    return $user->uuid === $receiver_uuid;
});

Broadcast::channel('notifications.{student_uuid}', function ($user, $student_uuid) {
    return $user->uuid === $student_uuid; // Memastikan user hanya bisa mendengar channel yang sesuai
});

Broadcast::channel('verifikasi.{student_uuid}', function ($user, $student_uuid) {
    return $user->uuid === $student_uuid; // Memastikan user hanya bisa mendengar channel yang sesuai
});
