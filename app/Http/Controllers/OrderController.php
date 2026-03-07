<?php

namespace App\Http\Controllers;

use App\Exports\ProdukTerjualExport;
use App\Exports\RekapTahunanExport;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $query = Order::with('user');

        // Filter status (misalnya status_order)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan nama pembeli
        if ($request->has('search') && $request->search !== '') {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%');
            });
        }
        $orders = $query->paginate(5)->withQueryString();
        return view('dashboard.pesanan', compact('orders'));
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('dashboard.pesanan.edit-pesanan', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,shipped,completed,cancelled',
            'alamat_pemesanan' => 'required|string|max:255',
            'metode_pengiriman' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:255',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status,
            'alamat_pemesanan' => $request->alamat_pemesanan,
            'metode_pengiriman' => $request->metode_pengiriman,
            'notes' => $request->notes,
        ]);

        if ($request->status === 'paid') {
            DB::table('payments')
                ->where('order_id', $id)
                ->update(['status_pembayaran' => 'completed']);
        }


        return redirect()->route('pesanan.page')->with('success', 'Pesanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('pesanan.page')->with('success', 'Pesanan berhasil dihapus.');
    }

    public function DetailOrder()
    {
        $groupedOrders = Order::with(['user', 'orderItems.product'])
            ->whereHas('orderItems') // Pastikan ada order items
            ->paginate(5);

        return view('dashboard.detailOrder', compact('groupedOrders'));
        // return view('dashboard.detailOrder', compact('orderItems'));
    }

    public function exportPDF(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        // Validasi: jika tidak ada bulan/tahun, redirect
        if (!$bulan || !$tahun) {
            return redirect()->back()->with('error', 'Pilih bulan dan tahun terlebih dahulu.');
        }

        // Ambil awal dan akhir bulan
        $startDate = Carbon::create($tahun, $bulan)->startOfMonth();
        $endDate = Carbon::create($tahun, $bulan)->endOfMonth();

        // Ambil data OrderItem yang ada pada bulan dan tahun tersebut
        $produkTerjual = OrderItem::with(['product', 'order'])
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->selectRaw('product_id, SUM(kuantitas) as total_kuantitas, MAX(harga) as harga_satuan, SUM(kuantitas * harga) as total_harga')
            ->groupBy('product_id')
            ->get();

        $totalKeseluruhan = $produkTerjual->sum('total_harga');

        $pdf = Pdf::loadView('dashboard.pdf.rekap-produk', [
            'produkTerjual' => $produkTerjual,
            'totalKeseluruhan' => $totalKeseluruhan,
            'bulan' => $bulan,
            'tahun' => $tahun
        ])->setPaper('A4', 'landscape');

        return $pdf->download("rekap_produk_{$bulan}_{$tahun}.pdf");
    }

    public function exportRekapTahunan(Request $request)
    {
        $tahun = $request->input('tahun', now()->year);

        $namaFile = 'rekap_produk_perbulan_' . $tahun . '.xlsx';

        return Excel::download(new RekapTahunanExport($tahun), $namaFile);
    }
}
