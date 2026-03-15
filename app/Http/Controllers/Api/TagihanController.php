<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;

class TagihanController extends Controller
{
    public function indexByBooking($bookingId)
    {
        $list = Tagihan::where('id_booking', $bookingId)
            ->orderByDesc('periode_bulan')->get();
        return response()->json(['success' => true, 'data' => $list]);
    }

    public function show($id)
    {
        $tagihan = Tagihan::find($id);
        if (!$tagihan) {
            return response()->json(['success' => false, 'message' => 'Tagihan tidak ditemukan.'], 404);
        }
        return response()->json(['success' => true, 'data' => $tagihan]);
    }
}