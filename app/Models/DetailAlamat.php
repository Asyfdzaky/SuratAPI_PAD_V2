<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailAlamat extends Model
{
    use HasFactory;

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
