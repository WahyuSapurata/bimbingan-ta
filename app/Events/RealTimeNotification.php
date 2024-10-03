<?php

namespace App\Events;

use App\Models\Penjadwalan;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RealTimeNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $penjadwalan;
    public $studentUuid;
    public $nama_dosen;

    public function __construct(Penjadwalan $penjadwalan, $nama_dosen, $studentUuid)
    {
        $this->penjadwalan = $penjadwalan;
        $this->studentUuid = $studentUuid;
        $this->nama_dosen = $nama_dosen;
    }

    public function broadcastOn()
    {
        return new Channel('notifications.' . $this->studentUuid);
    }

    public function broadcastAs()
    {
        return 'jadwal-dibuat';
    }

    public function broadcastWith()
    {
        return [
            'message' => 'Jadwal baru telah dibuat.',
            'nama' => $this->nama_dosen,
            'tanggal' => $this->penjadwalan->tanggal,
            'waktu' => $this->penjadwalan->waktu,
            'metode' => $this->penjadwalan->metode,
            'catatan' => $this->penjadwalan->catatan,
            'created_at' => $this->penjadwalan->created_at->toDateTimeString(),
        ];
    }
}
