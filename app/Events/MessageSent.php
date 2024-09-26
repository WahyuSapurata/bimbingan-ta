<?php

namespace App\Events;

use App\Models\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcast // Menambahkan implementasi ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat;

    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
    }

    public function broadcastOn()
    {
        // Pastikan menggunakan channel yang sesuai
        return new Channel('chat.' . $this->chat->receiver_uuid);
    }

    public function broadcastWith()
    {
        return [
            'sender_uuid' => $this->chat->sender_uuid,
            'receiver_uuid' => $this->chat->receiver_uuid,
            'message' => $this->chat->message,
            'media_path' => $this->chat->media_path,
            'type' => $this->chat->type,
            'created_at' => $this->chat->created_at->toDateTimeString(),
        ];
    }
}
