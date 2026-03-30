<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FurnitureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = 5;
        $page = $request->get('page', 1);
        $search = $request->get('search', '');

        // Get sample data
        $furnitures = $this->getSampleFurnitures();
        
        // Search berdasarkan nama furnitur atau nomor kamar jika ada
        if ($search) {
            $furnitures = array_filter($furnitures, function($furniture) use ($search) {
                return stripos($furniture['nama_furnitur'], $search) !== false || 
                       stripos($furniture['nomor_kamar'], $search) !== false;
            });
        }

        // Paginate
        $total = count($furnitures);
        $totalPages = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;
        $paginatedFurnitures = array_slice($furnitures, $offset, $perPage);

        return view('dashboard.furniture.index', [
            'furnitures' => $paginatedFurnitures,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'total' => $total,
        ]);
    }

    /**
     * Get sample furniture data for demonstration
     */
    private function getSampleFurnitures()
    {
        return [
            [
                'id' => 1,
                'nomor_kamar' => 'D1',
                'nama_furnitur' => 'Lemari, Kursi',
                'jumlah' => 2,
                'harga_sewa' => 75000,
            ],
            [
                'id' => 2,
                'nomor_kamar' => 'D2',
                'nama_furnitur' => 'Lemari, Kuas angin',
                'jumlah' => 2,
                'harga_sewa' => 300000,
            ],
            [
                'id' => 3,
                'nomor_kamar' => 'D3',
                'nama_furnitur' => 'Lemari, Kursi',
                'jumlah' => 2,
                'harga_sewa' => 200000,
            ],
            [
                'id' => 4,
                'nomor_kamar' => 'D4',
                'nama_furnitur' => 'Kursi',
                'jumlah' => 1,
                'harga_sewa' => 100000,
            ],
            [
                'id' => 5,
                'nomor_kamar' => 'D5',
                'nama_furnitur' => 'Kursi',
                'jumlah' => 1,
                'harga_sewa' => 240000,
            ],
            [
                'id' => 6,
                'nomor_kamar' => 'D6',
                'nama_furnitur' => 'Tempat Tidur',
                'jumlah' => 1,
                'harga_sewa' => 150000,
            ],
        ];
    }

    public function create()
    {
        return view('dashboard.furniture.create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
