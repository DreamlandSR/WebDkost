<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $thirtyDaysAgo = $now->copy()->subDays(30);
        $prevThirtyDaysAgo = $now->copy()->subDays(60);

        // Total Kamar (pengganti products)
        $totalProduk = DB::table('kamar')->count();
        $prevProduk = 0;

        // Total Register user baru 30 hari
        $totalRegister = DB::table('users')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->count();
        $prevRegister = DB::table('users')
            ->whereBetween('created_at', [$prevThirtyDaysAgo, $thirtyDaysAgo])
            ->count();

        // Total Booking selesai 30 hari (pengganti sold)
        $totalSold = DB::table('booking')
            ->where('status_booking', 'selesai')
            ->where('tgl_booking', '>=', $thirtyDaysAgo)
            ->count();
        $prevSold = DB::table('booking')
            ->where('status_booking', 'selesai')
            ->whereBetween('tgl_booking', [$prevThirtyDaysAgo, $thirtyDaysAgo])
            ->count();

        // Total Pembayaran 30 hari
        $totalPembayaran = DB::table('pembayaran')
            ->where('status_pembayaran', 'settlement')
            ->where('tgl_bayar', '>=', $thirtyDaysAgo)
            ->sum('jumlah_bayar');
        $prevPembayaran = DB::table('pembayaran')
            ->where('status_pembayaran', 'settlement')
            ->whereBetween('tgl_bayar', [$prevThirtyDaysAgo, $thirtyDaysAgo])
            ->sum('jumlah_bayar');

        // Hitung pertumbuhan (%)
        $growth = function ($current, $previous) {
            if ($previous == 0) return $current > 0 ? 100 : 0;
            return round((($current - $previous) / $previous) * 100, 2);
        };

        $growthProduk     = $growth($totalProduk, $prevProduk);
        $growthRegister   = $growth($totalRegister, $prevRegister);
        $growthSold       = $growth($totalSold, $prevSold);
        $growthPembayaran = $growth($totalPembayaran, $prevPembayaran);

        // Kamar terlaris (pengganti produk terlaris)
        $produkTerlaris = DB::table('booking')
            ->join('kamar', 'booking.id_kamar', '=', 'kamar.id_kamar')
            ->select('kamar.nomor_kamar as nama_produk', DB::raw('COUNT(booking.id_booking) as total_terjual'))
            ->where('booking.status_booking', 'selesai')
            ->groupBy('kamar.id_kamar', 'kamar.nomor_kamar')
            ->orderByDesc('total_terjual')
            ->limit(6)
            ->get();

        // Total omset keseluruhan
        $totalOmsetKeseluruhan = DB::table('pembayaran')
            ->where('status_pembayaran', 'settlement')
            ->sum('jumlah_bayar');

        // Kamar favorite (pengganti product favorite)
        $productFavorite = DB::table('booking')
            ->join('kamar', 'booking.id_kamar', '=', 'kamar.id_kamar')
            ->select(
                'kamar.id_kamar',
                'kamar.nomor_kamar as nama_produk',
                'kamar.tipe_kamar',
                DB::raw('COUNT(booking.id_booking) as total_terjual')
            )
            ->groupBy('kamar.id_kamar', 'kamar.nomor_kamar', 'kamar.tipe_kamar')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                // Cek apakah ada foto dari galeri_kamar
                $foto = DB::table('galeri_kamar')
                    ->where('id_kamar', $item->id_kamar)
                    ->where('is_main', 1)
                    ->first();

                $item->image_base64 = $foto
                    ? asset('storage/' . $foto->url_foto)
                    : asset('img/default.jpg');

                return $item;
            });

        // Chart booking selesai per bulan
        $completedOrders = DB::table('booking')
            ->selectRaw('MONTH(tgl_booking) as bulan, COUNT(*) as total')
            ->where('status_booking', 'selesai')
            ->groupByRaw('MONTH(tgl_booking)')
            ->orderByRaw('MONTH(tgl_booking)')
            ->get();

        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $orderCountPerMonth = array_fill(0, 12, 0);

        foreach ($completedOrders as $data) {
            $bulanIndex = $data->bulan - 1;
            if ($bulanIndex >= 0 && $bulanIndex < 12) {
                $orderCountPerMonth[$bulanIndex] = (int) $data->total;
            }
        }

        if (max($orderCountPerMonth) === 0) {
            $orderCountPerMonth = array_map(fn() => rand(1, 5), $orderCountPerMonth);
        }

        $growthData = [
            'labels' => $labels,
            'orders' => $orderCountPerMonth,
        ];

        return view('dashboard.admin', compact(
            'totalProduk',
            'totalRegister',
            'totalSold',
            'totalPembayaran',
            'growthProduk',
            'growthRegister',
            'growthSold',
            'growthPembayaran',
            'produkTerlaris',
            'totalOmsetKeseluruhan',
            'productFavorite',
            'growthData'
        ));
    }

    public function produkTerlaris()
    {
        $produkTerlaris = DB::table('booking')
            ->join('kamar', 'booking.id_kamar', '=', 'kamar.id_kamar')
            ->select('kamar.nomor_kamar as nama_produk', DB::raw('COUNT(booking.id_booking) as total_terjual'))
            ->where('booking.status_booking', 'selesai')
            ->groupBy('kamar.id_kamar', 'kamar.nomor_kamar')
            ->orderByDesc('total_terjual')
            ->get();

        return view('dashboard.terlaris', compact('produkTerlaris'));
    }
}
