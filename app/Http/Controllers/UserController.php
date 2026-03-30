<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
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
        $users = $this->getSampleUsers();
        
        // Search berdasarkan nama atau nomor kamar jika ada
        if ($search) {
            $users = array_filter($users, function($user) use ($search) {
                return stripos($user['nama'], $search) !== false || 
                       stripos($user['nomor_kamar'], $search) !== false;
            });
        }

        // Paginate
        $total = count($users);
        $totalPages = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;
        $paginatedUsers = array_slice($users, $offset, $perPage);

        return view('dashboard.user.index', [
            'users' => $paginatedUsers,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'total' => $total,
        ]);
    }

    /**
     * Get sample user data for demonstration
     */
    private function getSampleUsers()
    {
        return [
            [
                'id' => 1,
                'nomor_kamar' => 'D1',
                'nama' => 'Rahman',
                'no_hp' => '087766661212',
                'total_harga' => 600000,
            ],
            [
                'id' => 2,
                'nomor_kamar' => 'D2',
                'nama' => 'Ahamad',
                'no_hp' => '087755661212',
                'total_harga' => 500000,
            ],
            [
                'id' => 3,
                'nomor_kamar' => 'D3',
                'nama' => 'Rifki',
                'no_hp' => '085746461312',
                'total_harga' => 900000,
            ],
            [
                'id' => 4,
                'nomor_kamar' => 'D4',
                'nama' => 'Riyan',
                'no_hp' => '081746121121',
                'total_harga' => 800000,
            ],
            [
                'id' => 5,
                'nomor_kamar' => 'D5',
                'nama' => 'Ahmadi Supri',
                'no_hp' => '082766661212',
                'total_harga' => 700000,
            ],
            [
                'id' => 6,
                'nomor_kamar' => 'D6',
                'nama' => 'Budi Santoso',
                'no_hp' => '083666661212',
                'total_harga' => 750000,
            ],
        ];
    }

    public function create()
    {
        return view('dashboard.user.create');
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
