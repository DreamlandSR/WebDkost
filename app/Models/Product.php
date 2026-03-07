<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    public $timestamps = false;

    protected $fillable = ['nama', 'deskripsi', 'harga', 'stok_id', 'status', 'rating', 'berat'];

    // Relasi ke Stock
    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stok_id', 'id');
    }

    // Relasi ke Images
    public function images()
    {
        return $this->hasMany(ProductImages::class, 'product_id');
    }

    public function mainImage()
    {
        return $this->hasOne(ProductImages::class, 'product_id')->where('is_main', 1);
    }
}
