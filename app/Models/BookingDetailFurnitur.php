<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BookingDetailFurnitur extends Model {
    protected $table = 'booking_detail_furnitur';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;
    protected $fillable = ['id_booking','id_furnitur','jumlah'];

    public function furnitur() { return $this->belongsTo(Furnitur::class, 'id_furnitur', 'id_furnitur'); }
}