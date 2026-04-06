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
        $cacheableIntents = ['cek_fasilitas', 'lihat_review', 'info_umum', 'cek_furnitur'];

        if (in_array($intent, $cacheableIntents)) {
            $key = 'sinora_resp_' . md5($intent . json_encode($params));
            Cache::put($key, $response, 3600); // 1 jam
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