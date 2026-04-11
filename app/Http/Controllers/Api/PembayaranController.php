<?php
// ============================================================
// FILE: app/Http/Controllers/API/PembayaranController.php
// Update: Snap → Core API (VA, QRIS, GoPay, ShopeePay)
// ============================================================

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\Pendapatan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    // ── Midtrans base URL ──────────────────────────────────
    
    private function midtransUrl(string $path = ''): string
    {
        $base = config('midtrans.is_production')
            ? 'https://api.midtrans.com/v2'
            : 'https://api.sandbox.midtrans.com/v2';

        return $base . $path;
    }

    private function midtransHeaders(): array
    {
        return [
            'Authorization' => 'Basic ' . base64_encode(config('midtrans.server_key') . ':'),
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ];
    }

    // ══════════════════════════════════════════════════════
    // POST: Charge pembayaran (Core API)
    // Body: { id_tagihan, payment_type, bank? }
    // payment_type: bank_transfer | qris | gopay | shopeepay
    // bank: bca | bni | bri | mandiri (hanya untuk bank_transfer)
    // ══════════════════════════════════════════════════════
    public function store(Request $request)
    {
        $request->validate([
            'id_tagihan'   => 'required|exists:tagihan,id_tagihan',
            'payment_type' => 'required|string|in:bank_transfer,qris,gopay,shopeepay',
            'bank'         => 'nullable|string|in:bca,bni,bri,mandiri',
        ]);

        $tagihan = Tagihan::with('booking.user')->find($request->id_tagihan);

        // Cek sudah lunas
        if ($tagihan->status_tagihan === 'lunas') {
            return response()->json([
                'success' => false,
                'message' => 'Tagihan sudah lunas.',
            ], 422);
        }

        // Cek ada pembayaran pending dengan metode & bank sama → reuse
        $existing = Pembayaran::where('id_tagihan', $tagihan->id_tagihan)
            ->where('status_pembayaran', 'pending')
            ->where('metode_pembayaran', $request->payment_type)
            ->where('bank', $request->bank)
            ->whereNotNull('midtrans_response')
            ->latest('id_pembayaran')
            ->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'message' => 'Gunakan data pembayaran yang sudah ada.',
                'data'    => json_decode($existing->midtrans_response, true),
            ]);
        }

        // Buat order_id baru
        $orderId = 'DKOST-' . $tagihan->id_tagihan . '-' . Str::upper(Str::random(6));
        $user    = $tagihan->booking->user;
        $booking = $tagihan->booking;
        $amount  = (int) $tagihan->total_tagihan;

        // Hitung sisa waktu dari expired_at booking
        $now       = Carbon::now();
        $expiredAt = $booking->expired_at
            ? Carbon::parse($booking->expired_at)
            : $now->copy()->addHours(24);

        // Format expiry untuk Midtrans
        $sisaMenit = max((int) $now->diffInMinutes($expiredAt, false), 5);

        // ── Build payload dasar ────────────────────────────
        $payload = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $user->Nama       ?? 'User',
                'email'      => $user->Email      ?? 'user@dkost.com',
                'phone'      => $user->No_telepon ?? '08000000000',
            ],
            'item_details' => [[
                'id'       => 'tagihan-' . $tagihan->id_tagihan,
                'price'    => $amount,
                'quantity' => 1,
                'name'     => 'Tagihan Kost ' . ($tagihan->periode_bulan ?? '-'),
            ]],
            // Expiry sinkron dengan expired_at booking
            'expiry' => [
                'start_time' => $now->format('Y-m-d H:i:s O'),
                'unit'       => 'minutes',
                'duration'   => $sisaMenit,
            ],
        ];

        // ── Tambahkan detail per metode ────────────────────
        switch ($request->payment_type) {
            case 'bank_transfer':
                $payload['payment_type']  = 'bank_transfer';
                $payload['bank_transfer'] = ['bank' => $request->bank];
                break;

            case 'qris':
                $payload['payment_type'] = 'qris';
                $payload['qris']         = ['acquirer' => 'gopay'];
                break;

            case 'gopay':
                $payload['payment_type'] = 'gopay';
                $payload['gopay']        = [
                    'enable_callback' => true,
                    'callback_url'    => url('/'),
                ];
                break;

            case 'shopeepay':
                $payload['payment_type'] = 'shopeepay';
                $payload['shopeepay']    = [
                    'callback_url' => url('/'),
                ];
                break;
        }

        // ── Hit Midtrans Core API ──────────────────────────
        $response     = Http::withHeaders($this->midtransHeaders())
            ->post($this->midtransUrl('/charge'), $payload);
        $midtransData = $response->json();

        // Status code 200/201 = sukses
        $statusCode = $midtransData['status_code'] ?? '500';
        if (!in_array($statusCode, ['200', '201', '202'])) {
            return response()->json([
                'success' => false,
                'message' => $midtransData['status_message'] ?? 'Gagal membuat pembayaran.',
            ], 422);
        }

        // ── Simpan ke database ─────────────────────────────
        $pembayaran = Pembayaran::create([
            'id_tagihan'        => $tagihan->id_tagihan,
            'order_id'          => $orderId,
            'jumlah_bayar'      => $amount,
            'payment_type'      => $request->payment_type,
            'bank'              => $request->bank,
            'status_pembayaran' => 'pending',
            'midtrans_response' => json_encode($midtransData),
            // snap_token tidak dipakai lagi, bisa null
            'snap_token'        => null,
        ]);

        // ── Return Midtrans response langsung ke Flutter ───
        // Flutter akan parse sesuai payment_type
        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil dibuat.',
            'data'    => $midtransData,
        ], 201);
    }

    // ══════════════════════════════════════════════════════
    // GET: Cek status by id_tagihan
    // Dipanggil Flutter untuk polling status
    // ══════════════════════════════════════════════════════
    public function checkStatus($idTagihan)
    {
        $pembayaran = Pembayaran::where('id_tagihan', $idTagihan)
            ->latest('id_pembayaran')
            ->first();

        if (!$pembayaran) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada pembayaran.',
            ], 404);
        }

        // Cek ke Midtrans untuk status terbaru
        $response = Http::withHeaders($this->midtransHeaders())
            ->get($this->midtransUrl('/' . $pembayaran->order_id . '/status'));

        $midtransData      = $response->json();
        $transactionStatus = $midtransData['transaction_status'] ?? $pembayaran->status_pembayaran;

        // Update status lokal jika berubah
        if ($transactionStatus !== $pembayaran->status_pembayaran) {
            $pembayaran->update(['status_pembayaran' => $transactionStatus]);

            // Update tagihan jika settlement
            if (in_array($transactionStatus, ['settlement', 'capture'])) {
                Tagihan::where('id_tagihan', $idTagihan)
                    ->update(['status_tagihan' => 'lunas']);
            }
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'transaction_status' => $transactionStatus,
                'order_id'           => $pembayaran->order_id,
                'payment_type'       => $pembayaran->payment_type,
                'jumlah_bayar'       => $pembayaran->jumlah_bayar,
                'tgl_bayar'          => $pembayaran->tgl_bayar,
            ],
        ]);
    }

    // GET: Ambil pembayaran pending by id_tagihan
        public function getPending($idTagihan)
        {
            $pembayaran = Pembayaran::where('id_tagihan', $idTagihan)
                ->where('status_pembayaran', 'pending')
                ->whereNotNull('midtrans_response')
                ->latest('id_pembayaran')
                ->first();

            if (!$pembayaran) {
                // Tidak ada pending → cari metode terakhir dari pembayaran sebelumnya
                $last = Pembayaran::where('id_tagihan', $idTagihan)
                    ->whereNotNull('metode_pembayaran')
                    ->latest('id_pembayaran')
                    ->first();

                return response()->json([
                    'success'      => false,
                    'message'      => 'Tidak ada pembayaran pending.',
                    'last_method'  => $last?->metode_pembayaran,
                    'last_bank'    => $last?->bank,
                ], 404);
            }

            $midtransData = json_decode($pembayaran->midtrans_response, true);

            return response()->json([
                'success' => true,
                'data'    => $midtransData,
            ]);
        }

    // ── GET: Detail pembayaran by id ───────────────────────
    public function show($id)
    {
        $pembayaran = Pembayaran::find($id);
        if (!$pembayaran) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.',
            ], 404);
        }
        return response()->json(['success' => true, 'data' => $pembayaran]);
    }

    // ══════════════════════════════════════════════════════
    // POST: Webhook notifikasi dari Midtrans
    // Logika sama seperti sebelumnya, hanya hapus referensi snap_token
    // PENTING: exclude dari CSRF di bootstrap/app.php
    // ══════════════════════════════════════════════════════
    public function webhook(Request $request)
    {
        $data = $request->all();

        $orderId           = $data['order_id']           ?? '';
        $statusCode        = $data['status_code']        ?? '';
        $grossAmount       = $data['gross_amount']       ?? '';
        $transactionStatus = $data['transaction_status'] ?? '';
        $paymentType       = $data['payment_type']       ?? '';
        $transactionId     = $data['transaction_id']     ?? '';
        $fraudStatus       = $data['fraud_status']       ?? null;

        // Validasi signature
        $signatureKey = hash('sha512',
            $orderId . $statusCode . $grossAmount . config('midtrans.server_key')
        );

        if ($signatureKey !== ($data['signature_key'] ?? '')) {
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
            if ($statusPembayaran === 'settlement') $statusTagihan = 'lunas';
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

        // Jika lunas: aktifkan booking + catat pendapatan
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

        // Jika expire: batalkan booking
        if ($transactionStatus === 'expire') {
            $booking = $tagihan->booking;
            if ($booking && $booking->status_booking === 'menunggu_pembayaran') {
                $booking->update(['status_booking' => 'batal']);
                $booking->kamar()->update(['status_kamar' => 'tersedia']);

                // Kembalikan stok furnitur
                foreach ($booking->furniturDetails as $detail) {
                    \App\Models\Furnitur::where('id_furnitur', $detail->id_furnitur)
                        ->increment('jumlah', $detail->jumlah);
                }
            }
        }

        return response()->json(['success' => true]);
    }
}