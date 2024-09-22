<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Diskusi extends Model
{
    use HasFactory;

    protected $table = 'diskusis';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'uuid_dosen',
        'judul',
        'kategori',
        'deskripsi',
        'file',
        'link_meet',
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
