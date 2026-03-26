<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table      = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';
    public $timestamps    = false;

    protected $fillable = [
        'id_tagihan',
        'order_id',
        'snap_token',
        'transaction_id_gateway',
        'tgl_bayar',
        'jumlah_bayar',
        'metode_pembayaran',
        'status_pembayaran',
    ];

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'id_tagihan', 'id_tagihan');
    }
}