<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    // ── GET: Tagihan by booking ────────────────────────────
    public function indexByBooking($bookingId)
    {
        $list = Tagihan::where('id_booking', $bookingId)
            ->orderByDesc('periode_bulan')->get();
        return response()->json(['success' => true, 'data' => $list]);
    }

    // ── GET: Semua tagihan by user ─────────────────────────
    public function indexByUser($userId)
    {
        $bookingIds = Booking::where('id_user', $userId)
            ->pluck('id_booking');

        $list = Tagihan::whereIn('id_booking', $bookingIds)
            ->with('booking')
            ->orderByDesc('periode_bulan')
            ->get()
            ->map(fn($t) => $this->formatTagihan($t));

        return response()->json(['success' => true, 'data' => $list]);
    }

    // ── GET: Detail tagihan ────────────────────────────────
    public function show($id)
    {
        $tagihan = Tagihan::with('booking')->find($id);
        if (!$tagihan) {
            return response()->json([
                'success' => false,
                'message' => 'Tagihan tidak ditemukan.'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data'    => $this->formatTagihan($tagihan)
        ]);
    }

    // ── DELETE: Hapus tagihan (hanya yang lunas/expired) ───
    public function destroy($id)
    {
        $tagihan = Tagihan::find($id);
        if (!$tagihan) {
            return response()->json([
                'success' => false,
                'message' => 'Tagihan tidak ditemukan.'
            ], 404);
        }

        // Hanya boleh hapus yang sudah lunas
        if ($tagihan->status_tagihan !== 'lunas') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya tagihan lunas yang dapat dihapus.'
            ], 422);
        }

        $tagihan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tagihan berhasil dihapus.'
        ]);
    }

    // ── GET: Cek tagihan bulan ini ─────────────────────────
    public function cekBulanIni($bookingId)
    {
        $bulanIni = Carbon::now()->format('Y-m-01');

        $tagihan = Tagihan::where('id_booking', $bookingId)
            ->where('periode_bulan', $bulanIni)
            ->first();

        if (!$tagihan) {
            return response()->json([
                'success' => true,
                'sudah_ada' => false,
                'message'   => 'Tagihan bulan ini belum dibuat.',
            ]);
        }

        return response()->json([
            'success'    => true,
            'sudah_ada'  => true,
            'data'       => $this->formatTagihan($tagihan),
            'message'    => 'Tagihan bulan ini sudah ada.',
        ]);
    }

    // ── Format tagihan untuk response ─────────────────────
private function formatTagihan(Tagihan $t): array
    {
        $booking  = $t->booking;
        $kamar    = $booking?->kamar;
        $mainFoto = $kamar?->galeri()->where('is_main', 1)->first();

        return [
            'id_tagihan'     => $t->id_tagihan,
            'id_booking'     => $t->id_booking,
            'periode_bulan'  => $t->periode_bulan,
            'nominal_dasar'  => $t->nominal_dasar,
            'nominal_denda'  => $t->nominal_denda,
            'total_tagihan'  => $t->total_tagihan,
            'tgl_jatuh_tempo'=> $t->tgl_jatuh_tempo,
            'status_tagihan' => $t->status_tagihan,
            'status_booking' => $booking?->status_booking,
            'nama_kamar'     => $kamar ? 'Kos ' . ucfirst($kamar->tipe_kamar) . ' ' . $kamar->nomor_kamar : null,
            // Hanya simpan path relatif, sama seperti formatBooking di BookingController
            'foto_kamar'     => $mainFoto?->url_foto,
            'tgl_mulai_sewa' => $booking?->tgl_mulai_sewa,
            'tgl_akhir_sewa' => $booking?->tgl_akhir_sewa,
        ];
    }
}