<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'kuantitas',
        'harga',
        'subtotal',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getSubtotalAttribute($value)
    {
        // Jika subtotal sudah ada, gunakan nilai tersebut
        if ($value) {
            return $value;
        }

        // Jika tidak ada, hitung otomatis
        return $this->kuantitas * $this->harga;
    }

    public function setKuantitasAttribute($value)
    {
        $this->attributes['kuantitas'] = $value;
        if (isset($this->attributes['harga'])) {
            $this->attributes['subtotal'] = $value * $this->attributes['harga'];
        }
    }

    public function setHargaAttribute($value)
    {
        $this->attributes['harga'] = $value;
        if (isset($this->attributes['kuantitas'])) {
            $this->attributes['subtotal'] = $this->attributes['kuantitas'] * $value;
        }
    }
}
