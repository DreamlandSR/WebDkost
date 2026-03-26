<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class productimages extends Model
{
    protected $fillable = ['product_id', 'image', 'is_main'];
    public $timestamps = false;

    protected $table = 'product_images';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
