<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OneSignalService
{
    private string $appId;
    private string $restApiKey;
    private string $baseUrl = 'https://onesignal.com/api/v1';

    public function __construct()
    {
        $this->appId      = config('onesignal.app_id');
        $this->restApiKey = config('onesignal.rest_api_key');
    }

    /**
     * Kirim push notification ke 1 user berdasarkan OneSignal Player ID
     */
    public function kirimKeUser(
        string $playerId,
        string $judul,
        string $pesan,
        array  $data = []
    ): bool {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $this->restApiKey,
                'Content-Type'  => 'application/json',
            ])->post("{$this->baseUrl}/notifications", [
                'app_id'             => $this->appId,
                'include_player_ids' => [$playerId],
                'headings'           => ['en' => $judul, 'id' => $judul],
                'contents'           => ['en' => $pesan,  'id' => $pesan],
                'data'               => $data,
            ]);

            if ($response->failed()) {
                Log::error('OneSignal: request gagal', [
                    'status'   => $response->status(),
                    'response' => $response->json(),
                ]);
                return false;
            }

            $body = $response->json();

            if (!empty($body['errors'])) {
                Log::warning('OneSignal: ada error', ['errors' => $body['errors']]);
                return false;
            }

            Log::info('OneSignal: notifikasi terkirim', [
                'notification_id' => $body['id'] ?? null,
                'recipients'      => $body['recipients'] ?? 0,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('OneSignal: exception - ' . $e->getMessage());
            return false;
        }
    }
}