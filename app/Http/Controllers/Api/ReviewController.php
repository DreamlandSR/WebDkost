<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Kamar;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function indexByKamar($kamarId)
    {
        $reviews = Review::where('id_kamar', $kamarId)
            ->orderByDesc('tgl_review')->get()
            ->map(fn($r) => [
                'id_review'  => $r->id_review,
                'id_user'    => $r->id_user,
                'id_kamar'   => $r->id_kamar,
                'rating'     => $r->rating,
                'komentar'   => $r->komentar,
                'tgl_review' => $r->tgl_review,
                'nama'       => $r->user?->nama ?? 'Pengguna',
            ]);
        return response()->json(['success' => true, 'data' => $reviews]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user'  => 'required|exists:users,id_user',
            'id_kamar' => 'required|exists:kamar,id_kamar',
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|min:5',
        ]);

        $existing = Review::where('id_user', $request->id_user)
            ->where('id_kamar', $request->id_kamar)->first();
        if ($existing) {
            return response()->json(['success' => false, 'message' => 'Anda sudah memberikan ulasan.'], 422);
        }

        $review = Review::create([
            'id_user'    => $request->id_user,
            'id_kamar'   => $request->id_kamar,
            'rating'     => $request->rating,
            'komentar'   => $request->komentar,
            'tgl_review' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Ulasan berhasil dikirim.', 'data' => $review], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|min:5',
        ]);
        $review = Review::find($id);
        if (!$review) {
            return response()->json(['success' => false, 'message' => 'Ulasan tidak ditemukan.'], 404);
        }
        $review->update(['rating' => $request->rating, 'komentar' => $request->komentar]);
        return response()->json(['success' => true, 'message' => 'Ulasan berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $review = Review::find($id);
        if (!$review) {
            return response()->json(['success' => false, 'message' => 'Ulasan tidak ditemukan.'], 404);
        }
        $review->delete();
        return response()->json(['success' => true, 'message' => 'Ulasan berhasil dihapus.']);
    }
}