<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = 5;
        $page = $request->get('page', 1);
        $status = $request->get('status', '');

        // Get sample data
        $kamars = $this->getSampleKamars();
        
        // Filter berdasarkan status jika ada
        if ($status) {
            $kamars = array_filter($kamars, function($kamar) use ($status) {
                return $kamar['status'] === $status;
            });
        }

        // Paginate
        $total = count($kamars);
        $totalPages = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;
        $paginatedKamars = array_slice($kamars, $offset, $perPage);

        return view('dashboard.kamar.index', [
            'kamars' => $paginatedKamars,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'status' => $status,
            'total' => $total,
        ]);
    }

    /**
     * Get sample kamar data for demonstration
     */
    private function getSampleKamars()
    {
        return [
            [
                'id' => 1,
                'nomor_kamar' => 'A101',
                'tipe_kamar' => 'Standard',
                'fasilitas' => 'WiFi, AC, Kasur, Lemari',
                'harga' => 1500000,
                'status' => 'Tersedia'
            ],
            [
                'id' => 2,
                'nomor_kamar' => 'A102',
                'tipe_kamar' => 'Premium',
                'fasilitas' => 'WiFi, AC, Kasur, Lemari, TV, Kamar Mandi Pribadi',
                'harga' => 2000000,
                'status' => 'Terisi'
            ],
            [
                'id' => 3,
                'nomor_kamar' => 'B201',
                'tipe_kamar' => 'Standard',
                'fasilitas' => 'WiFi, AC, Kasur, Lemari',
                'harga' => 1500000,
                'status' => 'Tersedia'
            ],
            [
                'id' => 4,
                'nomor_kamar' => 'B202',
                'tipe_kamar' => 'Deluxe',
                'fasilitas' => 'WiFi, AC, Kasur, Lemari, TV, Kamar Mandi Pribadi, Mini Bar',
                'harga' => 2500000,
                'status' => 'Maintenance'
            ],
            [
                'id' => 5,
                'nomor_kamar' => 'C301',
                'tipe_kamar' => 'Standard',
                'fasilitas' => 'WiFi, AC, Kasur, Lemari',
                'harga' => 1500000,
                'status' => 'Terisi'
            ],
            [
                'id' => 6,
                'nomor_kamar' => 'C302',
                'tipe_kamar' => 'Premium',
                'fasilitas' => 'WiFi, AC, Kasur, Lemari, TV, Kamar Mandi Pribadi',
                'harga' => 2000000,
                'status' => 'Tersedia'
            ],
            [
                'id' => 7,
                'nomor_kamar' => 'D401',
                'tipe_kamar' => 'Standard',
                'fasilitas' => 'WiFi, AC, Kasur, Lemari',
                'harga' => 1500000,
                'status' => 'Tersedia'
            ],
        ];
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
        // TODO: Implement store logic with validation
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
