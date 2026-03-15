<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PembayaranController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['id_tagihan' => 'required|exists:tagihan,id_tagihan']);

        $tagihan = Tagihan::find($request->id_tagihan);
        if ($tagihan->status_tagihan === 'lunas') {
            return response()->json(['success' => false, 'message' => 'Tagihan sudah lunas.'], 422);
        }

        $orderId = 'DKOST-' . $tagihan->id_tagihan . '-' . Str::upper(Str::random(6));

        $pembayaran = Pembayaran::create([
            'id_tagihan'       => $tagihan->id_tagihan,
            'order_id'         => $orderId,
            'snap_token'       => null,
            'jumlah_bayar'     => $tagihan->total_tagihan,
            'status_pembayaran'=> 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dibuat.',
            'data'    => [
                'id_pembayaran' => $pembayaran->id_pembayaran,
                'order_id'      => $orderId,
                'total'         => $tagihan->total_tagihan,
            ],
        ]);
    }

    public function show($id)
    {
        $pembayaran = Pembayaran::find($id);
        if (!$pembayaran) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }
        return response()->json(['success' => true, 'data' => $pembayaran]);
    }
}