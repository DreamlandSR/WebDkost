<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\GaleriKamar;
use App\Models\FasilitasKamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
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
            $query = Kamar::with('galeri');

            if ($request->filled('status') && $request->status !== 'Semua') {
                $query->where('status_kamar', $request->status);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nomor_kamar', 'like', '%' . $search . '%')
                      ->orWhere('tipe_kamar', 'like', '%' . $search . '%');
                });
            }

            $kamars = $query->orderBy('id_kamar', 'desc')->paginate(5);

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
    // Validasi dilakukan di luar try agar otomatis redirect jika gagal
    $validated = $request->validate([
        'nomor_kamar'  => 'required|string|max:50|unique:kamar,nomor_kamar',
        'tipe_kamar'   => 'required|string|in:biasa,sedang,mewah',
        'deskripsi'    => 'nullable|string|max:500',
        'harga'        => 'required|numeric|min:0',
        'status_kamar' => 'required|string|in:tersedia,terisi,maintenance',
        'images'       => 'nullable|array',
        'images.*'     => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        'fasilitas'    => 'nullable|array',
        'fasilitas.*'  => 'string|max:255',
    ], [
        // Pesan kustom agar lebih user-friendly
        'tipe_kamar.required' => 'Pilih salah satu tipe kamar.',
        'status_kamar.required' => 'Pilih status ketersediaan kamar.',
        'nomor_kamar.unique' => 'Nomor kamar sudah terdaftar.',
    ]);

    try {
        DB::beginTransaction();

        $kamar = Kamar::create([
            'nomor_kamar'   => $validated['nomor_kamar'],
            'tipe_kamar'    => $validated['tipe_kamar'],
            'deskripsi'     => $validated['deskripsi'] ?? null,
            'harga_per_bulan' => $validated['harga'],
            'status_kamar'  => $validated['status_kamar'],
        ]);

        // Handle upload multiple gambar
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('kamar', $imageName, 'public');

                GaleriKamar::create([
                    'id_kamar' => $kamar->id_kamar,
                    'url_foto' => $imagePath,
                    'is_main'  => ($index === 0) ? 1 : 0, // Lebih simpel pakai index
                ]);
            }
        }

        // Simpan fasilitas
        if ($request->has('fasilitas')) {
            foreach ($request->fasilitas as $f) {
                FasilitasKamar::create([
                    'id_kamar'        => $kamar->id_kamar,
                    'nama_fasilitas'  => $f,
                ]);
            }
        }

        DB::commit();
        return redirect()->route('kamar.index')->with('success', 'Kamar berhasil ditambahkan!');

    } catch (\Exception $e) {
        DB::rollBack(); // Batalkan semua jika ada error
        Log::error('Error storing kamar: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Terjadi kesalahan sistem.')->withInput();
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
                            'id' => $item->id_galeri,
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
                    'galeri' => $kamar->galeri->map(function($item) {
                        return [
                            'id' => $item->id_galeri,
                            'url' => asset('storage/' . $item->url_foto),
                            'is_main' => $item->is_main
                        ];
                    }),
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

            // Validasi input dengan multiple images
            $validated = $request->validate([
                'nomor_kamar' => 'required|string|max:50|unique:kamar,nomor_kamar,' . $id_kamar . ',id_kamar',
                'tipe_kamar' => 'required|string|in:biasa,sedang,mewah',
                'deskripsi' => 'nullable|string|max:500',
                'harga' => 'required|numeric|min:0',
                'status_kamar' => 'required|string|in:tersedia,terisi,maintenance',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'delete_images' => 'nullable|array', // ID gambar yang akan dihapus
                'delete_images.*' => 'integer|exists:galeri_kamar,id_galeri',
                'set_main_image' => 'nullable|integer|exists:galeri_kamar,id_galeri', // Set gambar utama
            ]);

            // Update data kamar
            $kamar->update([
                'nomor_kamar' => $validated['nomor_kamar'],
                'tipe_kamar' => $validated['tipe_kamar'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'harga_per_bulan' => $validated['harga'],
                'status_kamar' => $validated['status_kamar'],
            ]);

            // Hapus gambar yang dipilih
            if ($request->has('delete_images')) {
                foreach ($request->delete_images as $imageId) {
                    $image = GaleriKamar::where('id_galeri', $imageId)
                        ->where('id_kamar', $id_kamar)
                        ->first();
                    if ($image) {
                        if (Storage::disk('public')->exists($image->url_foto)) {
                            Storage::disk('public')->delete($image->url_foto);
                        }
                        $image->delete();
                    }
                }
            }

            // Upload multiple gambar baru
            if ($request->hasFile('images')) {
                $currentImagesCount = GaleriKamar::where('id_kamar', $id_kamar)->count();
                $isMain = ($currentImagesCount == 0); // Jika belum ada gambar, yang baru jadi utama

                foreach ($request->file('images') as $image) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->storeAs('kamar', $imageName, 'public');

                    GaleriKamar::create([
                        'id_kamar' => $kamar->id_kamar,
                        'url_foto' => $imagePath,
                        'is_main' => $isMain ? 1 : 0,
                    ]);
                    $isMain = false;
                }
            }

            // Set gambar utama
            if ($request->has('set_main_image')) {
                // Reset semua gambar ke is_main = 0
                GaleriKamar::where('id_kamar', $id_kamar)->update(['is_main' => 0]);
                // Set gambar yang dipilih jadi utama
                GaleriKamar::where('id_galeri', $request->set_main_image)
                    ->where('id_kamar', $id_kamar)
                    ->update(['is_main' => 1]);
            }

            FasilitasKamar::where('id_kamar', $id_kamar)->delete();

            if ($request->has('fasilitas')) {
                foreach ($request->fasilitas as $f) {
                    FasilitasKamar::create([
                        'id_kamar'       => $id_kamar,
                        'nama_fasilitas' => $f,
                    ]);
                }
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

            // Hapus data kamar
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
     * Delete single image from gallery (AJAX)
     */
    public function deleteImage($id_galeri)
    {
        try {
            $image = GaleriKamar::findOrFail($id_galeri);
            $kamarId = $image->id_kamar;
            $wasMain = $image->is_main;

            // Hapus file fisik
            if (Storage::disk('public')->exists($image->url_foto)) {
                Storage::disk('public')->delete($image->url_foto);
            }

            // Hapus record
            $image->delete();

            // Jika yang dihapus adalah gambar utama, set gambar lain jadi utama
            if ($wasMain) {
                $newMainImage = GaleriKamar::where('id_kamar', $kamarId)->first();
                if ($newMainImage) {
                    $newMainImage->is_main = 1;
                    $newMainImage->save();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Gambar berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting image: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus gambar'
            ], 500);
        }
    }

    /**
     * Set main image (AJAX)
     */
    public function setMainImage($id_galeri)
    {
        try {
            $image = GaleriKamar::findOrFail($id_galeri);

            // Reset semua gambar kamar ini jadi bukan utama
            GaleriKamar::where('id_kamar', $image->id_kamar)->update(['is_main' => 0]);

            // Set gambar ini jadi utama
            $image->is_main = 1;
            $image->save();

            return response()->json([
                'success' => true,
                'message' => 'Gambar utama berhasil diubah'
            ]);

        } catch (\Exception $e) {
            Log::error('Error setting main image: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah gambar utama'
            ], 500);
        }
    }

    /**
     * Get status badge HTML (helper method)
     */
    private function getStatusBadge($status)
    {
        $badges = [
            'tersedia' => '<span class="badge rounded-pill" style="background-color: #ecfdf5; color: #00a669; font-weight: 600; padding: 6px 12px;">Tersedia</span>',
            'terisi' => '<span class="badge rounded-pill" style="background-color: #fef2f2; color: #ef4444; font-weight: 600; padding: 6px 12px;">Terisi</span>',
            'maintenance' => '<span class="badge rounded-pill" style="background-color: #fef3c7; color: #d97706; font-weight: 600; padding: 6px 12px;">Maintenance</span>',
        ];

        return $badges[$status] ?? '<span class="badge rounded-pill" style="background-color: #f3f4f6; color: #6b7280;">' . $status . '</span>';
    }

    /**
     * Get kamar details for API
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
                        return [
                            'id' => $item->id_galeri,
                            'url' => asset('storage/' . $item->url_foto),
                            'is_main' => $item->is_main
                        ];
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
     * Export kamar data
     */
    public function export()
    {
        try {
            $kamars = Kamar::with('fasilitas')->get();

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
