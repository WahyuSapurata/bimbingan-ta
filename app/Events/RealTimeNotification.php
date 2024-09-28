<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RealTimeNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $penjadwalan;
    public $uuid_user; // ID pengguna yang akan menerima notifikasi

    public function __construct($penjadwalan, $uuid_user)
    {
        $this->penjadwalan = $penjadwalan;
        $this->uuid_user = $uuid_user; // Menyimpan ID pengguna
    }

    public function broadcastOn()
    {
        return new PrivateChannel('penjadwalan.' . $this->uuid_user); // Private channel
    }

    public function broadcastWith()
    {
        return [
            'uuid_bimbingan' => $this->penjadwalan->uuid_bimbingan,
            'tanggal' => $this->penjadwalan->tanggal,
            'waktu' => $this->penjadwalan->waktu,
            'metode' => $this->penjadwalan->metode,
            'catatan' => $this->penjadwalan->catatan,
        ];
    }
}
