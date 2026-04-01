<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FasilitasKamar extends Model
{
    use HasFactory;

    protected $table = 'fasilitas_kamar';
    protected $primaryKey = 'id_fasilitas';
    public $timestamps = false;
    
    protected $fillable = [
        'id_kamar',
        'nama_fasilitas'
    ];

    protected $casts = [
        'id_kamar' => 'integer',
    ];

    /**
     * Relationship dengan Kamar
     */
    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar', 'id_kamar');
    }
}
