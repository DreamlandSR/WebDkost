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
            ->where('tgl_booking', '<', Carbon::now()->subHours(24))
            ->get();

        foreach ($expiredBookings as $booking) {
            $booking->update(['status_booking' => 'expired']);
            
            Kamar::where('id_kamar', $booking->id_kamar)
                ->update(['status_kamar' => 'tersedia']);

            $booking->tagihan()->update(['status_tagihan' => 'belum_bayar']);
        }

        $this->info('Selesai: ' . $expiredBookings->count() . ' booking diupdate.');
    }
}