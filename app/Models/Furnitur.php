<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Furnitur extends Model {
    protected $primaryKey = 'id_furnitur';
    protected $table = 'furnitur';
    public $timestamps = false;
    protected $fillable = ['nama_furnitur','jumlah','harga_sewa_tambahan'];

    // Relasi ke item fisik furnitur
    public function items()
    {
        return $this->hasMany(ItemFurnitur::class, 'id_furnitur', 'id_furnitur');
    }

    // Hanya item yang tersedia
    public function itemTersedia()
    {
        return $this->hasMany(ItemFurnitur::class, 'id_furnitur', 'id_furnitur')
                    ->where('status_item', 'Tersedia');
    }
}
