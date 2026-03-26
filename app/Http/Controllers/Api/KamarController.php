<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    public function index()
    {
        $kamarList = Kamar::all()->map(function ($kamar) {
            $mainFoto = $kamar->galeri()->where('is_main', 1)->first();
            $avgRating = \App\Models\Review::where('id_kamar', $kamar->id_kamar)->avg('rating');
            return [
                'id_kamar'        => $kamar->id_kamar,
                'nomor_kamar'     => $kamar->nomor_kamar,
                'tipe_kamar'      => $kamar->tipe_kamar,
                'deskripsi'       => $kamar->deskripsi,
                'harga_per_bulan' => $kamar->harga_per_bulan,
                'status_kamar'    => $kamar->status_kamar,
                'foto_primary' => $mainFoto ? asset('storage/' . $mainFoto->url_foto) : null,
                'rating'          => $avgRating ? round($avgRating, 1) : null,
            ];
        });

        return response()->json(['success' => true, 'data' => $kamarList]);
    }

    public function show($id)
    {
        $kamar = Kamar::find($id);
        if (!$kamar) {
            return response()->json(['success' => false, 'message' => 'Kamar tidak ditemukan.'], 404);
        }

        $mainFoto  = $kamar->galeri()->where('is_main', 1)->first();
        $avgRating = \App\Models\Review::where('id_kamar', $id)->avg('rating');

        return response()->json([
            'success' => true,
            'data'    => [
                'id_kamar'        => $kamar->id_kamar,
                'nomor_kamar'     => $kamar->nomor_kamar,
                'tipe_kamar'      => $kamar->tipe_kamar,
                'deskripsi'       => $kamar->deskripsi,
                'harga_per_bulan' => $kamar->harga_per_bulan,
                'status_kamar'    => $kamar->status_kamar,
                'foto_primary' => $mainFoto ? asset('storage/' . $mainFoto->url_foto) : null,
                'galeri' => $kamar->galeri->map(fn($g) => asset('storage/' . $g->url_foto)),
                'fasilitas'       => $kamar->fasilitas->map(fn($f) => [
                    'nama'      => $f->nama_fasilitas,
                    'deskripsi' => $f->deskripsi_fasilitas,
                ]),
                'rating'          => $avgRating ? round($avgRating, 1) : null,
            ],
        ]);
    }
}