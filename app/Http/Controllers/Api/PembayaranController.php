<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\Pendapatan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PembayaranController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    // ── POST: Buat pembayaran + ambil Snap Token ───────────
    public function store(Request $request)
    {
        $request->validate([
            'id_tagihan' => 'required|exists:tagihan,id_tagihan',
        ]);

        $tagihan = Tagihan::with('booking.user')->find($request->id_tagihan);

        if ($tagihan->status_tagihan === 'lunas') {
            return response()->json([
                'success' => false,
                'message' => 'Tagihan sudah lunas.'
            ], 422);
        }

        // Cek apakah sudah ada pembayaran pending dengan snap_token
        $existing = Pembayaran::where('id_tagihan', $tagihan->id_tagihan)
            ->where('status_pembayaran', 'pending')
            ->whereNotNull('snap_token')
            ->latest('id_pembayaran')
            ->first();

        if ($existing) {
            return response()->json([
                'success'       => true,
                'message'       => 'Gunakan token yang sudah ada.',
                'data' => [
                    'id_pembayaran' => $existing->id_pembayaran,
                    'order_id'      => $existing->order_id,
                    'snap_token'    => $existing->snap_token,
                    'client_key'    => config('midtrans.client_key'),
                    'total'         => $existing->jumlah_bayar,
                ],
            ]);
        }

        $orderId = 'DKOST-' . $tagihan->id_tagihan . '-' . Str::upper(Str::random(6));
        $user    = $tagihan->booking->user;

        // Parameter untuk Midtrans Snap
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $tagihan->total_tagihan,
            ],
            'customer_details' => [
                'first_name' => $user->Nama ?? 'User',
                'email'      => $user->Email ?? 'user@dkost.com',
                'phone'      => $user->No_telepon ?? '08000000000',
            ],
            'item_details' => [[
                'id'       => 'tagihan-' . $tagihan->id_tagihan,
                'price'    => (int) $tagihan->total_tagihan,
                'quantity' => 1,
                'name'     => 'Tagihan Kost Periode ' . ($tagihan->periode_bulan ?? '-'),
            ]],
        ];

        // Ambil snap token dari Midtrans
        $snapToken = Snap::getSnapToken($params);

        // Simpan ke database
        $pembayaran = Pembayaran::create([
            'id_tagihan'        => $tagihan->id_tagihan,
            'order_id'          => $orderId,
            'snap_token'        => $snapToken,
            'jumlah_bayar'      => $tagihan->total_tagihan,
            'status_pembayaran' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dibuat.',
            'data'    => [
                'id_pembayaran' => $pembayaran->id_pembayaran,
                'order_id'      => $orderId,
                'snap_token'    => $snapToken,
                'client_key'    => config('midtrans.client_key'),
                'total'         => $tagihan->total_tagihan,
            ],
        ]);
    }

    // ── GET: Detail pembayaran by id ───────────────────────
    public function show($id)
    {
        $pembayaran = Pembayaran::find($id);
        if (!$pembayaran) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }
        return response()->json(['success' => true, 'data' => $pembayaran]);
    }

    // ── GET: Cek status by id_tagihan ──────────────────────
    public function checkStatus($idTagihan)
    {
        $pembayaran = Pembayaran::where('id_tagihan', $idTagihan)
            ->latest('id_pembayaran')
            ->first();

        if (!$pembayaran) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada pembayaran.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'id_pembayaran'          => $pembayaran->id_pembayaran,
                'order_id'               => $pembayaran->order_id,
                'snap_token'             => $pembayaran->snap_token,
                'status_pembayaran'      => $pembayaran->status_pembayaran,
                'metode_pembayaran'      => $pembayaran->metode_pembayaran,
                'jumlah_bayar'           => $pembayaran->jumlah_bayar,
                'tgl_bayar'              => $pembayaran->tgl_bayar,
                'transaction_id_gateway' => $pembayaran->transaction_id_gateway,
            ],
        ]);
    }

    // ── POST: Webhook notifikasi dari Midtrans ─────────────
    public function webhook(Request $request)
    {
        $notification = new Notification();

        $orderId           = $notification->order_id;
        $statusCode        = $notification->status_code;
        $grossAmount       = $notification->gross_amount;
        $transactionStatus = $notification->transaction_status;
        $paymentType       = $notification->payment_type;
        $transactionId     = $notification->transaction_id;
        $fraudStatus       = $notification->fraud_status ?? null;

        // Validasi signature
        $signatureKey = hash('sha512',
            $orderId . $statusCode . $grossAmount . config('midtrans.server_key')
        );

        if ($signatureKey !== $notification->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $pembayaran = Pembayaran::where('order_id', $orderId)->first();
        if (!$pembayaran) {
            return response()->json(['message' => 'Pembayaran tidak ditemukan'], 404);
        }

        // Tentukan status
        $statusPembayaran = 'pending';
        $statusTagihan    = 'belum_bayar';

        if ($transactionStatus === 'capture') {
            $statusPembayaran = $fraudStatus === 'accept' ? 'settlement' : 'deny';
        } elseif ($transactionStatus === 'settlement') {
            $statusPembayaran = 'settlement';
            $statusTagihan    = 'lunas';
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $statusPembayaran = $transactionStatus;
            $statusTagihan    = 'belum_bayar';
        }

        // Update pembayaran
        $pembayaran->update([
            'transaction_id_gateway' => $transactionId,
            'status_pembayaran'      => $statusPembayaran,
            'metode_pembayaran'      => $paymentType,
            'tgl_bayar'              => now(),
        ]);

        // Update tagihan
        $tagihan = $pembayaran->tagihan;
        $tagihan->update(['status_tagihan' => $statusTagihan]);

        // Jika lunas — update booking + catat pendapatan
        if ($statusPembayaran === 'settlement') {
            $tagihan->booking->update(['status_booking' => 'aktif']);

            Pendapatan::firstOrCreate(
                ['id_pembayaran' => $pembayaran->id_pembayaran],
                [
                    'nominal'      => $grossAmount,
                    'tgl_diterima' => now()->toDateString(),
                ]
            );
        }

        return response()->json(['success' => true]);
    }
}