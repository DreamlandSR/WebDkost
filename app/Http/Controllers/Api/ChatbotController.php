<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function handle(Request $request)
    {
        $question = strtolower(trim($request->input('question', '')));

        if (empty($question)) {
            return response()->json(['type' => 'error', 'message' => 'Pertanyaan tidak ditemukan.']);
        }

        // Fungsi utilitas
        function contains_keywords($text, $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($text, $keyword) !== false) return true;
            }
            return false;
        }

        switch (true) {
            case contains_keywords($question, ['stok', 'stock', 'tersedia', 'ketersediaan']):
                return response()->json([
                    'type' => 'stock_steps',
                    'message' => "Berikut cara mengecek stok barang:",
                    'steps' => [
                        "1. Buka halaman beranda aplikasi",
                        "2. Pilih menu 'Produk' atau ketuk ikon pencarian",
                        "3. Ketik nama produk yang ingin Anda cek",
                        "4. Hasil pencarian akan menampilkan produk beserta status ketersediaannya",
                        "5. Anda juga bisa melihat jumlah stok pada halaman detail produk"
                    ]
                ]);

            case contains_keywords($question, ['bayar', 'pembayaran']):
                return response()->json([
                    'type' => 'payment_steps',
                    'message' => "Berikut cara melakukan pembayaran:",
                    'steps' => [
                        "1. Pilih barang yang akan dibeli",
                        "2. Lakukan pemesanan",
                        "3. Pilih metode pembayaran",
                        "4. Selesaikan pembayaran sesuai instruksi",
                        "5. Status pesanan akan diperbarui setelah pembayaran berhasil"
                    ]
                ]);

            case contains_keywords($question, ['resi', 'cek resi', 'tracking', 'lacak']):
                return response()->json([
                    'type' => 'tracking_steps',
                    'message' => "Berikut cara melakukan cek resi:",
                    'steps' => [
                        "1. Salin resi pada menu 'detail pesanan'",
                        "2. Buka browser, ketik 'cekresi.com'",
                        "3. Masukkan nomor resi dan klik tombol",
                        "4. Pilih ekspedisi untuk melihat info pengiriman"
                    ]
                ]);

            case contains_keywords($question, ['kontak', 'contact', 'hubungi']):
                return response()->json([
                    'type' => 'contact_info',
                    'message' => "Berikut informasi kontak kami:",
                    'contacts' => [
                        [
                            'name' => 'UMKM Batik Nusantara',
                            'phone' => '082112345678',
                            'email' => 'info@umkmbatik.com',
                            'hours' => 'Senin-Jumat: 08.00-17.00'
                        ],
                        [
                            'name' => 'Layanan Pelanggan',
                            'phone' => '082187654321',
                            'email' => 'cs@umkmbatik.com',
                            'hours' => 'Setiap hari: 08.00-20.00'
                        ]
                    ]
                ]);

            case contains_keywords($question, ['tentang', 'about', 'profil']):
                return response()->json([
                    'type' => 'about',
                    'message' => "Tentang UMKM Batik Nusantara",
                    'content' => "UMKM Batik Nusantara didirikan pada tahun 2015... [cut for brevity]"
                ]);

            case ($question === 'menu' || contains_keywords($question, ['bantuan', 'help'])):
                return response()->json([
                    'type' => 'help_menu',
                    'message' => "Berikut menu bantuan yang tersedia:",
                    'menu' => [
                        "stok - Cara cek stok produk",
                        "bayar - Cara melakukan pembayaran",
                        "resi - Cara cek resi pengiriman",
                        "kontak - Informasi kontak penjual",
                        "tentang - Tentang UMKM Batik"
                    ]
                ]);

            default:
                return response()->json([
                    'type' => 'unknown',
                    'message' => "Maaf, saya belum dapat memahami pertanyaan Anda. Silakan ketik 'menu' untuk melihat bantuan."
                ]);
        }
    }
}
