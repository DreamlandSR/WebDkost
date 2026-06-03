<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class RateLimitService
{
    // Sesuai free tier Gemini: 15 RPM, 1500/hari
    private array $limits = [
        'per_user_minute' => 15,   // Naik dari 10 ke 15 pesan/menit per user
        'per_user_hour'   => 50,  // Naik dari 30 ke 50 pesan/jam per user
        'global_minute'   => 15,  // Naik dari 12 ke 15 global RPM (sesuai limit Gemini)
    ];

    public function checkLimit(string $userId): array
    {
        $minuteSlot = floor(time() / 60);
        $hourSlot   = floor(time() / 3600);

        $userMinKey    = "sinora_rl_u_{$userId}_m_{$minuteSlot}";
        $userHourKey   = "sinora_rl_u_{$userId}_h_{$hourSlot}";
        $globalMinKey  = "sinora_rl_global_m_{$minuteSlot}";

        $userMin    = (int) Cache::get($userMinKey, 0);
        $userHour   = (int) Cache::get($userHourKey, 0);
        $globalMin  = (int) Cache::get($globalMinKey, 0);

        if ($userMin >= $this->limits['per_user_minute']) {
            return [
                'allowed'     => false,
                'reason'      => 'Terlalu banyak pesan, tunggu 1 menit ya 😊',
                'retry_after' => 60 - (time() % 60),
            ];
        }

        if ($userHour >= $this->limits['per_user_hour']) {
            return [
                'allowed'     => false,
                'reason'      => 'Batas pesan per jam tercapai, coba lagi nanti 🙏',
                'retry_after' => 3600 - (time() % 3600),
            ];
        }

        if ($globalMin >= $this->limits['global_minute']) {
            return [
                'allowed'     => false,
                'reason'      => 'Server sedang sibuk, tunggu sebentar ya ⏳',
                'retry_after' => 60 - (time() % 60),
            ];
        }

        // Increment semua counter
        Cache::put($userMinKey,   $userMin   + 1, 120);
        Cache::put($userHourKey,  $userHour  + 1, 7200);
        Cache::put($globalMinKey, $globalMin + 1, 120);

        return ['allowed' => true];
    }
}