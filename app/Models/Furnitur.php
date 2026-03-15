<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Furnitur extends Model {
    protected $primaryKey = 'id_furnitur';
    protected $table = 'furnitur';
    public $timestamps = false;
    protected $fillable = ['nama_furnitur','jumlah','harga_sewa_tambahan'];
}