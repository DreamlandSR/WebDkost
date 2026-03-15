<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GaleriKamar extends Model {
    protected $table = 'galeri_kamar';
    protected $primaryKey = 'id_foto';
    public $timestamps = false;
    protected $fillable = ['id_kamar','url_foto','is_main'];
}