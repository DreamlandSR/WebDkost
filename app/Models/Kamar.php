<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamar';
    protected $primaryKey = 'id_kamar';
    public $timestamps = false; // Karena database tidak punya created_at/updated_at

    protected $fillable = [
        'nomor_kamar',
        'tipe_kamar',
        'deskripsi',
        'harga_per_bulan',
        'status_kamar',
    ];

    // Relasi ke galeri
    public function galeri()
    {
        return $this->hasMany(GaleriKamar::class, 'id_kamar', 'id_kamar');
    }

    public function mainImage()
    {
        return $this->hasOne(GaleriKamar::class, 'id_kamar', 'id_kamar')
                    ->where('is_main', 1);
    }
    
    // Untuk kompatibilitas dengan view yang menggunakan galeriKamar
    public function galeriKamar()
    {
        return $this->mainImage();
    }

    public function fasilitas()
    {
        return $this->hasMany(FasilitasKamar::class, 'id_kamar', 'id_kamar');
    }
}