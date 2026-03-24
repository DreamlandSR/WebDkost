<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\User;
use App\Notifications\ReminderPembayaran;

class KirimReminderPembayaran extends Command
{
    protected $signature   = 'reminder:pembayaran';
    protected $description = 'Kirim reminder tagihan kos H-3 via Email';

    public function handle()
    {
        $targetTanggal = Carbon::today()->addDays(3)->toDateString();

        $this->info("Cek tagihan jatuh tempo: {$targetTanggal}");

        $tagihans = DB::table('tagihan')
            ->join('booking', 'tagihan.id_booking', '=', 'booking.id_booking')
            ->join('users', 'booking.id_user', '=', 'users.id_user')
            ->join('kamar', 'booking.id_kamar', '=', 'kamar.id_kamar')
            ->select(
                'tagihan.id_tagihan',
                'tagihan.periode_bulan',
                'tagihan.total_tagihan',
                'tagihan.tgl_jatuh_tempo',
                'users.id_user',
                'users.nama',
                'users.email',
                'kamar.nomor_kamar',
            )
            ->where('tagihan.status_tagihan', 'belum_bayar')
            ->whereDate('tagihan.tgl_jatuh_tempo', $targetTanggal)
            ->get();

        if ($tagihans->isEmpty()) {
            $this->info('Tidak ada tagihan yang perlu diingatkan hari ini.');
            return;
        }

        foreach ($tagihans as $tagihan) {
            if (!$tagihan->email) {
                $this->warn("Skip {$tagihan->nama}: email kosong");
                continue;
            }

            $user = User::find($tagihan->id_user);
            if (!$user) continue;

            try {
                $user->notify(new ReminderPembayaran($tagihan));

                $this->info("✅ Email terkirim → {$tagihan->nama} ({$tagihan->email})");

                Log::info('Reminder email terkirim', [
                    'id_tagihan' => $tagihan->id_tagihan,
                    'nama'       => $tagihan->nama,
                    'email'      => $tagihan->email,
                ]);

            } catch (\Exception $e) {
                $this->error("❌ Gagal → {$tagihan->nama}: " . $e->getMessage());
                Log::error('Gagal kirim reminder', [
                    'id_tagihan' => $tagihan->id_tagihan,
                    'error'      => $e->getMessage(),
                ]);
            }
        }

        $this->info('Selesai.');
    }
}
