<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\keluhan;

class laporanKeluhan extends Controller
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
}
