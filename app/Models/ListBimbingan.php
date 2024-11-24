<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class ListBimbingan extends Model
{
    use HasFactory;

    protected $table = 'list_bimbingans';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'uuid_dosen',
        'uuid_mahasiswa',
        'angkatan',
        'judul',
        'jenis_bimbingan',
        'pembimbing',
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
