<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stocks';
    public $timestamps = false;

    protected $fillable = ['quantity']; // Tambahkan product_id jika diperlukan

    // Relasi ke Product (one-to-one)
    public function product()
    {
        return $this->belongsTo(Product::class, 'id', 'stok_id');
    }
}
