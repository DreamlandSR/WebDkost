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
    public function penyewaFurnitur() { return $this->hasMany(\App\Models\PenyewaFurnitur::class, 'id_booking', 'id_booking'); }

    protected static function booted()
    {
        static::updated(function ($booking) {
            if ($booking->wasChanged('status_booking')) {
                self::syncPenyewaFurnitur($booking, $booking->status_booking);
            }
        });
    }

    public static function syncPenyewaFurnitur(self $booking, string $statusBaru): void
    {
        try {
            if ($statusBaru === 'aktif') {
                $alreadyAssigned = PenyewaFurnitur::where('id_booking', $booking->id_booking)->exists();
                if ($alreadyAssigned) return;

                $details = BookingDetailFurnitur::where('id_booking', $booking->id_booking)->get();

                foreach ($details as $detail) {
                    $availableItems = ItemFurnitur::where('id_furnitur', $detail->id_furnitur)
                        ->where('status_item', 'Tersedia')
                        ->limit($detail->jumlah)
                        ->get();

                    foreach ($availableItems as $item) {
                        $item->update(['status_item' => 'Disewa']);
                        PenyewaFurnitur::create([
                            'id_booking'    => $booking->id_booking,
                            'id_item'       => $item->id_item,
                            'id_user'       => $booking->id_user,
                            'tgl_mulai'     => $booking->tgl_mulai_sewa,
                            'tgl_selesai'   => $booking->tgl_akhir_sewa,
                            'status'        => 'aktif',
                            'catatan'       => 'Otomatis di-assign saat booking #' . $booking->id_booking . ' diaktifkan via sistem.',
                        ]);
                    }
                }
            } elseif (in_array($statusBaru, ['selesai', 'batal', 'expired'])) {
                $penyewaanAktif = PenyewaFurnitur::where('id_booking', $booking->id_booking)
                    ->where('status', 'aktif')
                    ->get();

                foreach ($penyewaanAktif as $sewa) {
                    $sewa->update([
                        'status' => 'selesai',
                        'tgl_selesai' => date('Y-m-d')
                    ]);
                    if ($sewa->itemFurnitur) {
                        $sewa->itemFurnitur->update(['status_item' => 'Tersedia']);
                    }
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal sync penyewa furnitur via Model Event: ' . $e->getMessage());
        }
    }
}