<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Keluhan;
use Illuminate\Http\Request;

class KeluhanController extends Controller
{
    private function imageToBase64($path)
    {
        $fullPath = storage_path('app/public/' . $path);
        if (!file_exists($fullPath)) return null;

        $ext      = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        $mimeMap  = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png'];
        $mimeType = $mimeMap[$ext] ?? 'image/jpeg';

        $data = base64_encode(file_get_contents($fullPath));
        return 'data:' . $mimeType . ';base64,' . $data;
    }

    private function getBookingAktif(int $userId, int $idKamar = null): ?Booking
    {
        $query = Booking::where('id_user', $userId)
            ->where('status_booking', 'aktif');

        if ($idKamar) {
            $query->where('id_kamar', $idKamar);
        }

        return $query->first();
    }

    // ── GET: Semua keluhan milik user ──────────────────────
    public function indexByUser($userId)
    {
        $list = Keluhan::where('id_user', $userId)
            ->orderByDesc('tgl_lapor')->get()
            ->map(fn($k) => [
                'id_keluhan'        => $k->id_keluhan,
                'id_kamar'          => $k->id_kamar,
                'nomor_kamar'       => $k->kamar?->nomor_kamar,
                'deskripsi_masalah' => $k->deskripsi_masalah,
                'foto_bukti'        => $k->foto_bukti
                    ? $this->imageToBase64($k->foto_bukti)
                    : null,
                'tgl_lapor'         => $k->tgl_lapor,
                'status_keluhan'    => $k->status_keluhan,
            ]);

        return response()->json(['success' => true, 'data' => $list]);
    }

    // ── POST: Buat keluhan baru ────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'id_user'           => 'required|exists:users,id_user',
            'id_kamar'          => 'required|exists:kamar,id_kamar',
            'deskripsi_masalah' => 'required|string|min:10',
            'foto_bukti'        => 'nullable|image|max:2048',
        ]);

        $bookingAktif = $this->getBookingAktif($request->id_user, $request->id_kamar);
        if (!$bookingAktif) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus memiliki kamar aktif untuk melaporkan keluhan.',
            ], 403);
        }

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

    // ── PUT: Edit keluhan (hanya jika masih pending) ───────
    public function update(Request $request, $id)
    {
        $keluhan = Keluhan::find($id);

        if (!$keluhan) {
            return response()->json([
                'success' => false,
                'message' => 'Keluhan tidak ditemukan.',
            ], 404);
        }

        if ($keluhan->status_keluhan !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Keluhan yang sudah diproses atau selesai tidak dapat diedit.',
            ], 422);
        }

        $request->validate([
            'deskripsi_masalah' => 'required|string|min:10',
            'foto_bukti'        => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto_bukti')) {
            if ($keluhan->foto_bukti) {
                \Storage::disk('public')->delete($keluhan->foto_bukti);
            }
            $keluhan->foto_bukti = $request->file('foto_bukti')
                ->store('keluhan', 'public');
        }

        $keluhan->deskripsi_masalah = $request->deskripsi_masalah;
        $keluhan->save();

        return response()->json([
            'success' => true,
            'message' => 'Keluhan berhasil diperbarui.',
            'data'    => $keluhan,
        ]);
    }

    // ── DELETE: Hapus keluhan (hanya jika masih pending) ───
    public function destroy($id)
    {
        $keluhan = Keluhan::find($id);

        if (!$keluhan) {
            return response()->json([
                'success' => false,
                'message' => 'Keluhan tidak ditemukan.',
            ], 404);
        }

        if ($keluhan->status_keluhan !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya keluhan yang masih pending yang dapat dihapus.',
            ], 422);
        }

        // Hapus foto dari storage jika ada
        if ($keluhan->foto_bukti) {
            \Storage::disk('public')->delete($keluhan->foto_bukti);
        }

        $keluhan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Keluhan berhasil dihapus.',
        ]);
    }
}