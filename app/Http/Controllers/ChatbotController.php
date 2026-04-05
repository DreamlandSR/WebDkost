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
            // 2. Classify intent (Gemini hanya untuk ini)
            $intent = $this->gemini->classifyIntent($request->message);

            // Tolak kalau tidak relevan
            if ($intent['intent'] === 'tidak_relevan' || $intent['confidence'] < 0.4) {
                return response()->json([
                    'success' => true,
                    'message' => 'Maaf, saya hanya bisa membantu pertanyaan seputar kos ini 😊',
                    'type'    => 'out_of_scope',
                    'data'    => null,
                ]);
            }

            // 3. Cek cache
            $params = $intent['params'] ?? [];
            $cached = $this->cache->getCachedResponse($intent['intent'], $params);
            if ($cached) {
                return response()->json([
                    'success'    => true,
                    'message'    => $cached['message'],
                    'data'       => $cached['data'],
                    'type'       => $intent['intent'],
                    'from_cache' => true,
                ]);
            }

            // 4. Query database
            $result = $this->queryDatabase($intent['intent'], $params);

            // 5. Simpan cache & return
            $this->cache->setCachedResponse($intent['intent'], $params, $result);

            return response()->json([
                'success'    => true,
                'message'    => $result['message'],
                'data'       => $result['data'],
                'type'       => $intent['intent'],
                'from_cache' => false,
            ]);

        } catch (\Exception $e) {
            \Log::error('Sinora chatbot error: ' . $e->getMessage());

    // ── Pesan khusus kalau Gemini rate limit ──────
    $message = $e->getMessage() === 'RATE_LIMIT_GEMINI'
        ? 'Server AI sedang sibuk, tunggu sebentar dan coba lagi ya 😊'
        : 'Maaf, ada gangguan teknis. Silakan coba lagi 🙏';

    return response()->json([
        'success' => false,
        'message' => $message,
        'data'    => null,
    ], 500);
    }
}

    private function queryDatabase(string $intent, array $params): array
    {
        return match($intent) {
            'cek_kamar_tersedia' => $this->getAvailableRooms($params),
            'cek_harga'          => $this->getRoomPrices(),
            'cek_fasilitas'      => $this->getFacilities($params),
            'lihat_review'       => $this->getReviews(),
            'cek_furnitur'       => $this->getFurniture(),
            default              => $this->getGeneralInfo(),
        };
    }

    private function getAvailableRooms(array $params): array
    {
        $tipe     = $params['tipe_kamar'] ?? null;
        $cacheKey = 'rooms_' . ($tipe ?? 'all');
        $cached   = $this->cache->getDbCache($cacheKey);
        if ($cached) return $cached;

        $query = Kamar::where('status_kamar', 'tersedia')
                      ->select('nomor_kamar', 'tipe_kamar', 'harga_per_bulan')
                      ->with('fasilitas:id_kamar,nama_fasilitas'); // pakai relasi yang sudah ada

        if ($tipe) {
            $query->where('tipe_kamar', $tipe);
        }

        $rooms = $query->orderBy('harga_per_bulan')->get()
                       ->map(fn($k) => [
                           'nomor'     => $k->nomor_kamar,
                           'tipe'      => ucfirst($k->tipe_kamar),
                           'harga'     => 'Rp ' . number_format($k->harga_per_bulan, 0, ',', '.') . '/bulan',
                           'fasilitas' => $k->fasilitas->pluck('nama_fasilitas')->join(', '),
                       ]);

        $result = [
            'data'    => $rooms,
            'message' => $rooms->isEmpty()
                ? 'Maaf, semua kamar sedang terisi 😔'
                : "Ada {$rooms->count()} kamar tersedia! 🏠",
        ];

        $this->cache->cacheDbResult($cacheKey, $result, 180);
        return $result;
    }

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

        $result = ['data' => $prices, 'message' => 'Daftar harga kamar kami 💰'];

        $this->cache->cacheDbResult('prices', $result, 600);
        return $result;
    }

    private function getFacilities(array $params): array
    {
        $tipe     = $params['tipe_kamar'] ?? null;
        $cacheKey = 'facilities_' . ($tipe ?? 'all');
        $cached   = $this->cache->getDbCache($cacheKey);
        if ($cached) return $cached;

        $query = FasilitasKamar::select('nama_fasilitas', 'deskripsi_fasilitas')->distinct();

        if ($tipe) {
            $query->join('kamar', 'fasilitas_kamar.id_kamar', '=', 'kamar.id_kamar')
                  ->where('kamar.tipe_kamar', $tipe);
        }

        $result = [
            'data'    => $query->get(),
            'message' => 'Fasilitas yang tersedia di kos kami ✨',
        ];

        $this->cache->cacheDbResult($cacheKey, $result, 3600);
        return $result;
    }

    private function getReviews(): array
    {
        $cached = $this->cache->getDbCache('reviews');
        if ($cached) return $cached;

        // SELECT rating & komentar SAJA - JANGAN ambil id_user atau join users!
        $reviews = Review::select('rating', 'komentar', 'tgl_review')
                         ->whereNotNull('komentar')
                         ->orderByDesc('tgl_review')
                         ->limit(5)
                         ->get()
                         ->map(fn($r) => [
                             'rating'   => str_repeat('⭐', $r->rating),
                             'komentar' => $r->komentar,
                             'tanggal'  => Carbon::parse($r->tgl_review)->format('M Y'),
                         ]);

        $avg = Review::avg('rating');

        $result = [
            'data'    => $reviews,
            'message' => '⭐ Rating rata-rata ' . number_format($avg, 1) . '/5',
        ];

        $this->cache->cacheDbResult('reviews', $result, 1800);
        return $result;
    }

    private function getFurniture(): array
    {
        $cached = $this->cache->getDbCache('furniture');
        if ($cached) return $cached;

        $furniture = Furnitur::select('nama_furnitur', 'jumlah', 'harga_sewa_tambahan')
                             ->where('jumlah', '>', 0)
                             ->get()
                             ->map(fn($f) => [
                                 'nama'   => $f->nama_furnitur,
                                 'jumlah' => $f->jumlah . ' unit',
                                 'biaya'  => $f->harga_sewa_tambahan > 0
                                     ? '+Rp ' . number_format($f->harga_sewa_tambahan, 0, ',', '.') . '/bln'
                                     : 'Sudah termasuk',
                             ]);

        $result = ['data' => $furniture, 'message' => 'Furnitur tersedia 🛋️'];

        $this->cache->cacheDbResult('furniture', $result, 3600);
        return $result;
    }

    private function getGeneralInfo(): array
    {
        return [
            'data'    => null,
            'message' => "Halo! Saya Sinora 😊\n\nSaya bisa bantu info tentang:\n🏠 Kamar tersedia\n💰 Harga sewa\n✨ Fasilitas\n⭐ Review penghuni\n🛋️ Furnitur\n\nSilakan tanyakan!",
        ];
    }
}