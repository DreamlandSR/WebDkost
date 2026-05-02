<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    private string $projectId;
    private string $serviceAccountPath;

    public function __construct()
    {
        $this->projectId        = config('services.fcm.project_id') ?? '';
        $this->serviceAccountPath = config('services.fcm.service_account_path') ?? '';
    }

    // ── Kirim ke 1 user ───────────────────────────────────────
    public function kirimKeUser(
        string $fcmToken,
        string $judul,
        string $pesan,
        array  $data = []
    ): bool {
        if (empty($fcmToken)) return false;

        $accessToken = $this->getAccessToken();
        if (!$accessToken) return false;

        $payload = [
            'message' => [
                'token'        => $fcmToken,
                'notification' => [
                    'title' => $judul,
                    'body'  => $pesan,
                ],
                'android' => [
                    'priority' => 'high',
                    'notification' => [
                        'sound'        => 'default',
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    ],
                ],
                'apns' => [
                    'payload' => [
                        'aps' => ['sound' => 'default'],
                    ],
                ],
                'data' => array_map('strval', $data), // FCM data harus string semua
            ],
        ];

        $response = Http::withToken($accessToken)
            ->post(
                "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send",
                $payload
            );

        if ($response->failed()) {
            Log::error('FCM gagal', [
                'token'  => substr($fcmToken, 0, 20) . '...',
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return false;
        }

        return true;
    }

    // ── Kirim ke banyak user sekaligus ────────────────────────
    public function kirimKeBanyak(
        array  $fcmTokens,
        string $judul,
        string $pesan,
        array  $data = []
    ): array {
        $hasil = ['sukses' => 0, 'gagal' => 0];

        foreach ($fcmTokens as $token) {
            $ok = $this->kirimKeUser($token, $judul, $pesan, $data);
            $ok ? $hasil['sukses']++ : $hasil['gagal']++;
        }

        return $hasil;
    }

    // ── Generate OAuth2 Access Token dari Service Account ─────
    private function getAccessToken(): ?string
    {
        try {
            $serviceAccount = json_decode(
                file_get_contents($this->serviceAccountPath),
                true
            );

            $now = time();
            $header  = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
            $payload = base64_encode(json_encode([
                'iss'   => $serviceAccount['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud'   => 'https://oauth2.googleapis.com/token',
                'iat'   => $now,
                'exp'   => $now + 3600,
            ]));

            $toSign = "$header.$payload";
            openssl_sign($toSign, $signature, $serviceAccount['private_key'], 'SHA256');
            $jwt = "$toSign." . base64_encode($signature);

            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt,
            ]);

            return $response->json('access_token');
        } catch (\Exception $e) {
            Log::error('FCM access token gagal: ' . $e->getMessage());
            return null;
        }
    }
}
