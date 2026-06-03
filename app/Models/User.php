<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'id_user';

    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'nama',
        'email',
        'google_id',           // ← baru
        'email_verified_at',   // ← baru
        'password',
        'no_telepon',
        'alamat',
        'role',
        'onesignal_player_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'email_verified_at' => 'datetime',  // ← baru
        ];
    }

    public function getAuthIdentifierName()
    {
        return 'id_user';
    }

    // Helper: cek apakah email sudah diverifikasi
    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified_at !== null;
    }
}