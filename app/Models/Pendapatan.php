<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pendapatan extends Model {
    protected $table = 'pendapatan';
    protected $primaryKey = 'id_pendapatan';
    public $timestamps = false;
    protected $fillable = ['id_pembayaran','nominal','tgl_diterima'];
}
