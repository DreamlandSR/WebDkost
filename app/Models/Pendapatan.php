<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    use HasFactory;
    protected $table = 'pengiriman';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'status_pengiriman',
        'nomor_resi',
        'jasa_kurir',
        'tanggal_dikirim',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
