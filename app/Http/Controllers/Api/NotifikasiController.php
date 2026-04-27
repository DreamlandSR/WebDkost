<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    // GET /api/notifikasi
    // Ambil semua notifikasi milik user yang login
    public function index(Request $request): JsonResponse
    {
        $notifikasis = Notifikasi::byUser($request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data'              => $notifikasis,
            'jumlah_belum_baca' => $notifikasis->where('sudah_dibaca', false)->count(),
        ]);
    }

    // POST /api/notifikasi/{id}/baca
    // Tandai 1 notifikasi sudah dibaca
    public function tandaiBaca(Request $request, int $id): JsonResponse
    {
        $notif = Notifikasi::byUser($request->user()->id)->findOrFail($id);
        $notif->update([
            'sudah_dibaca' => true,
            'dibaca_at'    => now(),
        ]);

        return response()->json(['message' => 'Berhasil ditandai dibaca']);
    }

    // POST /api/notifikasi/baca-semua
    // Tandai semua notifikasi sudah dibaca
    public function tandaiSemuaBaca(Request $request): JsonResponse
    {
        Notifikasi::byUser($request->user()->id)
            ->belumDibaca()
            ->update([
                'sudah_dibaca' => true,
                'dibaca_at'    => now(),
            ]);

        return response()->json(['message' => 'Semua notifikasi telah dibaca']);
    }

    // POST /api/fcm-token
    // Flutter kirim FCM token setelah login / token diperbarui
    public function simpanFcmToken(Request $request): JsonResponse
    {
        $request->validate(['fcm_token' => 'required|string']);

        $request->user()->update(['fcm_token' => $request->fcm_token]);

        return response()->json(['message' => 'FCM token tersimpan']);
    }
}