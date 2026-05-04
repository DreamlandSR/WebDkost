<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use App\Services\ChatCacheService;
use App\Services\RateLimitService;
use App\Models\Kamar;
use App\Models\Furnitur;
use App\Models\Review;
use App\Models\FasilitasKamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ChatbotController extends Controller
{
    public function __construct(
        private GeminiService    $gemini,
        private ChatCacheService  $cache,
        private RateLimitService  $rateLimit,
    ) {}

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'user_id' => 'required|string',
        ]);

        // 1. Rate limit
        $limit = $this->rateLimit->checkLimit($request->user_id);
        if (!$limit['allowed']) {
            return response()->json([
                'success'     => false,
                'message'     => $limit['reason'],
                'retry_after' => $limit['retry_after'],
                'type'        => 'rate_limited',
            ], 429);
        }

        try {
            // 2. Check cache dulu sebelum hit Gemini
            // Jika ada cached response dari DB atau full response, langsung return
            $cachedResponse = $this->tryGetCachedResponse($request->message);
            if ($cachedResponse) {
                return response()->json($cachedResponse);
            }

            // 3. Tidak ada cache, query DB + Generate reply dalam 1 flow
            // Classify intent, query DB, dan generate natural reply sekaligus
            $result = $this->processUserMessage($request->message);

            return response()->json($result);

        } catch (\Exception $e) {
            \Log::error('Sinora chatbot error: ' . $e->getMessage());

            $message = match($e->getMessage()) {
                'RATE_LIMIT_GEMINI' => 'Sinora lagi sibuk nih kak 😅 Tunggu sebentar ya, coba lagi dalam 1 menit!',
                'GEMINI_SERVICE_UNAVAILABLE' => 'Sinora lagi maintenance, coba lagi dalam beberapa menit ya kak 🔧',
                default => 'Maaf kak, ada gangguan teknis. Coba lagi ya! 🙏'
            };

            return response()->json([
                'success' => false,
                'message' => $message,
                'data'    => null,
            ], 500);
        }
    }

    // ── Try Get Cached Response untuk exact duplicate messages ─────────────
    private function tryGetCachedResponse(string $message): ?array
    {
        $cacheKey = 'sinora_full_resp_' . md5($message);
        $cached = Cache::get($cacheKey);
        
        if ($cached) {
            \Log::info('Full response from cache for message: ' . substr($message, 0, 50));
            return [
                'success'    => true,
                'message'    => $cached['message'],
                'data'       => $cached['data'] ?? null,
                'type'       => $cached['type'] ?? 'cached',
                'from_cache' => true,
            ];
        }
        
        return null;
    }

    // ── Process User Message: Classify Intent → Query DB → Generate Reply (OPTIMIZED) ───
    private function processUserMessage(string $userMessage): array
    {
        // STEP 1: Classify intent ONLY (no reply generation yet)
        $classifyResult = $this->gemini->classifyIntent($userMessage);
        
        $intent = $classifyResult['intent'] ?? 'info_umum';
        $confidence = $classifyResult['confidence'] ?? 0;

        // Reject jika tidak relevan atau confidence terlalu rendah
        if ($intent === 'tidak_relevan' || $confidence < 0.3) {
            return [
                'success' => true,
                'message' => 'Maaf kak, Sinora hanya bisa bantu info seputar kos ini ya 😊' . "\n" . 'Coba tanyakan tentang kamar, harga, fasilitas, atau furnitur!',
                'data'    => null,
                'type'    => 'out_of_scope',
            ];
        }

        // STEP 2: Query database dengan params dari intent
        $params = $classifyResult['params'] ?? [];
        $dbResult = $this->queryDatabase($intent, $params);
        $responseData = null;
        $dbData = [];

        // Format response data untuk client
        if (in_array($intent, ['cek_kamar_tersedia', 'cek_kamar_budget', 'cek_kamar_rating']) && !empty($dbResult['data'])) {
            $responseData = is_object($dbResult['data'])
                ? $dbResult['data']->values()->toArray()
                : array_values((array) $dbResult['data']);
            $dbData = $responseData; // Pass ke Gemini
        } elseif (!empty($dbResult['data'])) {
            $dbData = is_object($dbResult['data'])
                ? $dbResult['data']->toArray()
                : (array) $dbResult['data'];
        }

        // STEP 3: Generate reply HANYA SEKALI dengan actual DB data
        // (No dual API calls - ini yang paling penting!)
        $reply = $this->gemini->generateNaturalReply(
            $userMessage,
            $intent,
            $dbData
        );

        // Fallback jika reply kosong
        if (empty($reply)) {
            $reply = $this->getDefaultReplyForIntent($intent, !empty($dbData));
        }

        // Cache full response
        $cacheKey = 'sinora_full_resp_' . md5($userMessage);
        $ttl = ($intent === 'cek_kamar_rating') ? 300 : 3600;
        Cache::put($cacheKey, [
            'message' => $reply,
            'data' => $responseData,
            'type' => $intent,
        ], $ttl);

        return [
            'success'    => true,
            'message'    => $reply,
            'data'       => $responseData,
            'type'       => $intent,
            'from_cache' => false,
        ];
    }

    // ── Default Reply per Intent (untuk fallback) ────────────────────────────
    private function getDefaultReplyForIntent(string $intent, bool $hasData): string
    {
        if (!$hasData) {
            return match($intent) {
                'cek_kamar_tersedia', 'cek_kamar_budget', 'cek_kamar_rating' => 
                    'Maaf kak, kamar yang kamu cari tidak ada nih 😔 Coba tanya yang lain ya!',
                'cek_fasilitas' => 'Maaf kak, info fasilitas tidak tersedia saat ini 😔',
                default => 'Halo kak! 😊 Ada yang bisa Sinora bantu?',
            };
        }

        return match($intent) {
            'cek_kamar_tersedia' => 'Ada nih kak beberapa kamar yang masih kosong! 🏠',
            'cek_kamar_budget'   => 'Dengan budget kamu, ada beberapa pilihan kamar yang cocok kak! 💰',
            'cek_kamar_rating'   => 'Kamar-kamar dengan rating terbaik sudah Sinora siapkan kak! ⭐',
            'cek_harga'          => 'Ini daftar harga sewa kamar kak 💰',
            'cek_fasilitas'      => 'Fasilitas lengkap tersedia di sini kak ✨',
            'lihat_review'       => 'Ini review dari penghuni kos kita kak ⭐',
            'cek_furnitur'       => 'Furnitur yang tersedia di sini kak 🛋️',
            default              => 'Halo kak! 😊 Ada yang bisa Sinora bantu?',
        };
    }

    // ── Router ─────────────────────────────────────────────────
    private function queryDatabase(string $intent, array $params): array
    {
        return match($intent) {
            'cek_kamar_tersedia' => $this->getAvailableRooms($params),
            'cek_kamar_budget'   => $this->getAvailableRoomsByBudget($params),
            'cek_kamar_rating'   => $this->getAvailableRoomsByRating(),
            'cek_harga'          => $this->getRoomPrices(),
            'cek_fasilitas'      => $this->getFacilities($params),
            'lihat_review'       => $this->getReviews(),
            'cek_furnitur'       => $this->getFurniture(),
            'info_umum'          => $this->getGeneralInfo(),
            default              => $this->getGeneralInfo(),
        };
    }

    // ── Kamar Sesuai Budget ───────────────────────────────────
    private function getAvailableRoomsByBudget(array $params): array
    {
        $budget = $params['budget'] ?? null;
        
        // Jika tidak ada budget parameter, return kosong
        if (!$budget) {
            return ['data' => collect([]), 'message' => ''];
        }

        $cacheKey = 'rooms_by_budget_' . $budget;
        $cached   = $this->cache->getDbCache($cacheKey);
        if ($cached) return $cached;

        $rooms = Kamar::where('status_kamar', 'tersedia')
                      ->where('harga_per_bulan', '<=', $budget)
                      ->select([
                          'id_kamar',
                          'nomor_kamar',
                          'tipe_kamar',
                          'harga_per_bulan',
                          'status_kamar',
                      ])
                      ->with([
                          'fasilitas:id_kamar,nama_fasilitas',
                          'galeri' => fn($q) => $q->where('is_main', 1)->limit(1),
                      ])
                      ->orderBy('harga_per_bulan')
                      ->limit(5)
                      ->get()
                      ->map(fn($k) => [
                          // Untuk Flutter KamarModel.fromJson
                          'id_kamar'        => $k->id_kamar,
                          'nomor_kamar'     => $k->nomor_kamar,
                          'tipe_kamar'      => $k->tipe_kamar,
                          'deskripsi'       => '',
                          'harga_per_bulan' => $k->harga_per_bulan,
                          'status_kamar'    => $k->status_kamar,
                          'foto_primary'    => $k->galeri->first()
                              ? asset('storage/' . $k->galeri->first()->url_foto)
                              : null,
                          'rating'          => null,

                          // Untuk Gemini natural reply
                          'nomor'     => $k->nomor_kamar,
                          'tipe'      => ucfirst($k->tipe_kamar),
                          'harga'     => 'Rp ' . number_format($k->harga_per_bulan, 0, ',', '.') . '/bulan',
                          'sisa_budget' => 'Sisa budget kamu: Rp ' . number_format($budget - $k->harga_per_bulan, 0, ',', '.'),
                          'fasilitas' => $k->fasilitas->pluck('nama_fasilitas')->join(', ') ?: '-',
                      ]);

        $result = ['data' => $rooms, 'message' => ''];
        $this->cache->cacheDbResult($cacheKey, $result, 180);
        return $result;
    }

    // ── Kamar dengan Rating Terbaik ─────────────────────────── (OPTIMIZED)
    private function getAvailableRoomsByRating(): array
    {
        $cacheKey = 'rooms_by_rating';
        $cached   = $this->cache->getDbCache($cacheKey);
        if ($cached) return $cached;

        // OPTIMIZED: Pakai subquery untuk average rating, bukan GROUP BY di main query
        $rooms = Kamar::where('status_kamar', 'tersedia')
                      ->select([
                          'kamar.id_kamar',
                          'kamar.nomor_kamar',
                          'kamar.tipe_kamar',
                          'kamar.harga_per_bulan',
                          'kamar.status_kamar',
                      ])
                      ->selectRaw('COALESCE((SELECT AVG(rating) FROM review WHERE review.id_kamar = kamar.id_kamar), 0) as avg_rating')
                      ->with([
                          'fasilitas:id_kamar,nama_fasilitas',
                          'galeri' => fn($q) => $q->where('is_main', 1)->limit(1),
                      ])
                      ->orderByDesc('avg_rating')
                      ->limit(5)
                      ->get()
                      ->map(fn($k) => [
                          // Untuk Flutter KamarModel.fromJson
                          'id_kamar'        => $k->id_kamar,
                          'nomor_kamar'     => $k->nomor_kamar,
                          'tipe_kamar'      => $k->tipe_kamar,
                          'deskripsi'       => '',
                          'harga_per_bulan' => $k->harga_per_bulan,
                          'status_kamar'    => $k->status_kamar,
                          'foto_primary'    => $k->galeri->first()
                              ? asset('storage/' . $k->galeri->first()->url_foto)
                              : null,
                          'rating'          => round($k->avg_rating, 1),

                          // Untuk Gemini natural reply
                          'nomor'     => $k->nomor_kamar,
                          'tipe'      => ucfirst($k->tipe_kamar),
                          'harga'     => 'Rp ' . number_format($k->harga_per_bulan, 0, ',', '.') . '/bulan',
                          'rating'    => round($k->avg_rating, 1) . '/5',
                          'fasilitas' => $k->fasilitas->pluck('nama_fasilitas')->join(', ') ?: '-',
                      ]);

        $result = ['data' => $rooms, 'message' => ''];
        // Rating cache lebih pendek (5 menit) karena bisa berubah
        $this->cache->cacheDbResult($cacheKey, $result, 300);
        return $result;
    }

    // ── Kamar Tersedia ─────────────────────────────────────────
    private function getAvailableRooms(array $params): array
    {
        $tipe     = $params['tipe_kamar'] ?? null;
        $cacheKey = 'rooms_' . ($tipe ?? 'all');
        $cached   = $this->cache->getDbCache($cacheKey);
        if ($cached) return $cached;

        $query = Kamar::where('status_kamar', 'tersedia')
                      ->select([
                          'id_kamar',
                          'nomor_kamar',
                          'tipe_kamar',
                          'harga_per_bulan',
                          'status_kamar',
                      ])
                      ->with([
                          'fasilitas:id_kamar,nama_fasilitas',
                          'galeri' => fn($q) => $q->where('is_main', 1)->limit(1),
                      ]);

        if ($tipe) {
            $query->where(function($q) use ($tipe) {
                $q->where('tipe_kamar', 'like', "%{$tipe}%")
                  ->orWhere('nomor_kamar', 'like', "%{$tipe}%");
            });
        }

        $rooms = $query->orderBy('harga_per_bulan')
                       ->limit(5)
                       ->get()
                       ->map(fn($k) => [
                           // Untuk Flutter KamarModel.fromJson
                           'id_kamar'        => $k->id_kamar,
                           'nomor_kamar'     => $k->nomor_kamar,
                           'tipe_kamar'      => $k->tipe_kamar,
                           'deskripsi'       => '',
                           'harga_per_bulan' => $k->harga_per_bulan,
                           'status_kamar'    => $k->status_kamar,
                           'foto_primary'    => $k->galeri->first()
                               ? asset('storage/' . $k->galeri->first()->url_foto)
                               : null,
                           'rating'          => null,

                           // Untuk Gemini natural reply
                           'nomor'     => $k->nomor_kamar,
                           'tipe'      => ucfirst($k->tipe_kamar),
                           'harga'     => 'Rp ' . number_format($k->harga_per_bulan, 0, ',', '.') . '/bulan',
                           'fasilitas' => $k->fasilitas->pluck('nama_fasilitas')->join(', ') ?: '-',
                       ]);

        $result = ['data' => $rooms, 'message' => ''];
        $this->cache->cacheDbResult($cacheKey, $result, 180);
        return $result;
    }

    // ── Harga ──────────────────────────────────────────────────
    private function getRoomPrices(): array
    {
        $cached = $this->cache->getDbCache('prices_available');
        if ($cached) return $cached;

        $prices = Kamar::select('tipe_kamar', 'harga_per_bulan')
                       ->where('status_kamar', 'tersedia') // FIX: Filter hanya kamar tersedia
                       ->groupBy('tipe_kamar', 'harga_per_bulan')
                       ->orderBy('harga_per_bulan')
                       ->get()
                       ->map(fn($k) => [
                           'tipe'  => ucfirst($k->tipe_kamar),
                           'harga' => 'Rp ' . number_format($k->harga_per_bulan, 0, ',', '.') . '/bulan',
                       ]);

        $result = ['data' => $prices, 'message' => ''];
        $this->cache->cacheDbResult('prices_available', $result, 600);
        return $result;
    }

    // ── Fasilitas ──────────────────────────────────────────────
    private function getFacilities(array $params): array
    {
        $tipe     = $params['tipe_kamar'] ?? null;
        $cacheKey = 'facilities_' . ($tipe ?? 'all');
        $cached   = $this->cache->getDbCache($cacheKey);
        if ($cached) return $cached;

        $query = FasilitasKamar::select(
            'fasilitas_kamar.nama_fasilitas',
            'fasilitas_kamar.deskripsi_fasilitas'
        )->distinct();

        if ($tipe) {
            $query->join('kamar', 'fasilitas_kamar.id_kamar', '=', 'kamar.id_kamar')
                  ->where(function($q) use ($tipe) {
                      $q->where('kamar.tipe_kamar', 'like', "%{$tipe}%")
                        ->orWhere('kamar.nomor_kamar', 'like', "%{$tipe}%");
                  });
        }

        $result = ['data' => $query->limit(5)->get(), 'message' => ''];
        $this->cache->cacheDbResult($cacheKey, $result, 3600);
        return $result;
    }

    // ── Review ─────────────────────────────────────────────────
    private function getReviews(): array
    {
        $cached = $this->cache->getDbCache('reviews');
        if ($cached) return $cached;

        $reviews = Review::select('rating', 'komentar', 'tgl_review')
                         ->whereNotNull('komentar')
                         ->orderByDesc('tgl_review')
                         ->limit(5)
                         ->get()
                         ->map(fn($r) => [
                             'rating'   => $r->rating . '/5',
                             'komentar' => $r->komentar,
                             'tanggal'  => Carbon::parse($r->tgl_review)->format('M Y'),
                         ]);

        $avg   = round(Review::avg('rating'), 1);
        $total = Review::count();

        $data = [
            'rata_rata_rating' => $avg . '/5',
            'total_ulasan'     => $total . ' ulasan',
            'ulasan_terbaru'   => $reviews->toArray(),
        ];

        $result = ['data' => collect([$data]), 'message' => ''];
        $this->cache->cacheDbResult('reviews', $result, 1800);
        return $result;
    }

    // ── Furnitur ───────────────────────────────────────────────
    private function getFurniture(): array
    {
        $cached = $this->cache->getDbCache('furniture');
        if ($cached) return $cached;

        $furniture = Furnitur::select('nama_furnitur', 'jumlah', 'harga_sewa_tambahan')
                             ->where('jumlah', '>', 0)
                             ->limit(5)
                             ->get()
                             ->map(fn($f) => [
                                 'nama'   => $f->nama_furnitur,
                                 'jumlah' => $f->jumlah . ' unit',
                                 'biaya'  => $f->harga_sewa_tambahan > 0
                                     ? '+Rp ' . number_format($f->harga_sewa_tambahan, 0, ',', '.') . '/bulan'
                                     : 'Sudah termasuk harga sewa',
                             ]);

        $result = ['data' => $furniture, 'message' => ''];
        $this->cache->cacheDbResult('furniture', $result, 3600);
        return $result;
    }

    // ── Info Umum ──────────────────────────────────────────────
    private function getGeneralInfo(): array
    {
        return [
            'data'    => null,
            'message' => 'Halo kak! Saya Sinora siap membantu 😊🌟' . "\n\n"
                       . 'Coba tanyakan:' . "\n"
                       . '🏠 "Kamar apa saja yang tersedia?"' . "\n"
                       . '💰 "Berapa harga sewa kamarnya?"' . "\n"
                       . '✨ "Fasilitas kamar meliputi apa saja?"' . "\n"
                       . '⭐ "Bagaimana review penghuni?"' . "\n"
                       . '🛋️ "Furnitur apa saja yang ada?"' . "\n\n"
                       . 'Saya siap bantu kak! 🙏',
        ];
    }
}