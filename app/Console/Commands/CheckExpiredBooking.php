<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Kamar;
use Carbon\Carbon;

class CheckExpiredBooking extends Command
{
    protected $signature   = 'booking:check-expired';
    protected $description = 'Ubah booking expired dan kembalikan kamar';

    public function handle()
{
    $expiredBookings = Booking::where('status_booking', 'menunggu_pembayaran')
        ->where('expired_at', '<', Carbon::now()) // ← fix bug 1
        ->get();

    foreach ($expiredBookings as $booking) {
        // Fix bug 2: kembalikan stok furnitur
        foreach ($booking->furniturDetails as $detail) {
            \App\Models\Furnitur::where('id_furnitur', $detail->id_furnitur)
                ->increment('jumlah', $detail->jumlah);
        }

        $booking->update(['status_booking' => 'expired']);

        Kamar::where('id_kamar', $booking->id_kamar)
            ->update(['status_kamar' => 'tersedia']);

        $booking->tagihan()->update(['status_tagihan' => 'dibatalkan']);
    }

    $this->info('Selesai: ' . $expiredBookings->count() . ' booking diupdate.');
    }
}   