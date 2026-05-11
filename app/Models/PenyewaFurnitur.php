<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenyewaFurnitur extends Model
{
    protected $table = 'penyewa_furnitur';
    protected $primaryKey = 'id_penyewa_furnitur';
    public $timestamps = true;

    protected $fillable = [
        'id_booking',
        'id_item',
        'id_user',
        'tgl_mulai',
        'tgl_selesai',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tgl_mulai'   => 'date',
        'tgl_selesai' => 'date',
    ];

    // Relasi ke Booking (nullable — bisa null jika sewa di luar booking)
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id_booking', 'id_booking');
    }

    // Relasi ke ItemFurnitur
    public function item()
    {
        return $this->belongsTo(ItemFurnitur::class, 'id_item', 'id_item');
    }

    // Relasi ke User (penyewa)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Scope: hanya yang aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Scope: filter berdasarkan furnitur (melalui item_furnitur)
    public function scopeByFurnitur($query, $id_furnitur)
    {
        return $query->whereHas('item', function($q) use ($id_furnitur) {
            $q->where('id_furnitur', $id_furnitur);
        });
    }
}
