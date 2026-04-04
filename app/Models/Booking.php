<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model {
    protected $table = 'booking';
    protected $primaryKey = 'id_booking';
    public $timestamps = false;
    protected $fillable = [
        'id_user', 'id_kamar', 'tgl_booking', 'expired_at',
        'durasi_sewa_bulan', 'tgl_mulai_sewa', 'tgl_akhir_sewa',
        'total_biaya_bulanan', 'status_booking',
    ];

    public function user()            { return $this->belongsTo(User::class,    'id_user',    'id_user'); }
    public function kamar()           { return $this->belongsTo(Kamar::class,   'id_kamar',   'id_kamar'); }
    public function furniturDetails() { return $this->hasMany(BookingDetailFurnitur::class, 'id_booking', 'id_booking'); }
    public function tagihan()         { return $this->hasMany(Tagihan::class,   'id_booking', 'id_booking'); }
}