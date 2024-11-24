<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Verifikasi
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $studentUuid;

    public function __construct($studentUuid)
    {
        $this->studentUuid = $studentUuid;
    }

    public function broadcastOn()
    {
        return new Channel('verifikasi.' . $this->studentUuid);
    }

    public function broadcastAs()
    {
        return 'verifikasi';
    }

    public function broadcastWith()
    {
        return [
            'message' => 'Telah di Verifikasi Admin.',
        ];
    }
}
