<?php

namespace App\Console\Commands;

use App\Models\Tagihan; // sesuaikan dengan model tagihan kamu
use App\Services\NotifikasiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CekTagihanJatuhTempo extends Command
{
    protected $signature   = 'tagihan:cek-jatuh-tempo';
    protected $description = 'Kirim notifikasi reminder tagihan yang akan jatuh tempo dalam 3 hari';

    public function __construct(private NotifikasiService $notifService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        // Cek tagihan yang jatuh tempo 3 hari lagi dan belum dibayar
        // ⚠️ Sesuaikan nama tabel/kolom dengan database kamu
        $tagihans = Tagihan::with('user')
            ->whereDate('tanggal_jatuh_tempo', now()->addDays(3)->toDateString())
            ->where('status', '!=', 'lunas')
            ->get();

        $this->info("Ditemukan {$tagihans->count()} tagihan akan jatuh tempo.");

        foreach ($tagihans as $tagihan) {
            if (!$tagihan->user) continue;

            try {
                $tanggal = $tagihan->tanggal_jatuh_tempo->format('d - m - Y');
                $this->notifService->kirimReminderTagihan($tagihan->user, $tanggal);

                $this->line("✓ Notif terkirim ke: {$tagihan->user->name}");
            } catch (\Exception $e) {
                Log::error('Gagal kirim notif tagihan', [
                    'tagihan_id' => $tagihan->id,
                    'error'      => $e->getMessage(),
                ]);
                $this->error("✗ Gagal: {$tagihan->user->name} — {$e->getMessage()}");
            }
        }

        $this->info('Selesai.');
        return Command::SUCCESS;
    }
}