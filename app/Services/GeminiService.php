<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    private string $apiKey;
    //private string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';
    //private string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-lite:generateContent';
    private string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';
    //private string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
    }

    public function classifyIntent(string $userMessage): array
    {
        $response = Http::timeout(10)->post("{$this->baseUrl}?key={$this->apiKey}", [
            'contents' => [
                ['parts' => [['text' => $this->buildPrompt($userMessage)]]]
            ],
            'generationConfig' => [
                'temperature'     => 0.1,
                'maxOutputTokens' => 500,
            ]
        ]);

        if ($response->status() === 429) {
        throw new \Exception('RATE_LIMIT_GEMINI');
        }
        if ($response->failed()) {
            throw new \Exception('Gemini error: ' . $response->status());
        }

        $text = $response->json('candidates.0.content.parts.0.text');
        return $this->parseResponse($text);
    }

    private function buildPrompt(string $message): string
    {
    return <<<PROMPT
    Kamu classifier intent chatbot kos-kosan "Sinora".
    JANGAN jawab pertanyaan, HANYA klasifikasikan.

    Pertanyaan user: "{$message}"

    Pilih SATU intent yang paling cocok:
    - cek_kamar_tersedia : tanya kamar kosong, available, kamar apa saja, ada kamar tidak
    - cek_harga          : tanya harga, biaya, tarif, berapa harga
    - cek_fasilitas      : tanya fasilitas, apa saja fasilitasnya
    - lihat_review       : minta review, ulasan, testimoni, rating
    - cek_furnitur       : tanya furnitur, furniture, kasur, meja, kursi, lemari
    - info_umum          : salam, halo, hai, apa kabar, pertanyaan umum tentang kos
    - tidak_relevan      : diluar topik kos sama sekali (cuaca, politik, dll)

    Tipe kamar valid: biasa, sedang, mewah

    PENTING: kata "furnitur" atau "furniture" SELALU intent cek_furnitur
    PENTING: kata "halo", "hallo", "hai", "hi" SELALU intent info_umum
    PENTING: kata "kamar" saja tanpa konteks = cek_kamar_tersedia

    Balas HANYA JSON tanpa markdown:
    {"intent":"nama_intent","confidence":0.9,"params":{"tipe_kamar":null}}
    PROMPT;
    }

    private function parseResponse(string $text): array
    {
        $text = preg_replace('/```json|```/', '', $text);
        $decoded = json_decode(trim($text), true);

        return $decoded ?? [
            'intent'     => 'info_umum',
            'confidence' => 0.5,
            'params'     => ['tipe_kamar' => null],
        ];
    }
}