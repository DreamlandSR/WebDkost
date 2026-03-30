<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = 5;
        $page = $request->get('page', 1);
        $statusFilter = $request->get('status_filter', '');
        $search = $request->get('search', '');

        // Get sample data
        $bookings = $this->getSampleBookings();
        
        // Filter berdasarkan status jika ada
        if ($statusFilter) {
            $bookings = array_filter($bookings, function($booking) use ($statusFilter) {
                return $booking['status_pembayaran'] === $statusFilter;
            });
        }

        // Search berdasarkan nama jika ada
        if ($search) {
            $bookings = array_filter($bookings, function($booking) use ($search) {
                return stripos($booking['nama'], $search) !== false;
            });
        }

        // Paginate
        $total = count($bookings);
        $totalPages = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;
        $paginatedBookings = array_slice($bookings, $offset, $perPage);

        return view('dashboard.booking.index', [
            'bookings' => $paginatedBookings,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'statusFilter' => $statusFilter,
            'search' => $search,
            'total' => $total,
        ]);
    }

    /**
     * Get sample booking data for demonstration
     */
    private function getSampleBookings()
    {
        return [
            [
                'id' => 1,
                'nama' => 'Rahman',
                'status_pembayaran' => 'Berhasil',
                'tanggal_pembayaran' => '2026-02-15',
                'jumlah_pembayaran' => 600000,
            ],
            [
                'id' => 2,
                'nama' => 'Sa\'i',
                'status_pembayaran' => 'Menunggu',
                'tanggal_pembayaran' => '2026-03-27',
                'jumlah_pembayaran' => 500000,
            ],
            [
                'id' => 3,
                'nama' => 'Rifeki',
                'status_pembayaran' => 'Dibayar',
                'tanggal_pembayaran' => '2026-04-21',
                'jumlah_pembayaran' => 900000,
            ],
            [
                'id' => 4,
                'nama' => 'Riyan',
                'status_pembayaran' => 'Piutangan',
                'tanggal_pembayaran' => '2026-02-17',
                'jumlah_pembayaran' => 800000,
            ],
            [
                'id' => 5,
                'nama' => 'Raki',
                'status_pembayaran' => 'Berhasil',
                'tanggal_pembayaran' => '2026-04-17',
                'jumlah_pembayaran' => 750000,
            ],
            [
                'id' => 6,
                'nama' => 'Ahmadi Supri',
                'status_pembayaran' => 'Berhasil',
                'tanggal_pembayaran' => '2026-03-20',
                'jumlah_pembayaran' => 700000,
            ],
        ];
    }

    public function show(string $id)
    {
        //
    }
}
