<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FasilitasKamar extends Model {
    protected $table = 'fasilitas_kamar';
    protected $primaryKey = 'id_fasilitas';
    public $timestamps = false;
    protected $fillable = ['id_kamar','nama_fasilitas','deskripsi_fasilitas'];

}
