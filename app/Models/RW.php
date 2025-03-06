<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RW extends Model
{
    use HasFactory;

    protected $fillable = ['nama_rw'];

    public function rt()
    {
        return $this->hasMany(RT::class, 'id_rw');
    }

    public function pejabatRW()
    {
        return $this->hasMany(PejabatRW::class, 'id_rw');
    }
}
