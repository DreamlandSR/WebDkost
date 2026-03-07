<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    // Di dalam model Order.php
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'status',
        'alamat_pemesanan',
        'metode_pengiriman',
        'notes',
        'total_amount',
    ];

    /**
     * Relasi ke user (many-to-one)
     */


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relasi ke pengiriman (one-to-many)
     */
    public function pengirimans()
    {
        return $this->hasMany(Pengiriman::class, 'order_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function getTotalAmountAttribute()
    {
        return $this->orderItems->sum('subtotal');
    }

    public function getTotalQuantityAttribute()
    {
        return $this->orderItems->sum('kuantitas');
    }

     public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}
