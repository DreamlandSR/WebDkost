<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    private string $apiKey;
    private string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
    }

    // ── 1. Classify Intent ─────────────────────────────────────
    public function classifyIntent(string $userMessage): array
    {
        $response = $this->requestWithRetry([
            'contents' => [
                ['parts' => [['text' => $this->buildIntentPrompt($userMessage)]]]
            ],
            'generationConfig' => [
                'temperature'     => 0.7,
                'maxOutputTokens' => 2000,
            ]
        ]);

        if ($response->status() === 429) {
            throw new \Exception('RATE_LIMIT_GEMINI');
        }

        if ($response->failed()) {
            throw new \Exception('Gemini error: ' . $response->status());
        }

        $text = $response->json('candidates.0.content.parts.0.text');
        \Log::info('Gemini intent raw: ' . $text);

        return $this->parseResponse($text);
    }

    // ── 2. Generate Jawaban Natural dari Data DB ───────────────
    public function generateNaturalReply(
        string $userMessage,
        string $intent,
        array  $data
    ): string {
        $dataJson = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $prompt = <<<PROMPT
        Kamu adalah Sinora, asisten chatbot kos-kosan yang ramah, santai, dan komunikatif seperti ngobrol dengan teman.

        User bertanya: "{$userMessage}"
        Intent: {$intent}
        Data dari database: {$dataJson}

        Aturan menjawab:
        - Jawab HANYA berdasarkan DATA di atas, jangan mengarang
        - Gunakan bahasa Indonesia santai dan ramah seperti ngobrol antar teman
        - Panggil user dengan "kak"
        - Gunakan emoji yang sesuai tapi tidak berlebihan
        - Jika data kosong atau array kosong, sampaikan dengan sopan bahwa info tidak tersedia
        - Format jawaban mudah dibaca, gunakan baris baru untuk list item
        - Maksimal 2000 kata
        - JANGAN sebut kata "database", "sistem", "intent", atau istilah teknis apapun
        - Langsung jawab tanpa pembuka seperti "Baik kak" atau "Tentu saja"
        - Buat terasa seperti customer service yang hangat dan helpful

        Khusus jika intent adalah "info_umum":
        Setelah menyapa, WAJIB tampilkan menu ini:
        "Sinora bisa bantu info tentang:
        🏠 Kamar tersedia — tanya kamar yang masih kosong
        💰 Harga sewa — cek harga per tipe kamar
        ✨ Fasilitas — fasilitas lengkap di setiap kamar
        ⭐ Review — ulasan dari penghuni kos
        🛋️ Furnitur — perabotan yang tersedia

        Tanyakan aja kak, Sinora siap bantu! 😊"

        Contoh gaya:
        "Ada nih kak beberapa kamar yang masih kosong! 🏠 ..."
        "Wah lengkap banget fasilitasnya kak! ✨ Ada ..."
        "Rating kos kita bagus kak ⭐ Penghuni bilang ..."
        "Furniturnya ada beberapa nih kak 🛋️ ..."
        "Halo kak! 😊 Sinora siap bantu ..."
        PROMPT;

        $response = $this->requestWithRetry([
            'contents' => [
                ['parts' => [['text' => $prompt]]]
            ],
            'generationConfig' => [
                'temperature'     => 0.7,
                'maxOutputTokens' => 2000,
            ]
        ]);

        if ($response->status() === 429) {
            throw new \Exception('RATE_LIMIT_GEMINI');
        }

        if ($response->failed()) {
            throw new \Exception('Gemini error: ' . $response->status());
        }

        return $response->json('candidates.0.content.parts.0.text')
            ?? 'Maaf kak, ada gangguan teknis. Coba lagi ya! 🙏';
    }

    // ── Helper: Request dengan Retry ───────────────────────────
    private function requestWithRetry(array $payload, int $maxRetry = 2): \Illuminate\Http\Client\Response
    {
        $attempt  = 0;
        $response = null;

        while ($attempt <= $maxRetry) {
            $response = Http::timeout(15)->post(
                "{$this->baseUrl}?key={$this->apiKey}",
                $payload
            );

            // 503 = server overload, retry setelah jeda
            if ($response->status() === 503 && $attempt < $maxRetry) {
                $attempt++;
                sleep(2);
                continue;
            }

            return $response;
        }

        return $response;
    }

    // ── Intent Prompt ──────────────────────────────────────────
    private function buildIntentPrompt(string $message): string
    {
        return <<<PROMPT
        Kamu adalah classifier intent untuk chatbot kos-kosan bernama Sinora.
        Tugasmu HANYA mengklasifikasikan maksud pertanyaan user, JANGAN jawab pertanyaannya.

        Pertanyaan user: "{$message}"

        Pahami maksud user secara natural, contoh:
        - "kamar apa saja yang tersedia ya?" → cek_kamar_tersedia
        - "ada kamar kosong gak?" → cek_kamar_tersedia
        - "masih ada kamar yang bisa disewa?" → cek_kamar_tersedia
        - "kamar A1 fasilitasnya apa saja?" → cek_fasilitas
        - "fasilitas setiap kamar meliputi apa?" → cek_fasilitas
        - "AC nya ada gak di kamar?" → cek_fasilitas
        - "berapa harga sewanya?" → cek_harga
        - "kamar mewah harganya berapa?" → cek_harga
        - "review kamar berapa?" → lihat_review
        - "penghuni bilang apa tentang kos ini?" → lihat_review
        - "bintangnya berapa?" → lihat_review
        - "furniturnya ada apa saja?" → cek_furnitur
        - "kasurnya termasuk atau bayar lagi?" → cek_furnitur
        - "halo", "hai", "selamat pagi", "gimana cara booking" → info_umum
        - "tentang cuaca", "politik", "resep masakan" → tidak_relevan

        Intent yang tersedia:
        - cek_kamar_tersedia : tanya ketersediaan kamar
        - cek_harga          : tanya harga/biaya sewa
        - cek_fasilitas      : tanya fasilitas kamar
        - lihat_review       : tanya review/ulasan/rating
        - cek_furnitur       : tanya furnitur/perabotan
        - info_umum          : salam, pertanyaan umum kos
        - tidak_relevan      : diluar topik kos sama sekali

        Tipe kamar valid: biasa, sedang, mewah
        Jika user sebut nomor kamar (A1, B2, dll) masukkan ke params tipe_kamar.

        PENTING: "furnitur" atau "furniture" SELALU → cek_furnitur
        PENTING: "halo", "halli", "hai", "hi" SELALU → info_umum
        PENTING: "kamar" tanpa konteks lain → cek_kamar_tersedia

        Balas HANYA JSON tanpa markdown, tanpa penjelasan apapun:
        {"intent":"nama_intent","confidence":0.9,"params":{"tipe_kamar":null}}
        PROMPT;
    }

    // ── Parse Response ─────────────────────────────────────────
    private function parseResponse(string $text): array
    {
        $text    = preg_replace('/```json|```/', '', $text);
        $decoded = json_decode(trim($text), true);

        return $decoded ?? [
            'intent'     => 'info_umum',
            'confidence' => 0.5,
            'params'     => ['tipe_kamar' => null],
        ];
    }
}