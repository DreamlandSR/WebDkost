<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kamar;
use App\Models\GaleriKamar;
use App\Models\FasilitasKamar;
use App\Models\Furnitur;

class DKostSeeder extends Seeder
{
    public function run(): void
    {
        // ── Furnitur ───────────────────────────────────────
        $furniturList = [
            ['nama_furnitur' => 'Lemari',        'jumlah' => 10, 'harga_sewa_tambahan' => 50000],
            ['nama_furnitur' => 'Meja Belajar',  'jumlah' => 10, 'harga_sewa_tambahan' => 30000],
            ['nama_furnitur' => 'Kasur',         'jumlah' => 10, 'harga_sewa_tambahan' => 100000],
            ['nama_furnitur' => 'Kipas Angin',   'jumlah' => 10, 'harga_sewa_tambahan' => 40000],
            ['nama_furnitur' => 'Rak Susun',     'jumlah' => 10, 'harga_sewa_tambahan' => 30000],
            ['nama_furnitur' => 'AC',            'jumlah' => 5,  'harga_sewa_tambahan' => 150000],
        ];
        foreach ($furniturList as $f) {
            Furnitur::create($f);
        }

        // ── Kamar ──────────────────────────────────────────
        $kamarList = [
            [
                'nomor' => '01', 'tipe' => 'biasa',
                'deskripsi' => 'Kamar biasa yang nyaman dengan fasilitas standar.',
                'harga' => 300000,
                'fasilitas' => ['Kamar Mandi Dalam', 'WiFi', 'Listrik'],
                'foto_url' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=400',
            ],
            [
                'nomor' => '02', 'tipe' => 'biasa',
                'deskripsi' => 'Kamar biasa dengan pencahayaan yang baik.',
                'harga' => 300000,
                'fasilitas' => ['Kamar Mandi Luar', 'WiFi', 'Listrik'],
                'foto_url' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=400',
            ],
            [
                'nomor' => '03', 'tipe' => 'sedang',
                'deskripsi' => 'Kamar sedang dengan AC dan kamar mandi dalam.',
                'harga' => 600000,
                'fasilitas' => ['Kamar Mandi Dalam', 'AC', 'WiFi', 'Listrik', 'Meja Kursi'],
                'foto_url' => 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=400',
            ],
            [
                'nomor' => '04', 'tipe' => 'sedang',
                'deskripsi' => 'Kamar sedang nyaman dengan lemari besar.',
                'harga' => 600000,
                'fasilitas' => ['Kamar Mandi Dalam', 'AC', 'WiFi', 'Listrik', 'Lemari'],
                'foto_url' => 'https://images.unsplash.com/photo-1560448204-603b3fc33ddc?w=400',
            ],
            [
                'nomor' => '05', 'tipe' => 'mewah',
                'deskripsi' => 'Kamar mewah full fasilitas dengan pemandangan indah.',
                'harga' => 900000,
                'fasilitas' => ['Kamar Mandi Dalam', 'AC', 'WiFi', 'Listrik', 'Kulkas', 'TV', 'Sofa'],
                'foto_url' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=400',
            ],
            [
                'nomor' => '06', 'tipe' => 'mewah',
                'deskripsi' => 'Kamar mewah dengan desain modern dan elegan.',
                'harga' => 900000,
                'fasilitas' => ['Kamar Mandi Dalam', 'AC', 'WiFi', 'Listrik', 'Kulkas', 'TV', 'Balkon'],
                'foto_url' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=400',
            ],
        ];

        foreach ($kamarList as $data) {
            $kamar = Kamar::create([
                'nomor_kamar'     => $data['nomor'],
                'tipe_kamar'      => $data['tipe'],
                'deskripsi'       => $data['deskripsi'],
                'harga_per_bulan' => $data['harga'],
                'status_kamar'    => 'tersedia',
            ]);

            // Foto utama
            GaleriKamar::create([
                'id_kamar' => $kamar->id_kamar,
                'url_foto' => $data['foto_url'],
                'is_main'  => 1,
            ]);

            // Fasilitas
            foreach ($data['fasilitas'] as $fas) {
                FasilitasKamar::create([
                    'id_kamar'        => $kamar->id_kamar,
                    'nama_fasilitas'  => $fas,
                    'deskripsi_fasilitas' => null,
                ]);
            }
        }
    }
}