<?php

namespace App\Events;

use App\Models\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat;

    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->chat->receiver_uuid);
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
