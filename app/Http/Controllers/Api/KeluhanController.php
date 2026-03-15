<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Keluhan;
use Illuminate\Http\Request;

class KeluhanController extends Controller
{
    public function indexByUser($userId)
    {
        $list = Keluhan::where('id_user', $userId)
            ->orderByDesc('tgl_lapor')->get()
            ->map(fn($k) => [
                'id_keluhan'        => $k->id_keluhan,
                'id_kamar'          => $k->id_kamar,
                'nomor_kamar'       => $k->kamar?->nomor_kamar,
                'deskripsi_masalah' => $k->deskripsi_masalah,
                'foto_bukti'        => $k->foto_bukti ? asset('storage/'.$k->foto_bukti) : null,
                'tgl_lapor'         => $k->tgl_lapor,
                'status_keluhan'    => $k->status_keluhan,
            ]);
        return response()->json(['success' => true, 'data' => $list]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user'           => 'required|exists:users,id_user',
            'id_kamar'          => 'required|exists:kamar,id_kamar',
            'deskripsi_masalah' => 'required|string|min:10',
            'foto_bukti'        => 'nullable|image|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_bukti')) {
            $fotoPath = $request->file('foto_bukti')->store('keluhan', 'public');
        }

        $keluhan = Keluhan::create([
            'id_user'           => $request->id_user,
            'id_kamar'          => $request->id_kamar,
            'deskripsi_masalah' => $request->deskripsi_masalah,
            'foto_bukti'        => $fotoPath,
            'tgl_lapor'         => now(),
            'status_keluhan'    => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Keluhan berhasil dikirim.',
            'data'    => $keluhan,
        ], 201);
    }
}