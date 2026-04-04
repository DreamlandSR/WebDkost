<?php
// ============================================================
// FILE: app/Console/Commands/ExpireBookingCheck.php
// Jalankan: php artisan booking:expire-check
// Cek booking yang expired_at sudah lewat tapi masih menunggu
// ============================================================

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

class ExpireBookingCheck extends Command
{
    protected $signature   = 'booking:expire-check';
    protected $description = 'Batalkan booking yang melewati expired_at';

    public function handle(): void
    {
        $now = Carbon::now();

        $bookings = Booking::where('status_booking', 'menunggu_pembayaran')
            ->where('expired_at', '<', $now)
            ->with('kamar')
            ->get();

        foreach ($bookings as $booking) {
            $booking->update(['status_booking' => 'batal']);
            if ($booking->kamar) {
                $booking->kamar->update(['status_kamar' => 'tersedia']);
            }
            $this->info("Booking #{$booking->id_booking} → batal (expired)");
        }

        $this->info("Selesai: {$bookings->count()} booking dibatalkan.");
    }
}