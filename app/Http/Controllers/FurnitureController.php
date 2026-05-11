<?php

namespace App\Http\Controllers;

use App\Models\Furnitur;
use App\Models\ItemFurnitur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class FurnitureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Furnitur::with('items');
            
            // Search berdasarkan nama furnitur
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('nama_furnitur', 'like', '%' . $search . '%');
            }
            
            $furnitur = $query->orderBy('id_furnitur', 'desc')->paginate(5);
            
            return view('dashboard.furniture.index', compact('furnitur'));
            
        } catch (\Exception $e) {
            Log::error('Error loading furnitur index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat data furnitur: ' . $e->getMessage());
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
                'nama_furnitur' => 'required|string|max:100',
                'harga_sewa_tambahan' => 'required|numeric|min:0',
                'kode_item' => 'required|array|min:1',
                'kode_item.*' => 'required|string|max:100|unique:item_furnitur,kode_item',
            ]);
            
            // Cek apakah furnitur dengan nama yang sama sudah ada (case-insensitive)
            $furnitureItem = Furnitur::whereRaw('LOWER(nama_furnitur) = ?', [strtolower(trim($validated['nama_furnitur']))])->first();

            if ($furnitureItem) {
                // Jika sudah ada, tambahkan jumlahnya dan update harganya (jika berbeda)
                $furnitureItem->increment('jumlah', count($validated['kode_item']));
                $furnitureItem->update([
                    'harga_sewa_tambahan' => $validated['harga_sewa_tambahan']
                ]);
            } else {
                // Jika belum ada, buat baru
                $furnitureItem = Furnitur::create([
                    'nama_furnitur' => trim($validated['nama_furnitur']),
                    'jumlah' => count($validated['kode_item']),
                    'harga_sewa_tambahan' => $validated['harga_sewa_tambahan'],
                ]);
            }

            // Simpan item-item (kode fisik) ke tabel item_furnitur
            foreach ($validated['kode_item'] as $kode) {
                ItemFurnitur::create([
                    'id_furnitur' => $furnitureItem->id_furnitur,
                    'kode_item' => $kode,
                    'status_item' => 'Tersedia',
                ]);
            }
            
            Log::info('Furniture created successfully with items', ['id' => $furnitureItem->id_furnitur]);
            
            return redirect()->route('furnitur.index')
                ->with('success', 'Furnitur beserta kode barang berhasil ditambahkan!');
                
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Error storing furnitur: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal menambahkan furnitur: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id_furnitur)
    {
        try {
            $furniture = Furnitur::findOrFail($id_furnitur);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $furniture->id_furnitur,
                    'nama_furnitur' => $furniture->nama_furnitur,
                    'jumlah' => $furniture->jumlah,
                    'harga_sewa_tambahan' => $furniture->harga_sewa_tambahan,
                    'harga_formatted' => 'Rp ' . number_format($furniture->harga_sewa_tambahan, 0, ',', '.'),
                ],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error showing furnitur: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Furnitur tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_furnitur)
    {
        try {
            $furniture = Furnitur::findOrFail($id_furnitur);
            
            // Validasi input. Kode item diabaikan saat edit (untuk simplifikasi/permintaan agar tampilan tetap sama),
            // atau jika kita mau update kode, kita abaikan unique check untuk ID yg sama.
            // Sesuai konteks user, hanya edit nama dan harga. Jumlah dan item diatur terpisah jika perlu, 
            // namun agar aman, kita biarkan saja jumlah mengikuti jumlah item yg ada.
            $validated = $request->validate([
                'nama_furnitur' => 'required|string|max:100',
                'harga_sewa_tambahan' => 'required|numeric|min:0',
            ]);
            
            // Update data furnitur
            $furniture->update([
                'nama_furnitur' => $validated['nama_furnitur'],
                'harga_sewa_tambahan' => $validated['harga_sewa_tambahan'],
            ]);
            
            Log::info('Furniture updated successfully', ['id' => $furniture->id_furnitur]);
            
            return redirect()->route('furnitur.index')
                ->with('success', 'Furnitur berhasil diperbarui!');
                
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Error updating furnitur: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal memperbarui furnitur: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_furnitur)
    {
        try {
            $furniture = Furnitur::findOrFail($id_furnitur);
            
            // Hapus data furnitur
            $furniture->delete();
            
            Log::info('Furniture deleted successfully', ['id' => $id_furnitur]);
            
            return redirect()->route('furnitur.index')
                ->with('success', 'Furnitur berhasil dihapus!');
                
        } catch (\Exception $e) {
            Log::error('Error deleting furnitur: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal menghapus furnitur: ' . $e->getMessage());
        }
    }
}
