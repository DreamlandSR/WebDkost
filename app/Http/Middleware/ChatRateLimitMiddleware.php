<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use App\Services\ChatCacheService;
use App\Services\RateLimitService;
use App\Models\Kamar;
use App\Models\Furnitur;
use App\Models\Review;
use Illuminate\Http\Request;

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

        $userId  = $request->user_id;
        $message = $request->message;

        // ══════════════════════════════════════
        // STEP 1: Rate limit check
        // ══════════════════════════════════════
        $limitCheck = $this->rateLimit->checkLimit($userId);
        if (!$limitCheck['allowed']) {
            return response()->json([
                'success'     => false,
                'message'     => $limitCheck['reason'],
                'retry_after' => $limitCheck['retry_after'],
                'type'        => 'rate_limited'
            ], 429);
        }

        try {
            // ══════════════════════════════════════
            // STEP 2: Classify intent via Gemini
            // ══════════════════════════════════════
            $intent = $this->gemini->classifyIntent($message);

            // Jika tidak relevan, tolak langsung (hemat token)
            if ($intent['intent'] === 'tidak_relevan' || $intent['confidence'] < 0.4) {
                return response()->json([
                    'success' => true,
                    'message' => 'Maaf, saya hanya bisa membantu pertanyaan seputar kos ini 😊',
                    'type'    => 'out_of_scope'
                ]);
            }

            // ══════════════════════════════════════
            // STEP 3: Cek cache
            // ══════════════════════════════════════
            $cached = $this->cache->getCachedResponse($intent['intent'], $intent['params']);
            if ($cached) {
                return response()->json([
                    'success'    => true,
                    'data'       => $cached['data'],
                    'message'    => $cached['message'],
                    'type'       => $intent['intent'],
                    'from_cache' => true
                ]);
            }

            // ══════════════════════════════════════
            // STEP 4: Query DATABASE (bukan AI!)
            // ══════════════════════════════════════
            $result = $this->queryDatabase($intent);

            // ══════════════════════════════════════
            // STEP 5: Simpan cache & return
            // ══════════════════════════════════════
            $this->cache->setCachedResponse($intent['intent'], $intent['params'], $result);

            return response()->json([
                'success' => true,
                'data'    => $result['data'],
                'message' => $result['message'],
                'type'    => $intent['intent'],
            ]);

        } catch (\Exception $e) {
            \Log::error('Chatbot error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Maaf, ada gangguan teknis. Silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * SEMUA DATA DARI DATABASE - bukan dari AI
     */
    private function queryDatabase(array $intent): array
    {
        return match($intent['intent']) {
            'cek_kamar_tersedia' => $this->getAvailableRooms($intent['params']),
            'cek_harga'          => $this->getRoomPrices($intent['params']),
            'cek_fasilitas'      => $this->getFacilities($intent['params']),
            'lihat_review'       => $this->getReviews(),
            'cek_furnitur'       => $this->getFurniture($intent['params']),
            default              => $this->getGeneralInfo(),
        };
    }

    private function getAvailableRooms(array $params): array
    {
        $cacheKey = 'available_rooms_' . ($params['tipe_kamar'] ?? 'all');
        $cached   = $this->cache->getDbCache($cacheKey);

        if ($cached) return $cached;

        $query = Kamar::where('status', 'tersedia')
                      ->select('id', 'nomor', 'tipe', 'harga', 'lantai', 'status');

        if (!empty($params['tipe_kamar'])) {
            $query->where('tipe', 'like', '%' . $params['tipe_kamar'] . '%');
        }

        $rooms = $query->limit(10)->get();

        $result = [
            'data'    => $rooms,
            'message' => $rooms->isEmpty()
                ? 'Maaf, tidak ada kamar tersedia saat ini 😔'
                : "Ada {$rooms->count()} kamar tersedia! 🏠",
        ];

        $this->cache->cacheDbResult($cacheKey, $result, 180); // cache 3 menit
        return $result;
    }

    private function getRoomPrices(array $params): array
    {
        $rooms = Kamar::select('tipe', 'harga')
                      ->groupBy('tipe', 'harga')
                      ->orderBy('harga')
                      ->get();

        return [
            'data'    => $rooms,
            'message' => 'Berikut daftar harga kamar kami 💰',
        ];
    }

    private function getFacilities(array $params): array
    {
        $cacheKey = 'facilities_all';
        $cached   = $this->cache->getDbCache($cacheKey);
        if ($cached) return $cached;

        // Ambil dari tabel fasilitas_kamar atau kolom JSON
        $facilities = \DB::table('fasilitas_kamar')
                         ->select('nama', 'deskripsi', 'tersedia')
                         ->get();

        $result = [
            'data'    => $facilities,
            'message' => 'Fasilitas kamar kami meliputi ✨',
        ];

        $this->cache->cacheDbResult($cacheKey, $result, 3600);
        return $result;
    }

    private function getReviews(): array
    {
        $reviews = Review::select('rating', 'komentar')  // NO nama penyewa!
                         ->where('is_approved', true)
                         ->orderByDesc('created_at')
                         ->limit(5)
                         ->get();

        $avgRating = $reviews->avg('rating');

        return [
            'data'    => $reviews,
            'message' => "Rating rata-rata kami: ⭐ {$avgRating}/5",
        ];
    }

    private function getFurniture(array $params): array
    {
        $furniture = Furnitur::select('nama', 'stok', 'kondisi')
                             ->where('tersedia', true)
                             ->get();

        return [
            'data'    => $furniture,
            'message' => 'Furnitur yang tersedia di kos kami 🛋️',
        ];
    }

    private function getGeneralInfo(): array
    {
        return [
            'data'    => null,
            'message' => 'Silakan tanyakan tentang: kamar tersedia, harga, fasilitas, review, atau furnitur. Kami siap membantu! 😊',
        ];
    }
}