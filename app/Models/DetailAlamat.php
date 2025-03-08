<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailAlamat extends Model
{
    use HasFactory;

    protected $table = 'detail_alamat';
    protected $fillable = [
        'alamat',
        'kabupaten',
        'provinsi',
    ];

    /**
     * Relasi ke model Warga (satu alamat bisa digunakan oleh banyak warga)
     */
    public function warga()
    {
        return $this->hasMany(Warga::class, 'id_alamat');
    }
}

