<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class ChatCacheService
{
    // Pakai file cache dulu (tidak perlu Redis saat development)
    
    public function getCachedResponse(string $intent, array $params): ?array
    {
        $key    = 'sinora_resp_' . md5($intent . json_encode($params));
        $cached = Cache::get($key);

        if ($cached) {
            $cached['from_cache'] = true;
            return $cached;
        }
        return null;
    }

    public function setCachedResponse(string $intent, array $params, array $response): void
    {
        // Intent yang boleh di-cache (datanya jarang berubah)
        // cek_kamar_rating diberikan TTL 5 menit karena melibatkan rata-rata rating yang bisa berubah
        $cacheableIntents = ['cek_kamar_tersedia', 'cek_kamar_rating', 'cek_fasilitas', 'lihat_review', 'info_umum', 'cek_furnitur', 'cek_harga'];

        if (in_array($intent, $cacheableIntents)) {
            $key = 'sinora_resp_' . md5($intent . json_encode($params));
            // cek_kamar_rating cache hanya 5 menit karena rating bisa berubah setiap saat
            $ttl = ($intent === 'cek_kamar_rating') ? 300 : 3600;
            Cache::put($key, $response, $ttl);
        }
    }

    public function cacheDbResult(string $key, array $data, int $ttl = 300): void
    {
        Cache::put('sinora_db_' . $key, $data, $ttl);
    }

    public function getDbCache(string $key): ?array
    {
        return Cache::get('sinora_db_' . $key);
    }

    // ── User State (Pagination) ─────────────────────────────

    public function setUserState(string $userId, array $state): void
    {
             Cache::put('sinora_state_' . $userId, $state, 600); // 10 menit
    }

    public function getUserState(string $userId): ?array
    {
             return Cache::get('sinora_state_' . $userId);
    }

    public function clearUserState(string $userId): void
    {
             Cache::forget('sinora_state_' . $userId);
    }
}