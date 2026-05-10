<?php
// ============================================================
// FILE: app/Console/Commands/ExpireBookingCheck.php
// ============================================================

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Furnitur;
use App\Models\Tagihan;
use Carbon\Carbon;

class ExpireBookingCheck extends Command
{
    protected $signature   = 'booking:expire-check';
    protected $description = 'Update expired booking status dan kembalikan stok/kamar';

    public function handle(): void
    {
        $now = Carbon::now();

        // Cari booking yang expired dan masih menunggu_pembayaran
        $bookings = Booking::where('status_booking', 'menunggu_pembayaran')
            ->where('expired_at', '<', $now)
            ->with(['furniturDetails', 'kamar', 'tagihan'])
            ->get();

        if ($bookings->isEmpty()) {
            $this->info("Tidak ada booking expired.");
            return;
        }

        foreach ($bookings as $booking) {
            $this->info("\n📝 Memproses Booking #{$booking->id_booking}");
            
            // 1. Kembalikan stok furnitur
            foreach ($booking->furniturDetails as $detail) {
                if ($detail->furnitur) {
                    $detail->furnitur->increment('jumlah', $detail->jumlah);
                    $this->line("  → Stok furnitur #{$detail->id_furnitur} +{$detail->jumlah}");
                }
            }

            // 2. Update status booking menjadi 'expired'
            $booking->update(['status_booking' => 'expired']);
            $this->line("  → Status booking: expired");

            // 3. Kembalikan status kamar menjadi tersedia
            if ($booking->kamar) {
                $booking->kamar->update(['status_kamar' => 'tersedia']);
                $this->line("  → Kamar #{$booking->id_kamar} status: tersedia");
            }

            // 4. Update tagihan yang terkait
            $tagihans = Tagihan::where('id_booking', $booking->id_booking)->get();
            
            if ($tagihans->isNotEmpty()) {
                foreach ($tagihans as $tagihan) {
                    // Update status tagihan menjadi 'dibatalkan' atau 'expired'
                    $tagihan->update(['status_tagihan' => 'dibatalkan']);
                    $this->line("  → Tagihan #{$tagihan->id_tagihan} status: dibatalkan");
                }
            } else {
                $this->line("  → ⚠️ Tidak ada tagihan untuk booking ini");
            }

            $this->info("✓ Booking #{$booking->id_booking} → EXPIRED");
        }

        $this->info("\n✅ Selesai: {$bookings->count()} booking expired diproses.");
        
        // Tampilkan summary
        $this->showSummary();
    }
    
    private function showSummary(): void
    {
        $expiredCount = Booking::where('status_booking', 'expired')->count();
        $this->info("\n📊 Summary:");
        $this->info("  Total booking expired: {$expiredCount}");
    }
}