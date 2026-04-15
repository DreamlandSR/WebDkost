<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Kamar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB; // Tambahkan ini untuk debugging

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index(Request $request)
{
    try {
        $query = Booking::with(['user', 'kamar']);
        
        // Filter berdasarkan status booking
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status_booking', $request->status);
        }
        
        // Search berdasarkan nomor booking, user, atau kamar
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($u) use ($search) {
                    $u->where('nama', 'like', '%' . $search . '%');
                })->orWhereHas('kamar', function($k) use ($search) {
                    $k->where('nomor_kamar', 'like', '%' . $search . '%');
                });
            });
        }
        
        $bookings = $query->orderBy('id_booking', 'desc')->paginate(10);
        $users = User::where('role', 'Penyewa')->get();
        
        // Untuk dropdown filter, tampilkan kamar yang tersedia
        $kamars = Kamar::where('status_kamar', 'tersedia')->get();
        
        // TAMBAHKAN INI: Kirim semua kamar untuk data harga
        $allKamars = Kamar::all();
        
        // TAMBAHKAN INI: Kirim data kamar dalam format JSON untuk JavaScript
        $kamarHargaMap = Kamar::pluck('harga_per_bulan', 'id_kamar')->toJson();
        
        return view('dashboard.booking.index', compact('bookings', 'users', 'kamars', 'allKamars', 'kamarHargaMap'));
        
    } catch (\Exception $e) {
        Log::error('Error loading booking index: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal memuat data booking: ' . $e->getMessage());
    }
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            // Hanya tampilkan kamar yang tersedia
            $kamars = Kamar::where('status_kamar', 'tersedia')->get();
            $users = User::where('role', 'Penyewa')->get();
            
            return view('dashboard.booking.create', compact('kamars', 'users'));
        } catch (\Exception $e) {
            Log::error('Error loading create booking form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat form: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_user' => 'required|exists:users,id_user',
                'id_kamar' => 'required|exists:kamar,id_kamar',
                'tgl_mulai_sewa' => 'required|date|after_or_equal:today',
                'durasi_sewa_bulan' => 'required|integer|min:1|max:24',
                'total_biaya_bulanan' => 'required|numeric|min:0',
                'status_booking' => 'required|string|in:menunggu_pembayaran,aktif,selesai,batal,expired',
            ]);
            
            // 🔥 KONVERSI DURASI KE INTEGER
            $durasi = (int) $validated['durasi_sewa_bulan'];
            
            // Cek ketersediaan kamar (status kamar harus tersedia)
            $kamar = Kamar::find($validated['id_kamar']);
            if (!$kamar || $kamar->status_kamar !== 'tersedia') {
                return redirect()->back()
                    ->with('error', 'Kamar yang dipilih tidak tersedia!')
                    ->withInput();
            }
            
            // Cek apakah kamar sudah dibooking di periode tersebut
            $tgl_mulai = \Carbon\Carbon::parse($validated['tgl_mulai_sewa']);
            $tgl_akhir = $tgl_mulai->copy()->addMonths($durasi)->subDay();
            
            $existingBooking = Booking::where('id_kamar', $validated['id_kamar'])
                ->whereIn('status_booking', ['menunggu_pembayaran', 'aktif'])
                ->where(function($q) use ($tgl_mulai, $tgl_akhir) {
                    $q->whereBetween('tgl_mulai_sewa', [$tgl_mulai, $tgl_akhir])
                      ->orWhereBetween('tgl_akhir_sewa', [$tgl_mulai, $tgl_akhir])
                      ->orWhere(function($sub) use ($tgl_mulai, $tgl_akhir) {
                          $sub->where('tgl_mulai_sewa', '<=', $tgl_mulai)
                               ->where('tgl_akhir_sewa', '>=', $tgl_akhir);
                      });
                })
                ->exists();
            
            if ($existingBooking) {
                return redirect()->back()
                    ->with('error', 'Kamar sudah dibooking untuk periode tersebut!')
                    ->withInput();
            }
            
            // Hitung tanggal akhir
            $tgl_akhir = $tgl_mulai->copy()->addMonths($durasi)->subDay();
            
            // Simpan booking
            $booking = Booking::create([
                'id_user' => $validated['id_user'],
                'id_kamar' => $validated['id_kamar'],
                'tgl_booking' => now()->format('Y-m-d'),
                'durasi_sewa_bulan' => $durasi,
                'tgl_mulai_sewa' => $validated['tgl_mulai_sewa'],
                'tgl_akhir_sewa' => $tgl_akhir->format('Y-m-d'),
                'total_biaya_bulanan' => $validated['total_biaya_bulanan'],
                'status_booking' => $validated['status_booking'],
            ]);
            
            // ✅ PERBAIKAN 1: Update status kamar dengan kutip yang benar
            if ($validated['status_booking'] === 'aktif') {
                // Gunakan query builder dengan string value yang benar
                DB::table('kamar')
                    ->where('id_kamar', $kamar->id_kamar)
                    ->update(['status_kamar' => 'tidak_tersedia']);
            }
            
            Log::info('Booking created successfully', ['id' => $booking->id_booking]);
            
            return redirect()->route('booking.index')
                ->with('success', 'Booking berhasil dibuat!');
                
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Error storing booking: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal membuat booking: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id_booking)
    {
        try {
            $booking = Booking::with(['user', 'kamar', 'tagihan'])->findOrFail($id_booking);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $booking->id_booking,
                    'user_nama' => $booking->user->nama ?? '-',
                    'user_email' => $booking->user->email ?? '-',
                    'user_telepon' => $booking->user->no_telepon ?? '-',
                    'kamar_nomor' => $booking->kamar->nomor_kamar ?? '-',
                    'kamar_tipe' => $booking->kamar->tipe_kamar ?? '-',
                    'tgl_booking' => $booking->tgl_booking ? \Carbon\Carbon::parse($booking->tgl_booking)->format('d M Y') : '-',
                    'tgl_mulai_sewa' => $booking->tgl_mulai_sewa ? \Carbon\Carbon::parse($booking->tgl_mulai_sewa)->format('d M Y') : '-',
                    'tgl_akhir_sewa' => $booking->tgl_akhir_sewa ? \Carbon\Carbon::parse($booking->tgl_akhir_sewa)->format('d M Y') : '-',
                    'durasi_sewa_bulan' => $booking->durasi_sewa_bulan ?? 0,
                    'total_biaya_bulanan' => $booking->total_biaya_bulanan ?? 0,
                    'total_biaya_formatted' => 'Rp ' . number_format($booking->total_biaya_bulanan ?? 0, 0, ',', '.'),
                    'status' => $booking->status_booking,
                    'status_badge' => $this->getStatusBadge($booking->status_booking),
                    'tagihan' => $booking->tagihan ? $booking->tagihan->count() : 0,
                ],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error showing booking: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
 /**
 * Show the form for editing the specified resource.
 */
        public function edit($id_booking)
            {
    try {
        $booking = Booking::with(['user', 'kamar'])->findOrFail($id_booking);
        
        // Tampilkan kamar yang TERSEDIA + kamar yang sedang dipakai oleh booking ini
        $allKamars = Kamar::where('status_kamar', 'tersedia')
            ->orWhere('id_kamar', $booking->id_kamar)
            ->get();
        
        // Untuk dropdown filter (kamar tersedia saja)
        $kamars = Kamar::where('status_kamar', 'tersedia')->get();
        
        $users = User::where('role', 'Penyewa')->get();
        
        // Kirim data ke view
        return view('dashboard.booking.index', compact('booking', 'kamars', 'users', 'allKamars'));
        
    } catch (\Exception $e) {
        Log::error('Error loading edit booking form: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal memuat form: ' . $e->getMessage());
    }
}
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_booking)
    {
        try {
            $booking = Booking::findOrFail($id_booking);
            
            $validated = $request->validate([
                'id_user' => 'required|exists:users,id_user',
                'id_kamar' => 'required|exists:kamar,id_kamar',
                'tgl_mulai_sewa' => 'required|date',
                'durasi_sewa_bulan' => 'required|integer|min:1|max:24',
                'total_biaya_bulanan' => 'required|numeric|min:0',
                'status_booking' => 'required|string|in:menunggu_pembayaran,aktif,selesai,batal,expired',
            ]);
            
            // 🔥 KONVERSI DURASI KE INTEGER
            $durasi = (int) $validated['durasi_sewa_bulan'];
            
            // Simpan data lama untuk keperluan update status kamar
            $oldStatus = $booking->status_booking;
            $oldKamarId = $booking->id_kamar;
            
            // Cek ketersediaan kamar jika berbeda dengan kamar lama
            if ($request->id_kamar != $booking->id_kamar) {
                $kamarBaru = Kamar::find($request->id_kamar);
                if (!$kamarBaru || $kamarBaru->status_kamar !== 'tersedia') {
                    return redirect()->back()
                        ->with('error', 'Kamar yang dipilih tidak tersedia!')
                        ->withInput();
                }
                
                // Cek apakah kamar baru sudah dibooking di periode tersebut
                $tgl_mulai = \Carbon\Carbon::parse($validated['tgl_mulai_sewa']);
                $tgl_akhir = $tgl_mulai->copy()->addMonths($durasi)->subDay();
                
                $existingBooking = Booking::where('id_kamar', $request->id_kamar)
                    ->where('id_booking', '!=', $id_booking)
                    ->whereIn('status_booking', ['menunggu_pembayaran', 'aktif'])
                    ->where(function($q) use ($tgl_mulai, $tgl_akhir) {
                        $q->whereBetween('tgl_mulai_sewa', [$tgl_mulai, $tgl_akhir])
                          ->orWhereBetween('tgl_akhir_sewa', [$tgl_mulai, $tgl_akhir])
                          ->orWhere(function($sub) use ($tgl_mulai, $tgl_akhir) {
                              $sub->where('tgl_mulai_sewa', '<=', $tgl_mulai)
                                   ->where('tgl_akhir_sewa', '>=', $tgl_akhir);
                          });
                    })
                    ->exists();
                
                if ($existingBooking) {
                    return redirect()->back()
                        ->with('error', 'Kamar sudah dibooking untuk periode tersebut!')
                        ->withInput();
                }
            }
            
            // Hitung tgl_akhir_sewa
            $tgl_mulai = \Carbon\Carbon::parse($validated['tgl_mulai_sewa']);
            $tgl_akhir = $tgl_mulai->copy()->addMonths($durasi)->subDay();
            
            // Update booking
            $booking->update([
                'id_user' => $validated['id_user'],
                'id_kamar' => $validated['id_kamar'],
                'tgl_mulai_sewa' => $validated['tgl_mulai_sewa'],
                'tgl_akhir_sewa' => $tgl_akhir->format('Y-m-d'),
                'durasi_sewa_bulan' => $durasi,
                'total_biaya_bulanan' => $validated['total_biaya_bulanan'],
                'status_booking' => $validated['status_booking'],
            ]);
            
            // ✅ PERBAIKAN 2: Update status kamar berdasarkan perubahan
            if ($oldKamarId != $validated['id_kamar']) {
                // === KAMAR LAMA ===
                // Cek apakah masih ada booking aktif di kamar lama
                $masihAdaBookingAktif = Booking::where('id_kamar', $oldKamarId)
                    ->whereIn('status_booking', ['menunggu_pembayaran', 'aktif'])
                    ->exists();
                    
                if (!$masihAdaBookingAktif) {
                    DB::table('kamar')
                        ->where('id_kamar', $oldKamarId)
                        ->update(['status_kamar' => 'tersedia']);
                }
                
                // === KAMAR BARU ===
                // Set tidak tersedia jika booking aktif
                if ($validated['status_booking'] === 'aktif') {
                    DB::table('kamar')
                        ->where('id_kamar', $validated['id_kamar'])
                        ->update(['status_kamar' => 'tidak_tersedia']);
                }
            } else {
                // === KAMAR SAMA ===
                // Update status berdasarkan booking
                if ($validated['status_booking'] === 'aktif' && $oldStatus !== 'aktif') {
                    // Booking menjadi aktif -> kamar tidak tersedia
                    DB::table('kamar')
                        ->where('id_kamar', $validated['id_kamar'])
                        ->update(['status_kamar' => 'tidak_tersedia']);
                } elseif ($validated['status_booking'] !== 'aktif' && $oldStatus === 'aktif') {
                    // Booking tidak aktif lagi (selesai/batal/expired) -> cek apakah masih ada booking aktif lain
                    $masihAdaBookingAktif = Booking::where('id_kamar', $validated['id_kamar'])
                        ->where('id_booking', '!=', $id_booking)
                        ->whereIn('status_booking', ['menunggu_pembayaran', 'aktif'])
                        ->exists();
                        
                    if (!$masihAdaBookingAktif) {
                        DB::table('kamar')
                            ->where('id_kamar', $validated['id_kamar'])
                            ->update(['status_kamar' => 'tersedia']);
                    }
                }
            }
            
            Log::info('Booking updated successfully', ['id' => $booking->id_booking]);
            
            return redirect()->route('booking.index')
                ->with('success', 'Booking berhasil diperbarui!');
                
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Error updating booking: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal memperbarui booking: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_booking)
    {
        try {
            $booking = Booking::findOrFail($id_booking);
            
            // ✅ PERBAIKAN 3: Update status kamar jika booking ini membuat kamar tidak tersedia
            if (in_array($booking->status_booking, ['menunggu_pembayaran', 'aktif'])) {
                $masihAdaBookingLain = Booking::where('id_kamar', $booking->id_kamar)
                    ->where('id_booking', '!=', $id_booking)
                    ->whereIn('status_booking', ['menunggu_pembayaran', 'aktif'])
                    ->exists();
                    
                if (!$masihAdaBookingLain) {
                    DB::table('kamar')
                        ->where('id_kamar', $booking->id_kamar)
                        ->update(['status_kamar' => 'tersedia']);
                }
            }
            
            $booking->delete();
            
            Log::info('Booking deleted successfully', ['id' => $id_booking]);
            
            return redirect()->route('booking.index')
                ->with('success', 'Booking berhasil dihapus!');
                
        } catch (\Exception $e) {
            Log::error('Error deleting booking: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal menghapus booking: ' . $e->getMessage());
        }
    }

    /**
     * Get status badge styling
     */
    private function getStatusBadge($status)
    {
        $badges = [
            'menunggu_pembayaran' => ['bg' => '#fef2f2', 'text' => '#ef4444', 'label' => 'Menunggu Pembayaran'],
            'aktif' => ['bg' => '#ecfdf5', 'text' => '#00a669', 'label' => 'Aktif'],
            'selesai' => ['bg' => '#f0f9ff', 'text' => '#0284c7', 'label' => 'Selesai'],
            'batal' => ['bg' => '#fef3c7', 'text' => '#d97706', 'label' => 'Dibatalkan'],
            'expired' => ['bg' => '#f3f4f6', 'text' => '#6b7280', 'label' => 'Expired'],
        ];
        
        return $badges[$status] ?? ['bg' => '#f3f4f6', 'text' => '#6b7280', 'label' => 'Tidak Diketahui'];
    }
}