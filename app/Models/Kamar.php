<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model {
    protected $table = 'kamar';
    protected $primaryKey = 'id_kamar';
    public $timestamps = false;
    protected $fillable = ['nomor_kamar','tipe_kamar','deskripsi','harga_per_bulan','status_kamar'];

    public function galeri()    { return $this->hasMany(GaleriKamar::class,    'id_kamar', 'id_kamar'); }
    public function fasilitas() { return $this->hasMany(FasilitasKamar::class, 'id_kamar', 'id_kamar'); }
    public function bookings()  { return $this->hasMany(Booking::class,        'id_kamar', 'id_kamar'); }
    public function reviews()   { return $this->hasMany(Review::class,         'id_kamar', 'id_kamar'); }
}