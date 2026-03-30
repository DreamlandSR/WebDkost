<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\Pendapatan;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('MIDTRANS_SERVER_KEY');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    // ==========================================
    // API — Flutter request snap token
    // ==========================================
    public function createToken(Request $request)
    {
        $request->validate([
            'id_tagihan' => 'required|exists:tagihan,id_tagihan',
        ]);

        $tagihan = Tagihan::with(['booking.user', 'booking.kamar'])
                          ->findOrFail($request->id_tagihan);

        // Pastikan tagihan milik user yang login
        if ($tagihan->booking->id_user !== auth()->id()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }

        if ($tagihan->status_tagihan === 'lunas') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tagihan ini sudah lunas',
            ], 400);
        }

        $orderId = 'KOST-' . auth()->id() . '-' . time();
        $user    = $tagihan->booking->user;
        $kamar   = $tagihan->booking->kamar;

        // Buat record pembayaran
        $pembayaran = Pembayaran::create([
            'id_tagihan'   => $tagihan->id_tagihan,
            'order_id'     => $orderId,
            'jumlah_bayar' => $tagihan->total_tagihan,
            'status_pembayaran' => 'pending',
        ]);

        // Generate snap token
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $tagihan->total_tagihan,
            ],
            'customer_details' => [
                'first_name' => $user->nama,
                'email'      => $user->email,
                'phone'      => $user->no_telepon ?? '',
            ],
            'item_details' => [[
                'id'       => $orderId,
                'price'    => (int) $tagihan->total_tagihan,
                'quantity' => 1,
                'name'     => 'Sewa Kamar ' . $kamar->nomor_kamar . ' - ' .
                              date('F Y', strtotime($tagihan->periode_bulan)),
            ]],
        ];

        $snapToken = Snap::getSnapToken($params);

        $pembayaran->update(['snap_token' => $snapToken]);

        return response()->json([
            'status'     => 'success',
            'snap_token' => $snapToken,
            'snap_url'   => config('midtrans.is_production')
                ? "https://app.midtrans.com/snap/v2/vtweb/{$snapToken}"
                : "https://app.sandbox.midtrans.com/snap/v2/vtweb/{$snapToken}",
            'order_id'   => $orderId,
        ]);
    }

    // ==========================================
    // WEBHOOK — Midtrans kirim notifikasi
    // ==========================================
    public function notification(Request $request)
    {
        $notification      = new Notification();
        $orderId           = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus       = $notification->fraud_status;
        $paymentType       = $notification->payment_type;
        $transactionId     = $notification->transaction_id;

        $pembayaran = Pembayaran::where('order_id', $orderId)->first();

        if (!$pembayaran) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($transactionStatus == 'capture' && $fraudStatus == 'accept') {
            $this->tandaiLunas($pembayaran, $paymentType, $transactionId);

        } elseif ($transactionStatus == 'settlement') {
            $this->tandaiLunas($pembayaran, $paymentType, $transactionId);

        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $pembayaran->update([
                'status_pembayaran' => $transactionStatus,
            ]);
            // Kembalikan status tagihan ke belum_bayar
            $pembayaran->tagihan->update([
                'status_tagihan' => 'belum_bayar',
            ]);

        } elseif ($transactionStatus == 'pending') {
            $pembayaran->update(['status_pembayaran' => 'pending']);
        }

        return response()->json(['status' => 'ok']);
    }

    // ==========================================
    // Helper — Tandai Lunas
    // ==========================================
    private function tandaiLunas($pembayaran, $paymentType, $transactionId)
    {
        $pembayaran->update([
            'status_pembayaran'      => 'settlement',
            'metode_pembayaran'      => $paymentType,
            'transaction_id_gateway' => $transactionId,
            'tgl_bayar'              => now(),
        ]);

        // Update status tagihan → lunas
        $pembayaran->tagihan->update([
            'status_tagihan' => 'lunas',
        ]);

        // Catat ke tabel pendapatan
        Pendapatan::create([
            'id_pembayaran' => $pembayaran->id_pembayaran,
            'nominal'       => $pembayaran->jumlah_bayar,
            'tgl_diterima'  => now()->toDateString(),
        ]);
    }
}