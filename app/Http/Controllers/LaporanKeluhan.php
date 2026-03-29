<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keluhan;

class LaporanKeluhan extends Controller
{
    public function index(Request $request)
    {
        $query = keluhan::with(['user', 'kamar']);

        // Filter status keluhan
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status_keluhan', $request->status);
        }

        // Filter pencarian nama pelapor
        if ($request->has('search') && $request->search !== '') {
            $query->whereHas('user', function ($q) use ($request) {
                // Menyesuaikan kolom 'nama' pada tabel users
                $q->where('nama', 'like', '%' . $request->search . '%');
            });
        }

        // Paginate hasil
        $keluhans = $query->orderBy('tgl_lapor', 'desc')->paginate(5)->withQueryString();

        return view('dashboard.keluhan', compact('keluhans'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_keluhan' => 'required|in:pending,diproses,selesai',
        ]);

        $keluhan = keluhan::findOrFail($id);
        $keluhan->status_keluhan = $request->status_keluhan;
        $keluhan->save();

        return redirect()->back()->with('success', 'Status keluhan berhasil diperbarui.');
    }
}
