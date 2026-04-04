<?php
// ============================================================
// FILE: app/Console/Kernel.php  ← bukan app/Http/Kernel.php
// ============================================================

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    // ── Schedule ───────────────────────────────────────────
    protected function schedule(Schedule $schedule): void
    {
        // Generate tagihan setiap tanggal 1, jam 00:01
        $schedule->command('tagihan:generate')
                 ->monthlyOn(1, '00:01')
                 ->withoutOverlapping()
                 ->runInBackground();

        // Cek & batalkan booking expired setiap 5 menit
        $schedule->command('booking:expire-check')
                 ->everyFiveMinutes()
                 ->withoutOverlapping()
                 ->runInBackground();
    }

    // ── Commands ───────────────────────────────────────────
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}