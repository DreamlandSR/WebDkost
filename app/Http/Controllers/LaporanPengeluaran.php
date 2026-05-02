<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pengeluaran;
use App\Exports\PengeluaranExport;
use Maatwebsite\Excel\Facades\Excel;

class laporanPengeluaran extends Controller
{
    public function exportExcel()
    {
        return Excel::download(new PengeluaranExport, 'Laporan_Pengeluaran.xlsx');
    }

    public function index(Request $request)
    {
        $query = pengeluaran::query();

        // Filter
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('kategori', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('keterangan', 'like', '%' . $search . '%')
                  ->orWhere('kategori', 'like', '%' . $search . '%');
            });
        }

        $pengeluarans = $query->orderBy('tgl_transaksi', 'desc')->paginate(5)->withQueryString();

        return view('dashboard.pengeluaran', compact('pengeluarans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori'      => 'required|string',
            'nominal'       => 'required|numeric',
            'tgl_transaksi' => 'required|date',
            'keterangan'    => 'nullable|string',
        ]);

        pengeluaran::create([
            'kategori'      => $request->kategori,
            'nominal'       => $request->nominal,
            'tgl_transaksi' => $request->tgl_transaksi,
            'keterangan'    => $request->keterangan,
        ]);

        return redirect()->route('pengeluaran.page')->with('success', 'Data pengeluaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id_pengeluaran)
    {
        $request->validate([
            'kategori'      => 'required|string',
            'nominal'       => 'required|numeric',
            'tgl_transaksi' => 'required|date',
            'keterangan'    => 'nullable|string',
        ]);

        $pengeluaran = pengeluaran::findOrFail($id_pengeluaran);
        $pengeluaran->update([
            'kategori'      => $request->kategori,
            'nominal'       => $request->nominal,
            'tgl_transaksi' => $request->tgl_transaksi,
            'keterangan'    => $request->keterangan,
        ]);

        return redirect()->route('pengeluaran.page')->with('success', 'Data pengeluaran berhasil diperbarui.');
    }

    public function destroy($id_pengeluaran)
    {
        $pengeluaran = pengeluaran::findOrFail($id_pengeluaran);
        $pengeluaran->delete();

        return redirect()->route('pengeluaran.page')->with('success', 'Data pengeluaran berhasil dihapus.');
    }
}
