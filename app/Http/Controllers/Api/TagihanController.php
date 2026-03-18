<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;

class TagihanController extends Controller
{
    // Sudah ada — tidak diubah
    public function indexByBooking($bookingId)
    {
        $list = Tagihan::where('id_booking', $bookingId)
            ->orderByDesc('periode_bulan')->get();
        return response()->json(['success' => true, 'data' => $list]);
    }

    // Sudah ada — tidak diubah
    public function show($id)
    {
        $tagihan = Tagihan::find($id);
        if (!$tagihan) {
            return response()->json(['success' => false, 'message' => 'Tagihan tidak ditemukan.'], 404);
        }
        return response()->json(['success' => true, 'data' => $tagihan]);
    }

    // ← BARU — Ambil semua tagihan milik user yang sedang login
    public function myTagihan()
    {
        $tagihans = Tagihan::with(['booking.kamar'])
            ->whereHas('booking', function ($q) {
                $q->where('id_user', auth()->id());
            })
            ->orderBy('tgl_jatuh_tempo', 'desc')
            ->get()
            ->map(function ($tagihan) {
                return [
                    'id_tagihan'      => $tagihan->id_tagihan,
                    'nama_penyewa'    => $tagihan->booking->user->nama ?? '-',
                    'nomor_kamar'     => $tagihan->booking->kamar->nomor_kamar ?? '-',
                    'periode_bulan'   => $tagihan->periode_bulan,
                    'nominal_dasar'   => $tagihan->nominal_dasar,
                    'nominal_denda'   => $tagihan->nominal_denda,
                    'total_tagihan'   => $tagihan->total_tagihan,
                    'tgl_jatuh_tempo' => $tagihan->tgl_jatuh_tempo,
                    'status_tagihan'  => $tagihan->status_tagihan,
                ];
            });

        return response()->json([
            'status' => 'success',
            'data'   => $tagihans,
        ]);
    }
}