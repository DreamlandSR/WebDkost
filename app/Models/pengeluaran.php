<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pengeluaran extends Model
{
    protected $table = 'pengeluaran';
    protected $primaryKey = 'id_pengeluaran';
    public $timestamps = false;
    protected $fillable = ['kategori', 'nominal', 'tgl_transaksi', 'keterangan'];
}
