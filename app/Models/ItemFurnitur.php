<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemFurnitur extends Model
{
    protected $table = 'item_furnitur';
    protected $primaryKey = 'id_item';
    public $timestamps = true;

    protected $fillable = [
        'id_furnitur',
        'kode_item',
        'status_item',
    ];

    // Relasi ke induk furnitur
    public function furnitur()
    {
        return $this->belongsTo(Furnitur::class, 'id_furnitur', 'id_furnitur');
    }

    // Relasi ke riwayat sewa (PenyewaFurnitur)
    public function penyewaFurnitur()
    {
        return $this->hasMany(PenyewaFurnitur::class, 'id_item', 'id_item');
    }
}
