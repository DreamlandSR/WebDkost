<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\PendapatanPerBulanExport;
use App\Exports\PengeluaranPerBulanExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        // 1. Kamar Tersedia & Terisi
        $totalKamarTersedia = DB::table('kamar')->where('status_kamar', 'tersedia')->count();
        $totalKamarTerisi = DB::table('kamar')->where('status_kamar', 'terisi')->count();

        // 2. Pendapatan bulanan & Growth (Dibanding bulan lalu)
        $totalPembayaran = DB::table('pendapatan')
            ->whereMonth('tgl_diterima', $currentMonth)
            ->whereYear('tgl_diterima', $currentYear)
            ->sum('nominal');

        $prevMonth = $now->copy()->subMonth();
        $prevPembayaran = DB::table('pendapatan')
            ->whereMonth('tgl_diterima', $prevMonth->month)
            ->whereYear('tgl_diterima', $prevMonth->year)
            ->sum('nominal');

        $growthPembayaran = 0;
        if ($prevPembayaran > 0) {
            $growthPembayaran = round((($totalPembayaran - $prevPembayaran) / $prevPembayaran) * 100, 1);
        } else if ($totalPembayaran > 0) {
            $growthPembayaran = 100;
        } else {
            $growthPembayaran = 0;
        }

        // 3. Keluhan Terbaru (3 data)
        $keluhanTerbaru = DB::table('keluhan')
            ->join('users', 'keluhan.id_user', '=', 'users.id_user')
            ->join('kamar', 'keluhan.id_kamar', '=', 'kamar.id_kamar')
            ->select('users.nama', 'kamar.nomor_kamar', 'keluhan.deskripsi_masalah', 'keluhan.tgl_lapor')
            ->whereIn('keluhan.status_keluhan', ['pending', 'diproses'])
            ->orderByDesc('keluhan.tgl_lapor')
            ->limit(3)
            ->get();

        // 4. Pengeluaran Bulanan
        $pengeluaranBulanan = DB::table('pengeluaran')
            ->select('kategori', DB::raw('SUM(nominal) as nominal'))
            ->whereMonth('tgl_transaksi', $currentMonth)
            ->whereYear('tgl_transaksi', $currentYear)
            ->groupBy('kategori')
            ->orderByDesc('nominal')
            ->get();

        // 5. Chart Pertumbuhan Pendapatan (Per bulan dalam tahun ini, dalam Nominal Juta)
        $pendapatanPerBulan = DB::table('pendapatan')
            ->selectRaw('MONTH(tgl_diterima) as bulan, SUM(nominal) / 1000000 as total_juta')
            ->whereYear('tgl_diterima', $currentYear)
            ->groupByRaw('MONTH(tgl_diterima)')
            ->orderByRaw('MONTH(tgl_diterima)')
            ->get();

        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $orderCountPerMonth = array_fill(0, 12, 0);

        foreach ($pendapatanPerBulan as $data) {
            $bulanIndex = $data->bulan - 1;
            if ($bulanIndex >= 0 && $bulanIndex < 12) {
                // Return di format Juta agar grafik tidak terpotong panjang angkanya
                $orderCountPerMonth[$bulanIndex] = round($data->total_juta, 1);
            }
        }

        $growthData = [
            'labels' => $labels,
            'orders' => $orderCountPerMonth,
        ];

        return view('dashboard.admin', compact(
            'totalKamarTersedia',
            'totalKamarTerisi',
            'totalPembayaran',
            'growthPembayaran',
            'prevMonth',
            'keluhanTerbaru',
            'pengeluaranBulanan',
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

    public function exportPendapatan(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)
            ->locale('id')
            ->translatedFormat('F_Y');

        $fileName = 'Laporan_Pendapatan_' . $namaBulan . '.xlsx';

        return Excel::download(new PendapatanPerBulanExport($bulan, $tahun), $fileName);
    }

    public function exportPengeluaran(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)
            ->locale('id')
            ->translatedFormat('F_Y');

        $fileName = 'Laporan_Pengeluaran_' . $namaBulan . '.xlsx';

        return Excel::download(new PengeluaranPerBulanExport($bulan, $tahun), $fileName);
    }
}

