<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\GaleriKamar;
use App\Models\FasilitasKamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class KamarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Kamar::with('galeri'); // Eager loading untuk menghindari N+1 query
            
            // Filter berdasarkan status
            if ($request->filled('status') && $request->status !== 'Semua') {
                $query->where('status_kamar', $request->status);
            }
            
            // Search berdasarkan nomor kamar atau tipe kamar
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nomor_kamar', 'like', '%' . $search . '%')
                      ->orWhere('tipe_kamar', 'like', '%' . $search . '%');
                });
            }
            
            $kamars = $query->orderBy('id_kamar', 'desc')->paginate(10);
            
            return view('dashboard.kamar.index', compact('kamars'));
            
        } catch (\Exception $e) {
            Log::error('Error loading kamar index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat data kamar: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.kamar.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'nomor_kamar' => 'required|string|max:50|unique:kamar,nomor_kamar',
                'tipe_kamar' => 'required|string|in:biasa,sedang,mewah',
                'deskripsi' => 'nullable|string|max:500',
                'harga' => 'required|numeric|min:0',
                'status_kamar' => 'required|string|in:Tersedia,Terisi,Maintenance',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            // Simpan ke tabel kamar
            $kamar = Kamar::create([
                'nomor_kamar' => $validated['nomor_kamar'],
                'tipe_kamar' => $validated['tipe_kamar'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'harga_per_bulan' => $validated['harga'],
                'status_kamar' => $validated['status_kamar'],
            ]);
            
            // Handle upload gambar jika ada
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('kamar', $imageName, 'public');
                
                GaleriKamar::create([
                    'id_kamar' => $kamar->id_kamar,
                    'url_foto' => $imagePath,
                    'is_main' => 1,
                ]);
            }
            
            Log::info('Kamar created successfully', ['id' => $kamar->id_kamar]);
            
            return redirect()->route('kamar.index')
                ->with('success', 'Kamar berhasil ditambahkan!');
                
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Error storing kamar: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal menambahkan kamar: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id_kamar)
    {
        try {
            $kamar = Kamar::with('galeri', 'fasilitas')->findOrFail($id_kamar);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $kamar->id_kamar,
                    'nomor_kamar' => $kamar->nomor_kamar,
                    'tipe_kamar' => $kamar->tipe_kamar,
                    'deskripsi' => $kamar->deskripsi,
                    'harga' => $kamar->harga_per_bulan,
                    'harga_formatted' => 'Rp ' . number_format($kamar->harga_per_bulan, 0, ',', '.'),
                    'status' => $kamar->status_kamar,
                    'status_badge' => $this->getStatusBadge($kamar->status_kamar),
                    'fasilitas' => $kamar->fasilitas ? $kamar->fasilitas->pluck('nama_fasilitas')->toArray() : [],
                    'image_url' => $kamar->galeri ? asset('storage/' . $kamar->galeri->firstWhere('is_main', 1)?->url_foto) : null,
                    'galeri' => $kamar->galeri ? $kamar->galeri->map(function($item) {
                        return [
                            'foto' => asset('storage/' . $item->url_foto),
                            'is_main' => $item->is_main
                        ];
                    }) : [],
                ],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error showing kamar: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Kamar tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_kamar)
    {
        try {
            $kamar = Kamar::with('galeri')->findOrFail($id_kamar);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $kamar->id_kamar,
                    'nomor_kamar' => $kamar->nomor_kamar,
                    'tipe_kamar' => $kamar->tipe_kamar,
                    'deskripsi' => $kamar->deskripsi,
                    'harga' => $kamar->harga_per_bulan,
                    'status' => $kamar->status_kamar,
                    'image_url' => $kamar->galeri ? asset('storage/' . $kamar->galeri->firstWhere('is_main', 1)?->url_foto) : null,
                ],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error editing kamar: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Kamar tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_kamar)
    {
        try {
            $kamar = Kamar::findOrFail($id_kamar);
            
            // Validasi input
            $validated = $request->validate([
                'nomor_kamar' => 'required|string|max:50|unique:kamar,nomor_kamar,' . $id_kamar . ',id_kamar',
                'tipe_kamar' => 'required|string|in:biasa,sedang,mewah',
                'deskripsi' => 'nullable|string|max:500',
                'harga' => 'required|numeric|min:0',
                'status_kamar' => 'required|string|in:Tersedia,Terisi,Maintenance',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            // Update data kamar
            $kamar->update([
                'nomor_kamar' => $validated['nomor_kamar'],
                'tipe_kamar' => $validated['tipe_kamar'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'harga_per_bulan' => $validated['harga'],
                'status_kamar' => $validated['status_kamar'],
            ]);
            
            // Handle upload gambar baru
            if ($request->hasFile('image')) {
                // Hapus gambar lama yang merupakan main image
                $oldImage = $kamar->galeri()->where('is_main', 1)->first();
                if ($oldImage) {
                    if (Storage::disk('public')->exists($oldImage->url_foto)) {
                        Storage::disk('public')->delete($oldImage->url_foto);
                    }
                    $oldImage->delete();
                }
                
                // Upload gambar baru
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('kamar', $imageName, 'public');
                
                GaleriKamar::create([
                    'id_kamar' => $kamar->id_kamar,
                    'url_foto' => $imagePath,
                    'is_main' => 1,
                ]);
            }
            
            Log::info('Kamar updated successfully', ['id' => $kamar->id_kamar]);
            
            return redirect()->route('kamar.index')
                ->with('success', 'Kamar berhasil diperbarui!');
                
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Error updating kamar: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal memperbarui kamar: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_kamar)
    {
        try {
            $kamar = Kamar::findOrFail($id_kamar);
            
            // Hapus semua gambar yang terkait dengan kamar ini
            foreach ($kamar->galeri as $image) {
                if (Storage::disk('public')->exists($image->url_foto)) {
                    Storage::disk('public')->delete($image->url_foto);
                }
                $image->delete();
            }
            
            // Hapus data kamar (fasilitas akan terhapus otomatis karena foreign key cascade)
            $kamar->delete();
            
            Log::info('Kamar deleted successfully', ['id' => $id_kamar]);
            
            return redirect()->route('kamar.index')
                ->with('success', 'Kamar berhasil dihapus!');
                
        } catch (\Exception $e) {
            Log::error('Error deleting kamar: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal menghapus kamar: ' . $e->getMessage());
        }
    }
    
    /**
     * Get kamar details for API (optional)
     */
    public function getKamarDetail($id_kamar)
    {
        try {
            $kamar = Kamar::with('galeri', 'fasilitas')->findOrFail($id_kamar);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $kamar->id_kamar,
                    'nomor_kamar' => $kamar->nomor_kamar,
                    'tipe_kamar' => $kamar->tipe_kamar,
                    'deskripsi' => $kamar->deskripsi,
                    'harga' => $kamar->harga_per_bulan,
                    'harga_formatted' => 'Rp ' . number_format($kamar->harga_per_bulan, 0, ',', '.'),
                    'status' => $kamar->status_kamar,
                    'fasilitas_list' => $kamar->fasilitas->map(function($item) {
                        return $item->nama_fasilitas;
                    }),
                    'image_url' => $kamar->galeri && $kamar->galeri->firstWhere('is_main', 1) 
                        ? asset('storage/' . $kamar->galeri->firstWhere('is_main', 1)->url_foto) 
                        : null,
                    'all_images' => $kamar->galeri->map(function($item) {
                        return asset('storage/' . $item->url_foto);
                    }),
                ],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting kamar detail: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Kamar tidak ditemukan'
            ], 404);
        }
    }
    
    /**
     * Get status badge HTML (helper method)
     */
    private function getStatusBadge($status)
    {
        $badges = [
            'Tersedia' => '<span class="badge rounded-pill" style="background-color: #ecfdf5; color: #00a669; font-weight: 600; padding: 6px 12px;">Tersedia</span>',
            'Terisi' => '<span class="badge rounded-pill" style="background-color: #fef2f2; color: #ef4444; font-weight: 600; padding: 6px 12px;">Terisi</span>',
            'Maintenance' => '<span class="badge rounded-pill" style="background-color: #fef3c7; color: #d97706; font-weight: 600; padding: 6px 12px;">Maintenance</span>',
        ];
        
        return $badges[$status] ?? '<span class="badge rounded-pill" style="background-color: #f3f4f6; color: #6b7280;">' . $status . '</span>';
    }
    
    /**
     * Export kamar data (optional)
     */
    public function export()
    {
        try {
            $kamars = Kamar::with('fasilitas')->get();
            
            // Logic untuk export data (Excel, CSV, PDF)
            // Bisa ditambahkan sesuai kebutuhan
            
            return response()->json([
                'success' => true,
                'data' => $kamars
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error exporting kamar: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }
}