<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Tagihan::with('booking.user', 'booking.kamar');
            
            // Filter berdasarkan status
            if ($request->filled('status') && $request->status !== 'Semua') {
                $query->where('status_tagihan', $request->status);
            }
            
            // Search berdasarkan nama penyewa atau nomor kamar
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('booking', function($q) use ($search) {
                    $q->whereHas('user', function($u) use ($search) {
                        $u->where('nama', 'like', '%' . $search . '%');
                    })->orWhereHas('kamar', function($k) use ($search) {
                        $k->where('nomor_kamar', 'like', '%' . $search . '%');
                    });
                });
            }
            
            $tagihans = $query->orderBy('id_tagihan', 'desc')->paginate(10);
            
            return view('dashboard.tagihan.index', compact('tagihans'));
            
        } catch (\Exception $e) {
            Log::error('Error loading tagihan index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat data tagihan: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'id_booking' => 'required|exists:booking,id_booking',
                'periode_bulan' => 'required|string|max:7',
                'nominal_dasar' => 'required|numeric|min:0',
                'nominal_denda' => 'nullable|numeric|min:0',
                'tgl_jatuh_tempo' => 'required|date',
                'status_tagihan' => 'required|string|in:belum_bayar,lunas,terlambat',
            ]);
            
            // Hitung total tagihan
            $nominal_denda = $validated['nominal_denda'] ?? 0;
            $validated['total_tagihan'] = $validated['nominal_dasar'] + $nominal_denda;
            
            // Simpan ke tabel tagihan
            $tagihan = Tagihan::create($validated);
            
            Log::info('Tagihan created successfully', ['id' => $tagihan->id_tagihan]);
            
            return redirect()->route('tagihan.index')
                ->with('success', 'Tagihan berhasil ditambahkan!');
                
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Error storing tagihan: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal menambahkan tagihan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id_tagihan)
    {
        try {
            $tagihan = Tagihan::with('booking.user', 'booking.kamar', 'pembayaran')->findOrFail($id_tagihan);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $tagihan->id_tagihan,
                    'penyewa' => $tagihan->booking->user->nama ?? '-',
                    'kamar' => $tagihan->booking->kamar->nomor_kamar ?? '-',
                    'periode' => $tagihan->periode_bulan,
                    'nominal_dasar' => $tagihan->nominal_dasar,
                    'nominal_dasar_formatted' => 'Rp ' . number_format($tagihan->nominal_dasar, 0, ',', '.'),
                    'nominal_denda' => $tagihan->nominal_denda ?? 0,
                    'nominal_denda_formatted' => 'Rp ' . number_format($tagihan->nominal_denda ?? 0, 0, ',', '.'),
                    'total_tagihan' => $tagihan->total_tagihan,
                    'total_tagihan_formatted' => 'Rp ' . number_format($tagihan->total_tagihan, 0, ',', '.'),
                    'jatuh_tempo' => $tagihan->tgl_jatuh_tempo,
                    'jatuh_tempo_formatted' => \Carbon\Carbon::parse($tagihan->tgl_jatuh_tempo)->locale('id')->translatedFormat('d M, Y'),
                    'status' => $tagihan->status_tagihan,
                    'status_badge' => $this->getStatusBadge($tagihan->status_tagihan),
                    'pembayaran' => $tagihan->pembayaran ? $tagihan->pembayaran->map(function($item) {
                        return [
                            'nominal' => 'Rp ' . number_format($item->nominal_pembayaran, 0, ',', '.'),
                            'tanggal' => \Carbon\Carbon::parse($item->tgl_pembayaran)->locale('id')->translatedFormat('d M, Y'),
                        ];
                    }) : [],
                ],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error showing tagihan: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Tagihan tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_tagihan)
    {
        try {
            $tagihan = Tagihan::findOrFail($id_tagihan);
            
            // Validasi input
            $validated = $request->validate([
                'periode_bulan' => 'required|string|max:7',
                'nominal_dasar' => 'required|numeric|min:0',
                'nominal_denda' => 'nullable|numeric|min:0',
                'tgl_jatuh_tempo' => 'required|date',
                'status_tagihan' => 'required|string|in:belum_bayar,lunas,terlambat',
            ]);
            
            // Hitung total tagihan
            $nominal_denda = $validated['nominal_denda'] ?? 0;
            $validated['total_tagihan'] = $validated['nominal_dasar'] + $nominal_denda;
            
            // Update tagihan
            $tagihan->update($validated);
            
            Log::info('Tagihan updated successfully', ['id' => $tagihan->id_tagihan]);
            
            return redirect()->route('tagihan.index')
                ->with('success', 'Tagihan berhasil diperbarui!');
                
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Error updating tagihan: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal memperbarui tagihan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_tagihan)
    {
        try {
            $tagihan = Tagihan::findOrFail($id_tagihan);
            
            // Cek apakah tagihan sudah dibayar
            if ($tagihan->status_tagihan === 'lunas') {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus tagihan yang sudah dibayar!');
            }
            
            // Hapus tagihan
            $tagihan->delete();
            
            Log::info('Tagihan deleted successfully', ['id' => $id_tagihan]);
            
            return redirect()->route('tagihan.index')
                ->with('success', 'Tagihan berhasil dihapus!');
                
        } catch (\Exception $e) {
            Log::error('Error deleting tagihan: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal menghapus tagihan: ' . $e->getMessage());
        }
    }

    /**
     * Get status badge
     */
    private function getStatusBadge($status)
    {
        $badges = [
            'belum_bayar' => ['bg' => '#fef2f2', 'color' => '#ef4444', 'text' => 'Belum Bayar'],
            'sebagian' => ['bg' => '#fef3c7', 'color' => '#d97706', 'text' => 'Sebagian'],
            'lunas' => ['bg' => '#ecfdf5', 'color' => '#00a669', 'text' => 'Lunas'],
            'terlambat' => ['bg' => '#fee2e2', 'color' => '#991b1b', 'text' => 'Tertunda'],
        ];
        
        return $badges[$status] ?? ['bg' => '#f3f4f6', 'color' => '#6b7280', 'text' => ucfirst($status)];
    }
}
