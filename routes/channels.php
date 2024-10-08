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
// Broadcast::channel('presence-chat.{chatId}', function ($user, $chatId) {
//     // Pisahkan chatId menjadi dua UUID
//     $uuids = explode('_', $chatId);
//     if (count($uuids) !== 2) {
//         return false;
//     }

//     // Pastikan pengguna adalah salah satu dari kedua UUID
//     if (!in_array($user->uuid, $uuids)) {
//         return false;
//     }

//     // Mengembalikan data pengguna untuk Presence Channel
//     return ['uuid' => $user->uuid, 'name' => $user->name];
// });

// Broadcast::channel('public-chat', function ($user, $chatId) {
//     return true;
// });

Broadcast::channel('public-chat.{receiver_uuid}', function ($user, $receiver_uuid) {
    return $user->uuid === $receiver_uuid;
});

Broadcast::channel('notifications.{student_uuid}', function ($user, $student_uuid) {
    return $user->uuid === $student_uuid; // Memastikan user hanya bisa mendengar channel yang sesuai
});
