<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory,HasApiTokens,Notifiable;

    /**
     * The attributes that are mass assignable.
     *`
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'role',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean', // Jika menggunakan boolean untuk status
    ];

    /**
     * Relasi ke model Warga (User bisa memiliki satu data Warga)
     */
    public function warga()
    {
        return $this->hasOne(Warga::class, 'id_users');
    }
}
