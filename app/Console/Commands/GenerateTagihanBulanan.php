<?php
// ============================================================
// FILE: app/Console/Commands/GenerateTagihanBulanan.php
// Jalankan: php artisan tagihan:generate
// Schedule: setiap tanggal 1 jam 00:01 (di Kernel.php)
// ============================================================

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Tagihan;
use Carbon\Carbon;

class GenerateTagihanBulanan extends Command
{
    protected $signature   = 'tagihan:generate';
    protected $description = 'Generate tagihan bulanan untuk semua booking aktif';

    public function handle(): void
    {
        $bulanIni    = Carbon::now()->format('Y-m-01');
        $today       = Carbon::today();

        // Ambil semua booking aktif
        $bookings = Booking::where('status_booking', 'aktif')
            ->with('kamar')
            ->get();

        $generated = 0;
        $selesai   = 0;

        foreach ($bookings as $booking) {

            // ── Cek apakah masa sewa sudah habis ──────────────
            $tglAkhir = Carbon::parse($booking->tgl_akhir_sewa);
            if ($today->greaterThan($tglAkhir)) {
                // Tandai booking selesai & kembalikan kamar
                $booking->update(['status_booking' => 'selesai']);
                if ($booking->kamar) {
                    $booking->kamar->update(['status_kamar' => 'tersedia']);
                }
                $this->info("Booking #{$booking->id_booking} → selesai (masa sewa habis)");
                $selesai++;
                continue;
            }

            // ── Skip jika tagihan bulan ini sudah ada ─────────
            $sudahAda = Tagihan::where('id_booking', $booking->id_booking)
                ->where('periode_bulan', $bulanIni)
                ->exists();

            if ($sudahAda) continue;

            // ── Hitung denda dari tagihan bulan lalu (jika ada & belum lunas) ──
            $nominalDenda = 0;
            $tagihanLalu  = Tagihan::where('id_booking', $booking->id_booking)
                ->where('status_tagihan', '!=', 'lunas')
                ->where('periode_bulan', '<', $bulanIni)
                ->latest('periode_bulan')
                ->first();

            if ($tagihanLalu) {
                // Denda 2% dari nominal dasar per bulan terlambat
                $nominalDenda = $tagihanLalu->nominal_dasar * 0.02;
                // Update status tagihan lalu menjadi terlambat
                $tagihanLalu->update(['status_tagihan' => 'terlambat']);
            }

            $nominalDasar  = $booking->total_biaya_bulanan;
            $totalTagihan  = $nominalDasar + $nominalDenda;
            $tglJatuhTempo = Carbon::parse($bulanIni)->addDays(10)->toDateString();

            Tagihan::create([
                'id_booking'      => $booking->id_booking,
                'periode_bulan'   => $bulanIni,
                'nominal_dasar'   => $nominalDasar,
                'nominal_denda'   => $nominalDenda,
                'total_tagihan'   => $totalTagihan,
                'tgl_jatuh_tempo' => $tglJatuhTempo,
                'status_tagihan'  => 'belum_bayar',
            ]);

            $this->info("Tagihan dibuat → Booking #{$booking->id_booking} | Rp {$totalTagihan}");
            $generated++;
        }

        $this->info("Selesai: {$generated} tagihan dibuat, {$selesai} booking ditandai selesai.");
    }
}