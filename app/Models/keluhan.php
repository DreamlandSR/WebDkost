<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class keluhan extends Model
{
    use HasFactory;

    protected $table = 'keluhan';
    protected $primaryKey = 'id_keluhan';
    public $timestamps = false; 

    protected $fillable = [
        'id_user',
        'id_kamar',
        'deskripsi_masalah',
        'foto_bukti',
        'tgl_lapor',
        'status_keluhan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar', 'id_kamar');
    }
}
