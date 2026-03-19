<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $fillable = [
        'nama', 'email', 'password', 'no_telepon', 'alamat', 'role'
    ];

    protected $hidden = ['password'];

    public function getAuthIdentifierName()
    {
        return 'id_user';
    }
}