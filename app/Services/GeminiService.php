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

    // ── 1. Classify Intent + Generate Reply (Combined untuk efisiensi) ─────
    public function classifyIntentAndGenerateReply(
        string $userMessage,
        array $dbData = []
    ): array {
        $cacheKey = 'gemini_full_' . md5($userMessage);
        $cached = \Illuminate\Support\Facades\Cache::get($cacheKey);
        if ($cached) {
            \Log::info('Gemini full response from cache: intent=' . $cached['intent']);
            return $cached;
        }

        $dataJson = json_encode($dbData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $response = $this->requestWithRetry([
            'contents' => [
                ['parts' => [['text' => $this->buildCombinedPrompt($userMessage, $dataJson)]]]
            ],
            'generationConfig' => [
                'temperature'     => 0.7,
                'maxOutputTokens' => 2000,
            ]
        ]);

        if ($response->status() === 429) {
            throw new \Exception('RATE_LIMIT_GEMINI');
        }

        if ($response->status() === 503) {
            throw new \Exception('GEMINI_SERVICE_UNAVAILABLE');
        }

        if ($response->failed()) {
            throw new \Exception('Gemini error: ' . $response->status());
        }

        $text = $response->json('candidates.0.content.parts.0.text');
        
        // Log raw response untuk debugging (truncate kalau terlalu panjang)
        $logText = strlen($text) > 200 ? substr($text, 0, 200) . '...' : $text;
        \Log::info('Gemini combined response raw: ' . $logText);

        $result = $this->parseCombinedResponse($text);
        
        // Validasi hasil parsing
        if (empty($result['reply'])) {
            \Log::warning('Parsed result has empty reply, using intent default');
            $result['reply'] = $this->getIntentDefaultReply($result['intent']);
        }

        \Log::info('Gemini combined result: intent=' . $result['intent'] . ', confidence=' . $result['confidence']);
        
        // Cache selama 1 jam untuk exact message yang sama
        \Illuminate\Support\Facades\Cache::put($cacheKey, $result, 3600);
        
        return $result;
    }

    // ── 2. Classify Intent Only (Fallback jika perlu) ──────────────────────
    public function classifyIntent(string $userMessage): array
    {
        // Cache intent classification - sama message = same intent
        $cacheKey = 'gemini_intent_' . md5($userMessage);
        $cached = \Illuminate\Support\Facades\Cache::get($cacheKey);
        if ($cached) {
            \Log::info('Gemini intent from cache: ' . json_encode($cached));
            return $cached;
        }

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

        if ($response->status() === 503) {
            throw new \Exception('GEMINI_SERVICE_UNAVAILABLE');
        }

        if ($response->failed()) {
            throw new \Exception('Gemini error: ' . $response->status());
        }

        $text = $response->json('candidates.0.content.parts.0.text');
        \Log::info('Gemini intent raw: ' . $text);

        $result = $this->parseResponse($text);
        
        // Cache selama 1 jam
        \Illuminate\Support\Facades\Cache::put($cacheKey, $result, 3600);
        
        return $result;
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

        Khusus jika intent adalah "cek_kamar_rating":
        - Tampilkan kamar dengan rating terbaik
        - Setiap kamar harus menampilkan: nomor kamar, tipe, harga, rating, dan fasilitas
        - Urutkan dari rating tertinggi
        - Format menarik dan jelas

        Khusus jika intent adalah "info_umum":
        Setelah menyapa, WAJIB tampilkan menu ini:
        "Sinora bisa bantu info tentang:
        🏠 Kamar tersedia — tanya kamar yang masih kosong
        💰 Harga sewa — cek harga per tipe kamar
        ✨ Fasilitas — fasilitas lengkap di setiap kamar
        ⭐ Review — ulasan dari penghuni kos & kamar terbaik
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

        if ($response->status() === 503) {
            throw new \Exception('GEMINI_SERVICE_UNAVAILABLE');
        }

        if ($response->failed()) {
            throw new \Exception('Gemini error: ' . $response->status());
        }

        return $response->json('candidates.0.content.parts.0.text')
            ?? 'Maaf kak, ada gangguan teknis. Coba lagi ya! 🙏';
    }

    // ── Helper: Request dengan Retry ───────────────────────────
    private function requestWithRetry(array $payload, int $maxRetry = 3): \Illuminate\Http\Client\Response
    {
        $attempt  = 0;
        $response = null;

        while ($attempt <= $maxRetry) {
            $response = Http::timeout(15)->post(
                "{$this->baseUrl}?key={$this->apiKey}",
                $payload
            );

            // 503 = server overload, retry dengan exponential backoff
            if ($response->status() === 503 && $attempt < $maxRetry) {
                $waitTime = 2 ** ($attempt + 1); // 2, 4, 8 detik
                \Log::warning("Gemini 503 - retry attempt " . ($attempt + 1) . ", waiting {$waitTime}s");
                $attempt++;
                sleep($waitTime);
                continue;
            }

            // 429 = rate limit, jangan retry terlalu banyak
            if ($response->status() === 429 && $attempt < 1) {
                \Log::warning("Gemini 429 - rate limited, retrying once");
                $attempt++;
                sleep(3);
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
        - "saya punya budget 900000" → cek_kamar_budget
        - "apa ada kamar yang cocok untuk budget saya?" → cek_kamar_budget
        - "kamar dengan harga dibawah 1 juta" → cek_kamar_budget
        - "yang terjangkau ada gak?" → cek_kamar_budget
        - "kamar dengan rating bagus?" → cek_kamar_rating
        - "kamar yang paling bagus ratingnya?" → cek_kamar_rating
        - "kamar terbaik berdasarkan rating?" → cek_kamar_rating
        - "kamar apa saja yang direkomendasikan?" → cek_kamar_rating
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
        - cek_kamar_budget   : tanya kamar sesuai budget/harga terjangkau
        - cek_kamar_rating   : tanya kamar dengan rating bagus
        - cek_kamar_fasilitas: tanya kamar dengan fasilitas spesifik (AC, kamar mandi dalam, WiFi, dll)
        - cek_harga          : tanya harga/biaya sewa
        - cek_fasilitas      : tanya fasilitas kamar
        - lihat_review       : tanya review/ulasan/rating umum
        - cek_furnitur       : tanya furnitur/perabotan
        - info_umum          : salam, pertanyaan umum kos
        - tidak_relevan      : diluar topik kos sama sekali

        EXTRACT BUDGET: Jika ada angka dalam message (misal 900000, 1000000), extract ke params.budget
        Contoh: "budget 900000" → params: {"budget": 900000, "tipe_kamar": null, "fasilitas": null}

        EXTRACT FASILITAS: Jika user mencari kamar dengan fasilitas tertentu, extract ke params.fasilitas.
        Contoh: "kamar yang ada AC" → params: {"budget": null, "tipe_kamar": null, "fasilitas": "AC"}
        Contoh: "kamar mandi dalam ada ngga?" → params: {"budget": null, "tipe_kamar": null, "fasilitas": "kamar mandi dalam"}

        PENTING: Selalu ekstrak angka sebagai budget jika ada
        PENTING: "budget", "harga", "terjangkau" + angka → cek_kamar_budget
        PENTING: "furnitur" atau "furniture" SELALU → cek_furnitur
        PENTING: "halo", "halli", "hai", "hi" SELALU → info_umum
        PENTING: "kamar" tanpa konteks lain → cek_kamar_tersedia

        Balas HANYA JSON tanpa markdown, tanpa penjelasan apapun:
        {"intent":"nama_intent","confidence":0.9,"params":{"budget":null,"tipe_kamar":null,"fasilitas":null}}
        PROMPT;
    }

    // ── Parse Combined Response (Intent + Natural Reply) ─────────────────
    private function parseCombinedResponse(string $text): array
    {
        // Bersihkan markdown wrapper jika ada
        $text = preg_replace('/```json\s*|\s*```/', '', $text);
        $text = trim($text);

        // Coba parse JSON
        $decoded = json_decode($text, true);
        
        if ($decoded && is_array($decoded)) {
            // Validasi minimum required fields
            if (!isset($decoded['intent'])) {
                \Log::warning('Parsed JSON missing intent field, fallback');
                return $this->getDefaultResponse();
            }

            // Jika reply kosong atau terlalu short (truncated), regenerate dengan simpler prompt
            $reply = $decoded['reply'] ?? '';
            if (strlen($reply) < 10) {
                \Log::warning('Reply too short, likely truncated. Using intent-based default');
                $reply = $this->getIntentDefaultReply($decoded['intent']);
            }

            return [
                'intent'     => $decoded['intent'] ?? 'info_umum',
                'confidence' => $decoded['confidence'] ?? 0.7,
                'params'     => $decoded['params'] ?? ['tipe_kamar' => null, 'fasilitas' => null],
                'reply'      => $reply,
            ];
        }

        // JSON parsing gagal - try extract dari text biasa
        \Log::warning('JSON parsing failed, attempting extraction');
        return $this->extractFromPlainText($text);
    }

    // ── Extract Intent dari Plain Text (Fallback) ──────────────────────────
    private function extractFromPlainText(string $text): array
    {
        // Cari pattern intent dari text
        $intentPatterns = [
            'cek_kamar_budget'   => ['budget', 'harga', 'terjangkau', 'dibawah', 'di bawah'],
            'cek_kamar_fasilitas'=> ['kamar ada', 'kamar fasilitas', 'kamar dengan'],
            'cek_kamar_tersedia' => ['tersedia', 'kosong', 'masih ada'],
            'cek_kamar_rating'   => ['rating', 'bagus', 'terbaik', 'rekomendasi'],
            'cek_harga'          => ['harga', 'biaya', 'sewa'],
            'cek_fasilitas'      => ['fasilitas', 'apa saja', 'ada gak'],
            'lihat_review'       => ['review', 'ulasan', 'bintang'],
            'cek_furnitur'       => ['furnitur', 'furniture', 'perabotan'],
            'tidak_relevan'      => ['cuaca', 'politik', 'resep'],
        ];

        $detectedIntent = 'info_umum';
        $textLower = strtolower($text);

        foreach ($intentPatterns as $intent => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($textLower, $keyword) !== false) {
                    $detectedIntent = $intent;
                    break 2;
                }
            }
        }

        // Extract budget number jika ada
        $budget = null;
        if (preg_match('/(\d{6,})/', $text, $matches)) {
            $budget = (int) $matches[1];
        }

        $params = ['tipe_kamar' => null, 'fasilitas' => null];
        if ($budget) {
            $params['budget'] = $budget;
        }

        return [
            'intent'     => $detectedIntent,
            'confidence' => 0.4,
            'params'     => $params,
            'reply'      => $text, // Gunakan text as-is
        ];
    }

    // ── Default Reply berdasarkan Intent ────────────────────────────────────
    private function getIntentDefaultReply(string $intent): string
    {
        return match($intent) {
            'cek_kamar_tersedia' => 'Ada beberapa kamar yang masih tersedia kak! 🏠 Coba tanyakan lebih detail atau tanya kamar mana yang kamu minati.',
            'cek_kamar_fasilitas'=> 'Sinora bisa carikan kamar dengan fasilitas tersebut kak! ✨ Coba sebutkan fasilitas yang diinginkan.',
            'cek_kamar_budget'   => 'Dengan budget kamu, pasti ada kamar yang cocok kak! 💰 Coba sebutkan angka budget yang kamu punya, nanti aku cariin pilihan terbaik untuk kamu.',
            'cek_kamar_rating'   => 'Kamar-kamar kita punya rating yang bagus kak! ⭐ Coba tanyakan kamar apa yang kamu cari.',
            'cek_harga'          => 'Harga sewa kamar bervariasi sesuai tipe kamar kak 💰 Coba tanyakan tipe kamar apa yang kamu minati.',
            'cek_fasilitas'      => 'Kamar-kamar kami punya fasilitas yang lengkap kak ✨ Coba tanyakan kamar apa yang kamu tanya.',
            'lihat_review'       => 'Review dari penghuni kos kita lumayan bagus kak ⭐ Tertarik coba menginap?',
            'cek_furnitur'       => 'Furnitur lengkap tersedia di sini kak 🛋️ Ada yang kamu butuhkan?',
            'tidak_relevan'      => 'Maaf kak, Sinora hanya bisa bantu info seputar kos ini ya 😊',
            default             => 'Halo kak! 😊 Ada yang bisa Sinora bantu seputar kos-kosan ini?',
        };
    }

    // ── Default Response (Ultimate Fallback) ────────────────────────────────
    private function getDefaultResponse(): array
    {
        return [
            'intent'     => 'info_umum',
            'confidence' => 0.3,
            'params'     => ['tipe_kamar' => null, 'fasilitas' => null],
            'reply'      => 'Halo kak! 😊 Ada yang bisa Sinora bantu? Tanya tentang kamar, harga, fasilitas, atau yang lain ya!',
        ];
    }

    // ── Combined Prompt (Classify + Generate dalam 1 request) ──────────────
    private function buildCombinedPrompt(string $userMessage, string $dbDataJson): string
    {
        return <<<PROMPT
        Kamu adalah Sinora, asisten chatbot kos-kosan yang ramah.
        TASK: Classify intent user dan buat jawaban natural dalam JSON.

        User bertanya: "{$userMessage}"
        Data dari database: {$dbDataJson}

        Intent yang tersedia:
        - cek_kamar_tersedia : kamar kosong, kamar available
        - cek_kamar_budget   : kamar dengan budget, harga terjangkau, harga dibawah X
        - cek_kamar_rating   : kamar bagus rating, kamar terbaik, rekomendasi
        - cek_kamar_fasilitas: kamar dengan fasilitas spesifik (AC, kamar mandi dalam, WiFi)
        - cek_harga          : harga sewa, biaya
        - cek_fasilitas      : fasilitas, apa saja, AC dll
        - lihat_review       : review, ulasan, rating, bintang
        - cek_furnitur       : furnitur, furniture, perabotan
        - info_umum          : salam, hello, halo
        - tidak_relevan      : cuaca, politik, resep

        EXTRACT BUDGET: Jika ada angka dalam message, extract ke params.budget
        Contoh: "budget 900000" → params: {"budget": 900000}
        Jika ada kata "dibawah", "di bawah", "kurang dari", hitung budget dari angka tersebut.
        
        EXTRACT FASILITAS: Jika user mencari kamar dengan fasilitas tertentu, extract ke params.fasilitas.
        Contoh: "kamar yang ada AC" → params: {"fasilitas": "AC"}
        Contoh: "kamar mandi dalam" → params: {"fasilitas": "kamar mandi dalam"}

        JAWABAN: Gunakan bahasa Indonesia santai. Panggil "kak". Pakai emoji. Maksimal 300 kata.

        CRITICAL - RESPONSE HARUS VALID JSON TANPA MARKDOWN:
        Jangan gunakan \`\`\`json atau \`\`\`
        Langsung output JSON object saja, dimulai dengan { dan diakhiri }
        Pastikan reply field tidak kosong dan lengkap.

        Contoh format BENAR:
        {"intent":"cek_kamar_budget","confidence":0.9,"params":{"budget":900000},"reply":"Ada nih kak..."}

        Format JSON yang harus dikembalikan:
        {
            "intent": "string (salah satu dari intent yang tersedia)",
            "confidence": 0.9,
            "params": {"budget": null atau number, "tipe_kamar": null atau string, "fasilitas": null atau string},
            "reply": "jawaban natural, pastikan lengkap dan tidak kosong"
        }

        Gaya jawaban:
        - Jangan sebut "database", "sistem", "intent"
        - Langsung ke jawaban tanpa pembuka "Baik kak"
        - Gunakan newline untuk list item
        - Kalau data kosong, bilang sopan tidak ada info
        - Untuk budget: "Dengan budget kamu, ada beberapa pilihan yang cocok kak..."
        PROMPT;
    }

    // ── Parse Response (Fallback intent parser) ────────────────────────────
    private function parseResponse(string $text): array
    {
        $text    = preg_replace('/```json|```/', '', $text);
        $decoded = json_decode(trim($text), true);

        return $decoded ?? [
            'intent'     => 'info_umum',
            'confidence' => 0.5,
            'params'     => ['tipe_kamar' => null, 'fasilitas' => null],
        ];
    }
}