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

        // Data 30 hari terakhir
        $totalProduk = DB::table('products')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->count();

        $totalRegister = DB::table('users')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->count();

        $totalSold = DB::table('orders')
            ->where('status', 'completed')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->count();

        $totalPembayaran = DB::table('orders')
            ->where('status', 'completed')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('total_harga');

        // Data 30 hari sebelumnya (untuk perbandingan)
        $prevProduk = DB::table('products')
            ->whereBetween('created_at', [$prevThirtyDaysAgo, $thirtyDaysAgo])
            ->count();

        $prevRegister = DB::table('users')
            ->whereBetween('created_at', [$prevThirtyDaysAgo, $thirtyDaysAgo])
            ->count();

        $prevSold = DB::table('orders')
            ->where('status', 'paid')
            ->whereBetween('created_at', [$prevThirtyDaysAgo, $thirtyDaysAgo])
            ->count();

        $prevPembayaran = DB::table('orders')
            ->where('status', 'paid')
            ->whereBetween('created_at', [$prevThirtyDaysAgo, $thirtyDaysAgo])
            ->sum('total_harga');

        // Hitung pertumbuhan (%)
        $growth = function ($current, $previous) {
            if ($previous == 0) {
                return $current > 0 ? 100 : 0;
            }
            return round((($current - $previous) / $previous) * 100, 2);
        };

        $growthProduk = $growth($totalProduk, $prevProduk);
        $growthRegister = $growth($totalRegister, $prevRegister);
        $growthSold = $growth($totalSold, $prevSold);
        $growthPembayaran = $growth($totalPembayaran, $prevPembayaran);

        // Ambil produk terlaris jika dipanggil oleh /admin/terlaris
        $produkTerlaris = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.nama as nama_produk', DB::raw('SUM(order_items.kuantitas) as total_terjual'))
            ->groupBy('products.nama')
            ->orderByDesc('total_terjual')
            ->limit(6)
            ->get();

        // Total omset keseluruhan (tanpa batasan hari)
        $totalOmsetKeseluruhan = DB::table('orders')
            ->where('status', 'completed')
            ->sum('total_harga');

        $productFavorite = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('product_images', function ($join) {
                $join->on('products.id', '=', 'product_images.product_id')
                    ->where('product_images.is_main', '=', 1);
            })
            ->select(
                'products.id',
                'products.nama as nama_produk',
                DB::raw('SUM(order_items.kuantitas) as total_terjual'),
                'product_images.image_product'
            )
            ->groupBy('products.id', 'products.nama', 'product_images.image_product')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                if ($item->image_product) {
                    $base64 = base64_encode($item->image_product);
                    $item->image_base64 = "data:image/jpeg;base64,{$base64}";
                } else {
                    $item->image_base64 = asset('img/default.jpg');
                }
                return $item;
            });

        $completedOrders = DB::table('orders')
            ->selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->where('status', 'completed')
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->get();

        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $orderCountPerMonth = array_fill(0, 12, 0);

        foreach ($completedOrders as $data) {
            $bulanIndex = $data->bulan - 1;
            if ($bulanIndex >= 0 && $bulanIndex < 12) {
                $orderCountPerMonth[$bulanIndex] = (int)$data->total;
            }
        }

        // Pastikan ada minimal 1 nilai bukan nol
        if (max($orderCountPerMonth) === 0) {
            $orderCountPerMonth = array_map(function () {
                return rand(1, 5); // Nilai dummy untuk testing
            }, $orderCountPerMonth);
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
}
