<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Kamar;
use App\Models\GaleriKamar;
use App\Models\FasilitasKamar;
use App\Models\Furnitur;

class DKostSeeder extends Seeder
{
    public function run(): void
    {
        // ══════════════════════════════════════════════════════
        // 1. USERS
        // ══════════════════════════════════════════════════════
        $users = [
            [
                'nama'              => 'Admin D\'Kost',
                'email'             => 'admin@dkost.com',
                'password'          => Hash::make('Admin@123'),
                'no_telepon'        => '081234567890',
                'alamat'            => 'Jl. Kalimantan No. 1, Jember',
                'role'              => 'admin',
                'email_verified_at' => now(),
            ],
            [
                'nama'              => 'Budi Santoso',
                'email'             => 'budi@gmail.com',
                'password'          => Hash::make('Penyewa@123'),
                'no_telepon'        => '082233445566',
                'alamat'            => 'Jl. Mastrip No. 12, Jember',
                'role'              => 'penyewa',
                'email_verified_at' => now(),
            ],
            [
                'nama'              => 'Siti Rahayu',
                'email'             => 'siti@gmail.com',
                'password'          => Hash::make('Penyewa@123'),
                'no_telepon'        => '083344556677',
                'alamat'            => 'Jl. Gajah Mada No. 5, Jember',
                'role'              => 'penyewa',
                'email_verified_at' => now(),
            ],
            [
                'nama'              => 'Andi Wijaya',
                'email'             => 'andi@gmail.com',
                'password'          => Hash::make('Penyewa@123'),
                'no_telepon'        => '085566778899',
                'alamat'            => 'Jl. PB Sudirman No. 22, Jember',
                'role'              => 'penyewa',
                'email_verified_at' => now(),
            ],
            [
                'nama'              => 'Dewi Lestari',
                'email'             => 'dewi@gmail.com',
                'password'          => Hash::make('Penyewa@123'),
                'no_telepon'        => '087788990011',
                'alamat'            => 'Jl. Imam Bonjol No. 8, Jember',
                'role'              => 'penyewa',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $u) {
            User::create($u);
        }

        // ══════════════════════════════════════════════════════
        // 2. FURNITUR
        // ══════════════════════════════════════════════════════
        $furniturList = [
            ['nama_furnitur' => 'Lemari',       'jumlah' => 10, 'harga_sewa_tambahan' => 50000],
            ['nama_furnitur' => 'Meja Belajar', 'jumlah' => 10, 'harga_sewa_tambahan' => 30000],
            ['nama_furnitur' => 'Kasur',        'jumlah' => 10, 'harga_sewa_tambahan' => 100000],
            ['nama_furnitur' => 'Kipas Angin',  'jumlah' => 10, 'harga_sewa_tambahan' => 40000],
            ['nama_furnitur' => 'Rak Susun',    'jumlah' => 10, 'harga_sewa_tambahan' => 30000],
            ['nama_furnitur' => 'AC',           'jumlah' => 5,  'harga_sewa_tambahan' => 150000],
        ];

        foreach ($furniturList as $f) {
            Furnitur::create($f);
        }

        // ══════════════════════════════════════════════════════
        // 3. KAMAR + GALERI + FASILITAS
        // ══════════════════════════════════════════════════════
        $kamarList = [
            // ── BIASA ──────────────────────────────────────────
            [
                'nomor_kamar'     => 'A01',
                'tipe_kamar'      => 'biasa',
                'deskripsi'       => 'Kamar biasa yang nyaman dengan pencahayaan alami dan ventilasi yang baik. Cocok untuk mahasiswa.',
                'harga_per_bulan' => 300000,
                'status_kamar'    => 'tersedia',
                'fasilitas' => [
                    ['nama' => 'Kamar Mandi Luar', 'deskripsi' => 'Kamar mandi bersama di lantai yang sama'],
                    ['nama' => 'WiFi',              'deskripsi' => 'Internet 20 Mbps'],
                    ['nama' => 'Listrik',           'deskripsi' => 'Listrik termasuk dalam harga sewa'],
                ],
                'galeri' => [
                    ['url' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800', 'is_main' => 1],
                    ['url' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=800', 'is_main' => 0],
                    ['url' => 'https://images.unsplash.com/photo-1484101403633-562f891dc89a?w=800', 'is_main' => 0],
                ],
            ],
            [
                'nomor_kamar'     => 'A02',
                'tipe_kamar'      => 'biasa',
                'deskripsi'       => 'Kamar biasa dengan jendela besar menghadap taman. Suasana tenang dan asri.',
                'harga_per_bulan' => 300000,
                'status_kamar'    => 'tersedia',
                'fasilitas' => [
                    ['nama' => 'Kamar Mandi Luar', 'deskripsi' => 'Kamar mandi bersama di lantai yang sama'],
                    ['nama' => 'WiFi',              'deskripsi' => 'Internet 20 Mbps'],
                    ['nama' => 'Listrik',           'deskripsi' => 'Listrik termasuk dalam harga sewa'],
                    ['nama' => 'Parkir Motor',      'deskripsi' => 'Area parkir motor aman'],
                ],
                'galeri' => [
                    ['url' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800', 'is_main' => 1],
                    ['url' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800', 'is_main' => 0],
                ],
            ],
            [
                'nomor_kamar'     => 'A03',
                'tipe_kamar'      => 'biasa',
                'deskripsi'       => 'Kamar biasa lantai 1 dekat pintu masuk. Praktis dan mudah diakses.',
                'harga_per_bulan' => 300000,
                'status_kamar'    => 'terisi',
                'fasilitas' => [
                    ['nama' => 'Kamar Mandi Luar', 'deskripsi' => 'Kamar mandi bersama'],
                    ['nama' => 'WiFi',              'deskripsi' => 'Internet 20 Mbps'],
                    ['nama' => 'Listrik',           'deskripsi' => 'Listrik termasuk dalam harga sewa'],
                ],
                'galeri' => [
                    ['url' => 'https://images.unsplash.com/photo-1484101403633-562f891dc89a?w=800', 'is_main' => 1],
                ],
            ],

            // ── SEDANG ─────────────────────────────────────────
            [
                'nomor_kamar'     => 'B01',
                'tipe_kamar'      => 'sedang',
                'deskripsi'       => 'Kamar sedang dengan AC dan kamar mandi dalam. Dilengkapi meja belajar dan lemari pakaian.',
                'harga_per_bulan' => 600000,
                'status_kamar'    => 'tersedia',
                'fasilitas' => [
                    ['nama' => 'Kamar Mandi Dalam', 'deskripsi' => 'Kamar mandi pribadi dalam kamar'],
                    ['nama' => 'AC',                 'deskripsi' => 'AC 1/2 PK hemat energi'],
                    ['nama' => 'WiFi',               'deskripsi' => 'Internet 50 Mbps'],
                    ['nama' => 'Listrik',            'deskripsi' => 'Listrik termasuk dalam harga sewa'],
                    ['nama' => 'Meja & Kursi',       'deskripsi' => 'Meja belajar dan kursi ergonomis'],
                    ['nama' => 'Lemari Pakaian',     'deskripsi' => 'Lemari 2 pintu'],
                ],
                'galeri' => [
                    ['url' => 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800', 'is_main' => 1],
                    ['url' => 'https://images.unsplash.com/photo-1560448204-603b3fc33ddc?w=800', 'is_main' => 0],
                    ['url' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=800', 'is_main' => 0],
                ],
            ],
            [
                'nomor_kamar'     => 'B02',
                'tipe_kamar'      => 'sedang',
                'deskripsi'       => 'Kamar sedang nyaman dengan lemari besar. Cocok untuk penyewa jangka panjang.',
                'harga_per_bulan' => 600000,
                'status_kamar'    => 'tersedia',
                'fasilitas' => [
                    ['nama' => 'Kamar Mandi Dalam', 'deskripsi' => 'Kamar mandi pribadi dalam kamar'],
                    ['nama' => 'AC',                 'deskripsi' => 'AC 1/2 PK hemat energi'],
                    ['nama' => 'WiFi',               'deskripsi' => 'Internet 50 Mbps'],
                    ['nama' => 'Listrik',            'deskripsi' => 'Listrik termasuk dalam harga sewa'],
                    ['nama' => 'Lemari Besar',       'deskripsi' => 'Lemari 3 pintu kapasitas besar'],
                    ['nama' => 'Parkir Motor',       'deskripsi' => 'Area parkir motor aman'],
                ],
                'galeri' => [
                    ['url' => 'https://images.unsplash.com/photo-1560448204-603b3fc33ddc?w=800', 'is_main' => 1],
                    ['url' => 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800', 'is_main' => 0],
                ],
            ],
            [
                'nomor_kamar'     => 'B03',
                'tipe_kamar'      => 'sedang',
                'deskripsi'       => 'Kamar sedang lantai 2 dengan pemandangan halaman. Tenang dan sejuk.',
                'harga_per_bulan' => 600000,
                'status_kamar'    => 'terisi',
                'fasilitas' => [
                    ['nama' => 'Kamar Mandi Dalam', 'deskripsi' => 'Kamar mandi pribadi dalam kamar'],
                    ['nama' => 'AC',                 'deskripsi' => 'AC 1/2 PK'],
                    ['nama' => 'WiFi',               'deskripsi' => 'Internet 50 Mbps'],
                    ['nama' => 'Listrik',            'deskripsi' => 'Listrik termasuk dalam harga sewa'],
                ],
                'galeri' => [
                    ['url' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=800', 'is_main' => 1],
                ],
            ],

            // ── MEWAH ──────────────────────────────────────────
            [
                'nomor_kamar'     => 'C01',
                'tipe_kamar'      => 'mewah',
                'deskripsi'       => 'Kamar mewah full fasilitas dengan interior modern. Dilengkapi kulkas, TV, dan sofa.',
                'harga_per_bulan' => 900000,
                'status_kamar'    => 'tersedia',
                'fasilitas' => [
                    ['nama' => 'Kamar Mandi Dalam', 'deskripsi' => 'Kamar mandi pribadi dengan shower'],
                    ['nama' => 'AC',                 'deskripsi' => 'AC 1 PK inverter'],
                    ['nama' => 'WiFi',               'deskripsi' => 'Internet 100 Mbps dedicated'],
                    ['nama' => 'Listrik',            'deskripsi' => 'Listrik termasuk dalam harga sewa'],
                    ['nama' => 'Kulkas',             'deskripsi' => 'Kulkas 1 pintu 100L'],
                    ['nama' => 'TV',                 'deskripsi' => 'Smart TV 32 inch'],
                    ['nama' => 'Sofa',               'deskripsi' => 'Sofa 2 dudukan'],
                    ['nama' => 'Parkir Mobil',       'deskripsi' => 'Area parkir mobil tersedia'],
                ],
                'galeri' => [
                    ['url' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=800', 'is_main' => 1],
                    ['url' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800', 'is_main' => 0],
                    ['url' => 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800', 'is_main' => 0],
                    ['url' => 'https://images.unsplash.com/photo-1560448204-603b3fc33ddc?w=800', 'is_main' => 0],
                ],
            ],
            [
                'nomor_kamar'     => 'C02',
                'tipe_kamar'      => 'mewah',
                'deskripsi'       => 'Kamar mewah dengan desain modern dan elegan. Terdapat balkon pribadi dengan pemandangan taman.',
                'harga_per_bulan' => 900000,
                'status_kamar'    => 'tersedia',
                'fasilitas' => [
                    ['nama' => 'Kamar Mandi Dalam', 'deskripsi' => 'Kamar mandi pribadi dengan bathtub'],
                    ['nama' => 'AC',                 'deskripsi' => 'AC 1 PK inverter'],
                    ['nama' => 'WiFi',               'deskripsi' => 'Internet 100 Mbps dedicated'],
                    ['nama' => 'Listrik',            'deskripsi' => 'Listrik termasuk dalam harga sewa'],
                    ['nama' => 'Kulkas',             'deskripsi' => 'Kulkas 2 pintu 150L'],
                    ['nama' => 'TV',                 'deskripsi' => 'Smart TV 40 inch'],
                    ['nama' => 'Balkon',             'deskripsi' => 'Balkon pribadi menghadap taman'],
                    ['nama' => 'Parkir Mobil',       'deskripsi' => 'Area parkir mobil tersedia'],
                ],
                'galeri' => [
                    ['url' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800', 'is_main' => 1],
                    ['url' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=800', 'is_main' => 0],
                    ['url' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800', 'is_main' => 0],
                ],
            ],
            [
                'nomor_kamar'     => 'C03',
                'tipe_kamar'      => 'mewah',
                'deskripsi'       => 'Kamar mewah corner unit dengan dua jendela besar. Pencahayaan alami maksimal dan sangat luas.',
                'harga_per_bulan' => 900000,
                'status_kamar'    => 'maintenance',
                'fasilitas' => [
                    ['nama' => 'Kamar Mandi Dalam', 'deskripsi' => 'Kamar mandi pribadi dengan shower'],
                    ['nama' => 'AC',                 'deskripsi' => 'AC 1 PK inverter'],
                    ['nama' => 'WiFi',               'deskripsi' => 'Internet 100 Mbps dedicated'],
                    ['nama' => 'Listrik',            'deskripsi' => 'Listrik termasuk dalam harga sewa'],
                    ['nama' => 'Kulkas',             'deskripsi' => 'Kulkas 1 pintu 100L'],
                    ['nama' => 'TV',                 'deskripsi' => 'Smart TV 32 inch'],
                ],
                'galeri' => [
                    ['url' => 'https://images.unsplash.com/photo-1560448204-603b3fc33ddc?w=800', 'is_main' => 1],
                ],
            ],
        ];

        foreach ($kamarList as $data) {
            $kamar = Kamar::create([
                'nomor_kamar'     => $data['nomor_kamar'],
                'tipe_kamar'      => $data['tipe_kamar'],
                'deskripsi'       => $data['deskripsi'],
                'harga_per_bulan' => $data['harga_per_bulan'],
                'status_kamar'    => $data['status_kamar'],
            ]);

            foreach ($data['galeri'] as $foto) {
                GaleriKamar::create([
                    'id_kamar' => $kamar->id_kamar,
                    'url_foto' => $foto['url'],
                    'is_main'  => $foto['is_main'],
                ]);
            }

            foreach ($data['fasilitas'] as $fas) {
                FasilitasKamar::create([
                    'id_kamar'            => $kamar->id_kamar,
                    'nama_fasilitas'      => $fas['nama'],
                    'deskripsi_fasilitas' => $fas['deskripsi'],
                ]);
            }
        }
    }
}