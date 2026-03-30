<?php
namespace App\Http\Controllers\API;

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
        $bookings = Booking::where('id_user', $userId)->get()
            ->map(fn($b) => $this->formatBooking($b));
        return response()->json(['success' => true, 'data' => $bookings]);
    }

    public function show($id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking tidak ditemukan.'], 404);
        }
        return response()->json(['success' => true, 'data' => $this->formatBooking($booking)]);
    }

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
            return response()->json(['success' => false, 'message' => 'Kamar tidak tersedia.'], 422);
        }

        $tglMulai   = Carbon::parse($request->tgl_mulai_sewa);
        $tglAkhir   = $tglMulai->copy()->addMonths($request->durasi_sewa_bulan);
        $totalBiaya = $kamar->harga_per_bulan * $request->durasi_sewa_bulan;

        // Hitung biaya furnitur
        $furniturItems = [];
        foreach ($request->furnitur ?? [] as $item) {
            $f = Furnitur::find($item['id_furnitur']);
            if ($f) {
                $totalBiaya += $f->harga_sewa_tambahan * $item['jumlah'] * $request->durasi_sewa_bulan;
                $furniturItems[] = ['furnitur' => $f, 'jumlah' => $item['jumlah']];
            }
        }

        $booking = Booking::create([
            'id_user'             => $request->id_user,
            'id_kamar'            => $request->id_kamar,
            'tgl_booking'         => now()->toDateString(),
            'durasi_sewa_bulan'   => $request->durasi_sewa_bulan,
            'tgl_mulai_sewa'      => $tglMulai->toDateString(),
            'tgl_akhir_sewa'      => $tglAkhir->toDateString(),
            'total_biaya_bulanan' => $totalBiaya,
            'status_booking'      => 'menunggu_pembayaran',
        ]);

        foreach ($furniturItems as $item) {
            BookingDetailFurnitur::create([
                'id_booking'  => $booking->id_booking,
                'id_furnitur' => $item['furnitur']->id_furnitur,
                'jumlah'      => $item['jumlah'],
            ]);
        }

        $kamar->update(['status_kamar' => 'terisi']);

        $tagihan = Tagihan::create([
            'id_booking'      => $booking->id_booking,
            'periode_bulan'   => $tglMulai->format('Y-m-01'),
            'nominal_dasar'   => $totalBiaya,
            'nominal_denda'   => 0,
            'total_tagihan'   => $totalBiaya,
            'tgl_jatuh_tempo' => $tglMulai->copy()->addDays(7)->toDateString(),
            'status_tagihan'  => 'belum_bayar',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil dibuat.',
            'data'    => [
                'id_booking'  => $booking->id_booking,
                'id_tagihan'  => $tagihan->id_tagihan,
                'total_biaya' => $totalBiaya,
            ],
        ], 201);
    }

    public function batal($id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking tidak ditemukan.'], 404);
        }
        if ($booking->status_booking !== 'menunggu_pembayaran') {
            return response()->json(['success' => false, 'message' => 'Booking tidak dapat dibatalkan.'], 422);
        }
        $booking->update(['status_booking' => 'batal']);
        Kamar::where('id_kamar', $booking->id_kamar)->update(['status_kamar' => 'tersedia']);
        return response()->json(['success' => true, 'message' => 'Booking berhasil dibatalkan.']);
    }

    private function formatBooking(Booking $b): array
    {
        $kamar    = $b->kamar;
        $mainFoto = $kamar?->galeri()->where('is_main', 1)->first();
        $tagihan  = $b->tagihan()->latest('id_tagihan')->first();

        return [
            'id_booking'          => $b->id_booking,
            'id_user'             => $b->id_user,
            'id_kamar'            => $b->id_kamar,
            'tgl_booking'         => $b->tgl_booking,
            'durasi_sewa_bulan'   => $b->durasi_sewa_bulan,
            'tgl_mulai_sewa'      => $b->tgl_mulai_sewa,
            'tgl_akhir_sewa'      => $b->tgl_akhir_sewa,
            'total_biaya_bulanan' => $b->total_biaya_bulanan,
            'status_booking'      => $b->status_booking,
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