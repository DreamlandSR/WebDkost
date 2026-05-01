<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotifikasiController extends Controller
{
    // GET /api/notifikasi
    public function index(Request $request): JsonResponse
    {
        $notifikasis = Notifikasi::byUser($request->user()->id_user)
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
        $notif = Notifikasi::byUser($request->user()->id_user)->findOrFail($id);
        $notif->update([
            'sudah_dibaca' => true,
            'dibaca_at'    => now(),
        ]);

        return response()->json(['message' => 'Berhasil ditandai dibaca']);
    }

    // POST /api/notifikasi/baca-semua
    public function tandaiSemuaBaca(Request $request): JsonResponse
    {
        Notifikasi::byUser($request->user()->id_user)
            ->belumDibaca()
            ->update([
                'sudah_dibaca' => true,
                'dibaca_at'    => now(),
            ]);

        return response()->json(['message' => 'Semua notifikasi telah dibaca']);
    }

    // POST /api/onesignal/login
    // Flutter memanggil ini setelah login supaya OneSignal tahu siapa usernya
    public function setExternalId(Request $request): JsonResponse
    {
        $request->validate([
            'onesignal_player_id' => 'required|string', // subscription_id dari Flutter SDK
        ]);

        $user     = $request->user();
        $playerId = $request->onesignal_player_id;

        // Panggil OneSignal API untuk set external_id = id_user
        $response = Http::withHeaders([
            'Authorization' => 'Key ' . config('services.onesignal.rest_api_key'),
            'Content-Type'  => 'application/json',
        ])->patch("https://onesignal.com/api/v1/apps/" . config('services.onesignal.app_id') . "/subscriptions/{$playerId}", [
            'subscription' => [
                'external_id' => (string) $user->id_user,
            ],
        ]);

        if ($response->failed()) {
            Log::error('OneSignal setExternalId gagal', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return response()->json(['message' => 'Gagal menghubungkan ke OneSignal'], 500);
        }

        return response()->json(['message' => 'OneSignal terhubung']);
    }
}