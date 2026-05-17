<?php
// ============================================================
// FILE: app/Http/Controllers/API/BookingController.php
// VERSI LENGKAP — sudah include:
//   A. Tagihan bulanan otomatis (via Scheduler)
//   B. Tambah furnitur mid-sewa
//   C. Akhiri sewa sekarang
//   D. Fix foto_primary di aktifByUser
//   E. Fix stok furnitur (kurangi saat booking, kembalikan saat batal/selesai)
// ============================================================

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDetailFurnitur;
use App\Models\Furnitur;
use App\Models\Kamar;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function indexByUser($userId)
    {
        $bookings = Booking::where('id_user', $userId)->get();
        
        foreach ($bookings as $booking) {
            $booking = $this->checkAndUpdateExpired($booking);
        }
        
        return response()->json([
            'success' => true, 
            'data' => $bookings->map(fn($b) => $this->formatBooking($b))
        ]);
    }
private function checkAndUpdateExpired(Booking $booking): Booking
{
    if ($booking->status_booking === 'menunggu_pembayaran') {
        $expiredAt = Carbon::parse($booking->expired_at);
        
        if (Carbon::now()->greaterThan($expiredAt)) {
            // Kembalikan stok furnitur
            foreach ($booking->furniturDetails as $detail) {
                if ($detail->furnitur) {
                    $detail->furnitur->increment('jumlah', $detail->jumlah);
                }
            }
            
            // Update status
            $booking->update(['status_booking' => 'expired']);
            
            // Kembalikan kamar
            if ($booking->kamar) {
                $booking->kamar->update(['status_kamar' => 'tersedia']);
            }
            
            // Update tagihan - gunakan nilai yang valid
            if ($booking->tagihan) {
                try {
                    $booking->tagihan()->update(['status_tagihan' => 'dibatalkan']);
                } catch (\Exception $e) {
                    // Jika error, skip update tagihan
                    \Log::warning("Gagal update tagihan untuk booking #{$booking->id_booking}: " . $e->getMessage());
                }
            }
            
            // Refresh model
            $booking->refresh();
        }
    }
    
    return $booking;
}
    // ── GET: Detail booking ────────────────────────────────
        public function show($id)
    {
        $booking = Booking::with(['kamar.galeri', 'furniturDetails.furnitur', 'tagihan'])
                        ->find($id);
        
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan.',
            ], 404);
        }
        
        // Cek dan update jika expired
        $booking = $this->checkAndUpdateExpired($booking);
        
        return response()->json([
            'success' => true,
            'data'    => $this->formatBooking($booking),
        ]);
    }

    // ── POST: Buat booking baru ────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'id_user'           => 'required|exists:users,id_user',
            'id_kamar'          => 'required|exists:kamar,id_kamar',
            'tgl_mulai_sewa'    => 'required|date',
            'durasi_sewa_bulan' => 'required|integer|min:1|max:12',
            'furnitur'          => 'nullable|array',
        ]);

        $kamar = Kamar::find($request->id_kamar);
        if ($kamar->status_kamar !== 'tersedia') {
            return response()->json([
                'success' => false,
                'message' => 'Kamar tidak tersedia.',
            ], 422);
        }

        $tglMulai   = Carbon::parse($request->tgl_mulai_sewa);
        $tglAkhir   = $tglMulai->copy()->addMonths($request->durasi_sewa_bulan);
        $totalBiaya = $kamar->harga_per_bulan * $request->durasi_sewa_bulan;

        // Hitung biaya furnitur + validasi stok
        $furniturItems = [];
        foreach ($request->furnitur ?? [] as $item) {
            $f = Furnitur::find($item['id_furnitur']);
            if ($f) {
                // Cek stok mencukupi
                if ($f->jumlah < $item['jumlah']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stok {$f->nama_furnitur} tidak mencukupi. Tersisa: {$f->jumlah}",
                    ], 422);
                }
                $totalBiaya   += $f->harga_sewa_tambahan * $item['jumlah'] * $request->durasi_sewa_bulan;
                $furniturItems[] = ['furnitur' => $f, 'jumlah' => $item['jumlah']];
            }
        }

        $expiredAt = now()->addHours(24);

        $booking = Booking::create([
            'id_user'             => $request->id_user,
            'id_kamar'            => $request->id_kamar,
            'tgl_booking'         => now()->toDateString(),
            'expired_at'          => $expiredAt,
            'durasi_sewa_bulan'   => $request->durasi_sewa_bulan,
            'tgl_mulai_sewa'      => $tglMulai->toDateString(),
            'tgl_akhir_sewa'      => $tglAkhir->toDateString(),
            'harga_per_bulan'     => $kamar->harga_per_bulan,
            'total_biaya_bulanan' => $totalBiaya,
            'status_booking'      => 'menunggu_pembayaran',
        ]);

        foreach ($furniturItems as $item) {
            BookingDetailFurnitur::create([
                'id_booking'  => $booking->id_booking,
                'id_furnitur' => $item['furnitur']->id_furnitur,
                'jumlah'      => $item['jumlah'],
            ]);

            // Kurangi stok furnitur
            $item['furnitur']->decrement('jumlah', $item['jumlah']);
        }

        $kamar->update(['status_kamar' => 'terisi']);

        $tagihan = Tagihan::create([
            'id_booking'      => $booking->id_booking,
            'periode_bulan'   => $tglMulai->format('Y-m-01'),
            'nominal_dasar'   => $totalBiaya,
            'nominal_denda'   => 0,
            'total_tagihan'   => $totalBiaya,
            'tgl_jatuh_tempo' => $expiredAt->toDateString(),
            'status_tagihan'  => 'belum_bayar',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil dibuat.',
            'data'    => [
                'id_booking'  => $booking->id_booking,
                'id_tagihan'  => $tagihan->id_tagihan,
                'total_biaya' => $totalBiaya,
                'expired_at'  => $expiredAt->toIso8601String(),
            ],
        ], 201);
    }

    // ── PUT: Batalkan booking (user) ───────────────────────
    public function batal($id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan.',
            ], 404);
        }
        if ($booking->status_booking !== 'menunggu_pembayaran') {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak dapat dibatalkan.',
            ], 422);
        }

        // Kembalikan stok furnitur
        foreach ($booking->furniturDetails as $detail) {
            Furnitur::where('id_furnitur', $detail->id_furnitur)
                    ->increment('jumlah', $detail->jumlah);
        }

        $booking->update(['status_booking' => 'batal']);
        Kamar::where('id_kamar', $booking->id_kamar)
              ->update(['status_kamar' => 'tersedia']);

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil dibatalkan.',
        ]);
    }

    // ══════════════════════════════════════════════════════
    // B. TAMBAH FURNITUR MID-SEWA
    // POST /booking/{id}/furnitur
    // Body: { "furnitur": [{ "id_furnitur": 1, "jumlah": 2 }] }
    // ══════════════════════════════════════════════════════
    public function tambahFurnitur(Request $request, $id)
    {
        $request->validate([
            'furnitur'              => 'required|array|min:1',
            'furnitur.*.id_furnitur'=> 'required|exists:furnitur,id_furnitur',
            'furnitur.*.jumlah'     => 'required|integer|min:1',
        ]);

        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan.',
            ], 404);
        }
        if ($booking->status_booking !== 'aktif') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya booking aktif yang dapat menambah furnitur.',
            ], 422);
        }

        // Validasi stok semua furnitur dulu sebelum proses
        foreach ($request->furnitur as $item) {
            $f = Furnitur::find($item['id_furnitur']);
            if ($f && $f->jumlah < $item['jumlah']) {
                return response()->json([
                    'success' => false,
                    'message' => "Stok {$f->nama_furnitur} tidak mencukupi. Tersisa: {$f->jumlah}",
                ], 422);
            }
        }

        // Hitung sisa bulan dari sekarang sampai tgl_akhir_sewa
        $sisaBulan = (int) ceil(
            Carbon::now()->floatDiffInMonths(
                Carbon::parse($booking->tgl_akhir_sewa), false
            )
        );
        if ($sisaBulan < 1) $sisaBulan = 1;

        $tambahanBiaya = 0;
        $ditambahkan   = [];

        foreach ($request->furnitur as $item) {
            $furnitur = Furnitur::find($item['id_furnitur']);
            if (!$furnitur) continue;

            // Cek apakah furnitur ini sudah ada di booking — jika ada, update jumlah
            $existing = BookingDetailFurnitur::where('id_booking', $booking->id_booking)
                ->where('id_furnitur', $furnitur->id_furnitur)
                ->first();

            if ($existing) {
                $existing->increment('jumlah', $item['jumlah']);
            } else {
                BookingDetailFurnitur::create([
                    'id_booking'  => $booking->id_booking,
                    'id_furnitur' => $furnitur->id_furnitur,
                    'jumlah'      => $item['jumlah'],
                ]);
            }

            // Kurangi stok furnitur umum
            $furnitur->decrement('jumlah', $item['jumlah']);

            // ── ASSIGN ITEM FISIK (TRACKING PENYEWA_FURNITUR) ──
            $availableItems = \App\Models\ItemFurnitur::where('id_furnitur', $furnitur->id_furnitur)
                ->where('status_item', 'Tersedia')
                ->limit($item['jumlah'])
                ->get();

            foreach ($availableItems as $physicalItem) {
                $physicalItem->update(['status_item' => 'Disewa']);
                
                \App\Models\PenyewaFurnitur::create([
                    'id_booking'    => $booking->id_booking,
                    'id_item'       => $physicalItem->id_item,
                    'id_user'       => $booking->id_user,
                    'tgl_mulai'     => Carbon::now()->toDateString(),
                    'tgl_selesai'   => $booking->tgl_akhir_sewa,
                    'status'        => 'aktif',
                    'catatan'       => 'Otomatis di-assign saat penambahan furnitur mid-sewa (Tagihan Tambahan).',
                ]);
            }

            $subtotal       = $furnitur->harga_sewa_tambahan * $item['jumlah'] * $sisaBulan;
            $tambahanBiaya += $subtotal;

            $ditambahkan[] = [
                'nama_furnitur' => $furnitur->nama_furnitur,
                'jumlah'        => $item['jumlah'],
                'sisa_bulan'    => $sisaBulan,
                'subtotal'      => $subtotal,
            ];
        }

        // Update total biaya bulanan booking
        $booking->increment('total_biaya_bulanan', $tambahanBiaya);

        // ── Update atau buat tagihan bulan ini ────────────────
        $bulanIni = Carbon::now()->format('Y-m-01');
        $tagihan  = Tagihan::where('id_booking', $booking->id_booking)
            ->where('periode_bulan', $bulanIni)
            ->where('status_tagihan', 'belum_bayar')
            ->first();

        if ($tagihan) {
            $tagihan->increment('nominal_dasar', $tambahanBiaya);
            $tagihan->increment('total_tagihan', $tambahanBiaya);
        } else {
            Tagihan::create([
                'id_booking'      => $booking->id_booking,
                'periode_bulan'   => $bulanIni,
                'nominal_dasar'   => $tambahanBiaya,
                'nominal_denda'   => 0,
                'total_tagihan'   => $tambahanBiaya,
                'tgl_jatuh_tempo' => Carbon::now()->addDays(7)->toDateString(),
                'status_tagihan'  => 'belum_bayar',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Furnitur berhasil ditambahkan.',
            'data'    => [
                'tambahan_furnitur'    => $ditambahkan,
                'total_tambahan_biaya' => $tambahanBiaya,
                'total_biaya_baru'     => $booking->fresh()->total_biaya_bulanan,
            ],
        ]);
    }

    // ══════════════════════════════════════════════════════
    // C. AKHIRI SEWA SEKARANG
    // POST /booking/{id}/selesai
    // ══════════════════════════════════════════════════════
    public function akhiriSewa($id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan.',
            ], 404);
        }
        if ($booking->status_booking !== 'aktif') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya booking aktif yang dapat diakhiri.',
            ], 422);
        }

        // Kembalikan stok furnitur
        foreach ($booking->furniturDetails as $detail) {
            Furnitur::where('id_furnitur', $detail->id_furnitur)
                    ->increment('jumlah', $detail->jumlah);
        }

        $today = Carbon::today()->toDateString();

        $booking->update([
            'status_booking' => 'selesai',
            'tgl_akhir_sewa' => $today,
        ]);

        // Kembalikan kamar ke tersedia
        Kamar::where('id_kamar', $booking->id_kamar)
              ->update(['status_kamar' => 'tersedia']);

        // Batalkan tagihan belum_bayar bulan depan dst
        $bulanIni = Carbon::now()->format('Y-m-01');
        Tagihan::where('id_booking', $booking->id_booking)
            ->where('status_tagihan', 'belum_bayar')
            ->where('periode_bulan', '>', $bulanIni)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sewa berhasil diakhiri. Kamar kembali tersedia.',
            'data'    => [
                'id_booking'     => $booking->id_booking,
                'status_booking' => 'selesai',
                'tgl_akhir_sewa' => $today,
            ],
        ]);
    }

    // ── GET: Booking aktif by user ─────────────────────────
    public function aktifByUser(int $userId)
    {
        $bookings = Booking::where('id_user', $userId)
            ->where('status_booking', 'aktif')
            ->with(['kamar', 'kamar.galeri'])
            ->get();

        if ($bookings->isEmpty()) {
            return response()->json(['success' => true, 'data' => []]);
        }

        return response()->json([
            'success' => true,
            'data'    => $bookings->map(fn($b) => [
                'id_booking'          => $b->id_booking,
                'id_user'             => $b->id_user,
                'id_kamar'            => $b->id_kamar,
                'tgl_booking'         => $b->tgl_booking,
                'durasi_sewa_bulan'   => $b->durasi_sewa_bulan,
                'tgl_mulai_sewa'      => $b->tgl_mulai_sewa,
                'tgl_akhir_sewa'      => $b->tgl_akhir_sewa,
                'total_biaya_bulanan' => $b->total_biaya_bulanan,
                'status_booking'      => $b->status_booking,
                'expired_at'          => $b->expired_at,
                'nomor_kamar'         => $b->kamar?->nomor_kamar,
                'tipe_kamar'          => $b->kamar?->tipe_kamar,
                'foto_primary'        => $b->kamar?->galeri
                                            ->where('is_main', 1)
                                            ->first()?->url_foto,
            ])->values(),
        ]);
    }

    // ── Private: Format booking untuk response ─────────────
        private function formatBooking(Booking $b): array
    {
        $kamar    = $b->kamar;
        $mainFoto = $kamar?->galeri()->where('is_main', 1)->first();
        
        // Ambil tagihan yang belum lunas atau yang terbaru
        $tagihan = $b->tagihan()
                    ->where('status_tagihan', '!=', 'lunas')
                    ->latest('id_tagihan')
                    ->first();
        
        // Jika tidak ada tagihan belum lunas, ambil yang terbaru
        if (!$tagihan) {
            $tagihan = $b->tagihan()->latest('id_tagihan')->first();
        }

        return [
            'id_booking'          => $b->id_booking,
            'expired_at'          => $b->expired_at,
            'id_user'             => $b->id_user,
            'id_kamar'            => $b->id_kamar,
            'tgl_booking'         => $b->tgl_booking,
            'durasi_sewa_bulan'   => $b->durasi_sewa_bulan,
            'tgl_mulai_sewa'      => $b->tgl_mulai_sewa,
            'tgl_akhir_sewa'      => $b->tgl_akhir_sewa,
            'total_biaya_bulanan' => $b->total_biaya_bulanan,
            'status_booking'      => $b->status_booking,  // ← pastikan ini 'expired'
            'nomor_kamar'         => $kamar?->nomor_kamar,
            'tipe_kamar'          => $kamar?->tipe_kamar,
            'foto_kamar'          => $mainFoto?->url_foto,
            'furnitur'            => $b->furniturDetails->map(fn($d) => [
                'id_furnitur'         => $d->id_furnitur,
                'nama_furnitur'       => $d->furnitur?->nama_furnitur,
                'jumlah'              => $d->jumlah,
                'harga_sewa_tambahan' => $d->furnitur?->harga_sewa_tambahan,
            ]),
            'tagihan' => $tagihan ? [
                'id_tagihan'      => $tagihan->id_tagihan,
                'total_tagihan'   => $tagihan->total_tagihan,
                'status_tagihan'  => $tagihan->status_tagihan,
                'tgl_jatuh_tempo' => $tagihan->tgl_jatuh_tempo,
            ] : null,
        ];
    }
}