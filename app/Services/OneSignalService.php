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
        $this->appId      = config('services.onesignal.app_id', '');
        $this->restApiKey = config('services.onesignal.rest_api_key', '');
    }

    public function kirimKeUser(
        string $externalUserId,
        string $judul,
        string $pesan,
        array  $data = []
    ): bool {
        return $this->kirim(
            target: ['include_aliases' => ['external_id' => [$externalUserId]]],
            judul: $judul,
            pesan: $pesan,
            data: $data
        );
    }

    public function kirimKeBanyak(
        array  $externalUserIds,
        string $judul,
        string $pesan,
        array  $data = []
    ): array {
        $hasil = ['sukses' => 0, 'gagal' => 0];

        $ok = $this->kirim(
            target: ['include_aliases' => ['external_id' => $externalUserIds]],
            judul: $judul,
            pesan: $pesan,
            data: $data
        );

        if ($ok) {
            $hasil['sukses'] = count($externalUserIds);
        } else {
            $hasil['gagal'] = count($externalUserIds);
        }

        return $hasil;
    }

    private function kirim(
        array  $target,
        string $judul,
        string $pesan,
        array  $data = []
    ): bool {
        if (empty($this->appId) || empty($this->restApiKey)) {
            Log::warning('OneSignal: app_id atau rest_api_key belum diset di .env');
            return false;
        }

        $payload = array_merge($target, [
            'app_id'               => $this->appId,
            'headings'             => ['en' => $judul, 'id' => $judul],
            'contents'             => ['en' => $pesan, 'id' => $pesan],
            'data'                 => $data,
            'android_accent_color' => 'FF4CAF50',
            'target_channel'       => 'push',
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'Key ' . $this->restApiKey,
            'Content-Type'  => 'application/json',
        ])->post("{$this->baseUrl}/notifications", $payload);

        if ($response->failed()) {
            Log::error('OneSignal kirim gagal', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return false;
        }

        $json = $response->json();

        if (!empty($json['errors'])) {
            Log::warning('OneSignal warning', ['errors' => $json['errors']]);
        }

        Log::info('OneSignal sukses', [
            'notification_id' => $json['id'] ?? null,
            'recipients'      => $json['recipients'] ?? 0,
        ]);

        return true;
    }
}