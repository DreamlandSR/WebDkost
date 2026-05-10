<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotifikasiController extends Controller
{
    // GET /api/notifikasi
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

    // POST /api/onesignal-player-id
    // Dipanggil Flutter setelah login atau saat Player ID diperbarui
    public function simpanPlayerId(Request $request): JsonResponse
    {
        Log::info('simpanPlayerId dipanggil', [
            'user_id' => $request->user()?->id,
            'ip'      => $request->ip(),
        ]);

        $request->validate([
            'player_id' => 'required|string|max:255',
        ]);

        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user->update(['onesignal_player_id' => $request->player_id]);

        return response()->json(['message' => 'OneSignal Player ID tersimpan']);
    }
}