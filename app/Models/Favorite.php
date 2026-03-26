<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'product_id'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'product_id' => 'integer'
    ];

    // Relationship dengan User (optional)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship dengan Product (optional)
    // public function product()
    // {
    //     return $this->belongsTo(Product::class);
    // }

    // Scope untuk mencari favorites berdasarkan user
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Check if product is favorited by user
    public static function isFavorited($userId, $productId)
    {
        return self::where('user_id', $userId)
                  ->where('product_id', $productId)
                  ->exists();
    }
}