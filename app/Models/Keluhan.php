<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Keluhan extends Model {
    protected $table = 'keluhan';
    protected $primaryKey = 'id_keluhan';
    public $timestamps = false;
    protected $fillable = ['id_user','id_kamar','deskripsi_masalah','foto_bukti','tgl_lapor','status_keluhan'];

    public function kamar() { return $this->belongsTo(Kamar::class, 'id_kamar', 'id_kamar'); }
}