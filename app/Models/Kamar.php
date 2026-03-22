<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamar';
    protected $primaryKey = 'id_kamar';
    public $timestamps = false;

    protected $fillable = [
        'nomor_kamar',
        'tipe_kamar',
        'deskripsi',
        'harga_per_bulan',
        'status_kamar',
    ];

    public function galeri()
    {
        return $this->hasMany(GaleriKamar::class, 'id_kamar', 'id_kamar');
    }

    public function mainImage()
    {
        return $this->hasOne(GaleriKamar::class, 'id_kamar', 'id_kamar')
                    ->where('is_main', 1);
    }

    public function fasilitas()
    {
        return $this->hasMany(FasilitasKamar::class, 'id_kamar', 'id_kamar');
    }

    public function booking()
    {
        return $this->hasMany(Booking::class, 'id_kamar', 'id_kamar');
    }

    public function review()
    {
        return $this->hasMany(Review::class, 'id_kamar', 'id_kamar');
    }
}
