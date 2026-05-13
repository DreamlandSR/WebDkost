<?php

namespace App\Http\Controllers;

use App\Models\Furnitur;
use App\Models\Booking;
use App\Models\User;
use App\Models\PenyewaFurnitur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PenyewaFurniturController extends Controller
{
    /**
     * Display a listing of all penyewa furnitur.
     */
    public function index(Request $request)
    {
        try {
            $query = PenyewaFurnitur::with(['item.furnitur', 'user', 'booking.kamar']);

            // Filter by status
            if ($request->filled('status') && $request->status !== 'Semua') {
                $query->where('status', $request->status);
            }

            // Filter by furnitur (melalui item)
            if ($request->filled('id_furnitur')) {
                $query->whereHas('item', function($q) use ($request) {
                    $q->where('id_furnitur', $request->id_furnitur);
                });
            }

            // Search by nama penyewa
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%');
                });
            }

            $penyewaFurnitur = $query->orderBy('id_penyewa_furnitur', 'desc')->paginate(5);

            $furniturList = Furnitur::orderBy('nama_furnitur')->get();
            $itemList     = \App\Models\ItemFurnitur::with('furnitur')
                                ->where('status_item', 'Tersedia')
                                ->orderBy('id_furnitur')
                                ->get();
            $userList     = User::where('role', 'Penyewa')->orderBy('nama')->get();

            // Booking aktif untuk dropdown form tambah
            $bookingAktif = Booking::with(['user', 'kamar'])
                ->where('status_booking', 'aktif')
                ->orderBy('id_booking', 'desc')
                ->get();

            return view('dashboard.penyewa-furnitur.index', compact(
                'penyewaFurnitur',
                'furniturList',
                'itemList',
                'userList',
                'bookingAktif'
            ));

        } catch (\Exception $e) {
            Log::error('Error loading penyewa furnitur index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created penyewa furnitur (manual by admin).
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_item'       => 'required|exists:item_furnitur,id_item',
                'id_user'       => 'required|exists:users,id_user',
                'id_booking'    => 'nullable|exists:booking,id_booking',
                'tgl_mulai'     => 'required|date',
                'tgl_selesai'   => 'nullable|date|after_or_equal:tgl_mulai',
                'status'        => 'required|in:aktif,selesai',
                'catatan'       => 'nullable|string|max:500',
            ]);

            // Cek apakah item sedang tersedia
            $item = \App\Models\ItemFurnitur::findOrFail($validated['id_item']);
            if ($item->status_item !== 'Tersedia') {
                return redirect()->back()
                    ->with('error', "Item dengan kode {$item->kode_item} sedang tidak tersedia (Status: {$item->status_item}).")
                    ->withInput();
            }

            // Update status item
            $item->update(['status_item' => $validated['status'] === 'aktif' ? 'Disewa' : 'Tersedia']);

            PenyewaFurnitur::create($validated);

            Log::info('PenyewaFurnitur created manually', ['user' => $validated['id_user'], 'item' => $validated['id_item']]);

            return redirect()->route('penyewa-furnitur.index')
                ->with('success', 'Data penyewa furnitur berhasil ditambahkan!');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Error storing penyewa furnitur: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menambahkan data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Return detail as JSON for modal.
     */
    public function show($id)
    {
        try {
            $record = PenyewaFurnitur::with(['item.furnitur', 'user', 'booking.kamar'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data'    => [
                    'id'            => $record->id_penyewa_furnitur,
                    'penyewa'       => $record->user->nama ?? '-',
                    'penyewa_email' => $record->user->email ?? '-',
                    'penyewa_telp'  => $record->user->no_telepon ?? '-',
                    'furnitur'      => $record->item?->furnitur?->nama_furnitur ?? '-',
                    'kode_item'     => $record->item?->kode_item ?? '-',
                    'harga'         => 'Rp ' . number_format($record->item?->furnitur?->harga_sewa_tambahan ?? 0, 0, ',', '.'),
                    'tgl_mulai'     => $record->tgl_mulai ? \Carbon\Carbon::parse($record->tgl_mulai)->format('d M Y') : '-',
                    'tgl_selesai'   => $record->tgl_selesai ? $record->tgl_selesai->format('d M Y') : 'Belum ditentukan',
                    'status'        => $record->status,
                    'catatan'       => $record->catatan ?? '-',
                    'booking_id'    => $record->id_booking ?? '-',
                    'kamar'         => $record->booking?->kamar?->nomor_kamar ?? 'Tanpa Booking',
                    'created_at'    => $record->created_at ? $record->created_at->format('d M Y H:i') : '-',
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }
    }

    /**
     * Update status & catatan.
     */
    public function update(Request $request, $id)
    {
        try {
            $record = PenyewaFurnitur::findOrFail($id);

            $validated = $request->validate([
                'status'      => 'required|in:aktif,selesai',
                'tgl_selesai' => 'nullable|date|after_or_equal:tgl_mulai',
                'catatan'     => 'nullable|string|max:500',
            ]);

            $record->update($validated);

            // Update status item_furnitur juga
            if ($record->item) {
                $record->item->update([
                    'status_item' => $validated['status'] === 'aktif' ? 'Disewa' : 'Tersedia'
                ]);
            }

            Log::info('PenyewaFurnitur updated', ['id' => $id, 'status' => $validated['status']]);

            return redirect()->route('penyewa-furnitur.index')
                ->with('success', 'Status penyewa furnitur berhasil diperbarui!');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating penyewa furnitur: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified record.
     */
    public function destroy($id)
    {
        try {
            $record = PenyewaFurnitur::findOrFail($id);
            
            // Kembalikan status item
            if ($record->item) {
                $record->item->update(['status_item' => 'Tersedia']);
            }
            
            $record->delete();

            Log::info('PenyewaFurnitur deleted', ['id' => $id]);

            return redirect()->route('penyewa-furnitur.index')
                ->with('success', 'Data penyewa furnitur berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Error deleting penyewa furnitur: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
