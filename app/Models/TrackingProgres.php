<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class TrackingProgres extends Model
{
    use HasFactory;

    protected $table = 'tracking_progres';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'uuid_bimbingan',
        'progres',
        'target',
        'catatan',
        'feedback',
        'konsultasi',
        'ttd',
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
