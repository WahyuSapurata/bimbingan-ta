<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Penjadwalan extends Model
{
    use HasFactory;

    protected $table = 'penjadwalans';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'uuid_bimbingan',
        'tanggal',
        'waktu',
        'metode',
        'catatan',
    ];

    protected static function boot()
    {
        parent::boot();

        // Event listener untuk membuat UUID sebelum menyimpan
        static::creating(function ($model) {
            $model->uuid = Uuid::uuid4()->toString();
        });
    }
}
