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
            // 2. Classify intent
            $intent = $this->gemini->classifyIntent($request->message);
            $params = $intent['params'] ?? [];

            // Tolak tidak relevan
            if ($intent['intent'] === 'tidak_relevan' || $intent['confidence'] < 0.3) {
                return response()->json([
                    'success' => true,
                    'message' => "Maaf kak, Sinora hanya bisa bantu info seputar kos ini ya 😊\nCoba tanyakan tentang kamar, harga, fasilitas, atau furnitur!",
                    'data'    => null,
                    'type'    => 'out_of_scope',
                ]);
            }

            // 3. Cek cache
            $cached = $this->cache->getCachedResponse($intent['intent'], $params);
            if ($cached) {
                return response()->json([
                    'success'    => true,
                    'message'    => $cached['message'],
                    'data'       => null,
                    'type'       => $intent['intent'],
                    'from_cache' => true,
                ]);
            }

            // 4. Query database
            $dbResult = $this->queryDatabase($intent['intent'], $params);

            // 5. Generate jawaban natural via Gemini
            $dataArray = [];
            if (!empty($dbResult['data'])) {
                $dataArray = is_object($dbResult['data'])
                    ? $dbResult['data']->toArray()
                    : (array) $dbResult['data'];
            }

            $naturalReply = $this->gemini->generateNaturalReply(
                $request->message,
                $intent['intent'],
                $dataArray
            );

            // 6. Cache & return
            $cacheData = ['message' => $naturalReply, 'data' => null];
            $this->cache->setCachedResponse($intent['intent'], $params, $cacheData);

            return response()->json([
                'success'    => true,
                'message'    => $naturalReply,
                'data'       => null,
                'type'       => $intent['intent'],
                'from_cache' => false,
            ]);

        } catch (\Exception $e) {
            \Log::error('Sinora chatbot error: ' . $e->getMessage());

            $message = $e->getMessage() === 'RATE_LIMIT_GEMINI'
                ? 'Sinora lagi sibuk nih kak 😅 Tunggu sebentar ya, coba lagi dalam 1 menit!'
                : 'Maaf kak, ada gangguan teknis. Coba lagi ya! 🙏';

            return response()->json([
                'success' => false,
                'message' => $message,
                'data'    => null,
            ], 500);
        }
    }

    // ── Router ─────────────────────────────────────────────────
    private function queryDatabase(string $intent, array $params): array
    {
        return match($intent) {
            'cek_kamar_tersedia' => $this->getAvailableRooms($params),
            'cek_harga'          => $this->getRoomPrices(),
            'cek_fasilitas'      => $this->getFacilities($params),
            'lihat_review'       => $this->getReviews(),
            'cek_furnitur'       => $this->getFurniture(),
            'info_umum'          => $this->getGeneralInfo(),
            default              => $this->getGeneralInfo(),
        };
    }

    // ── Kamar Tersedia ─────────────────────────────────────────
    private function getAvailableRooms(array $params): array
    {
        $tipe     = $params['tipe_kamar'] ?? null;
        $cacheKey = 'rooms_' . ($tipe ?? 'all');
        $cached   = $this->cache->getDbCache($cacheKey);
        if ($cached) return $cached;

        $query = Kamar::where('status_kamar', 'tersedia')
                      ->select('nomor_kamar', 'tipe_kamar', 'harga_per_bulan')
                      ->with('fasilitas:id_kamar,nama_fasilitas');

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
        $cached = $this->cache->getDbCache('prices');
        if ($cached) return $cached;

        $prices = Kamar::select('tipe_kamar', 'harga_per_bulan')
                       ->groupBy('tipe_kamar', 'harga_per_bulan')
                       ->orderBy('harga_per_bulan')
                       ->get()
                       ->map(fn($k) => [
                           'tipe'  => ucfirst($k->tipe_kamar),
                           'harga' => 'Rp ' . number_format($k->harga_per_bulan, 0, ',', '.') . '/bulan',
                       ]);

        $result = ['data' => $prices, 'message' => ''];
        $this->cache->cacheDbResult('prices', $result, 600);
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

        // Kirim avg & total ke Gemini juga
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
        // Info umum tidak perlu generate natural reply dari DB
        // Langsung return pesan sapaan
        return [
            'data'    => null,
            'message' => "Halo kak! Saya Sinora siap membantu 😊🌟\n\nCoba tanyakan:\n🏠 \"Kamar apa saja yang tersedia?\"\n💰 \"Berapa harga sewa kamarnya?\"\n✨ \"Fasilitas kamar meliputi apa saja?\"\n⭐ \"Bagaimana review penghuni?\"\n🛋️ \"Furnitur apa saja yang ada?\"\n\nSaya siap bantu kak! 🙏",
        ];
    }
}

