<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /** CSS yang dipakai di semua halaman publik */
    private const PUBLIC_CSS = ['nav.css', 'styles.css', 'ionicons.min.css'];

    private function baseViewData(string $judul, array $extra = []): array
    {
        return array_merge([
            'judul'          => $judul,
            'css'            => self::PUBLIC_CSS,
            'minimal_header' => true,
        ], $extra);
    }

    /**
     * Halaman utama / beranda.
     */
    public function index()
    {
        $kamars = Kamar::with(['galeri', 'reviews'])->get();

        return view('home.index', $this->baseViewData('Beranda', [
            'kamars'       => $kamars,
            'company_name' => "D'Kost",
            'tagline'      => 'Platform untuk pemasaran kos secara online dan terpercaya',
            'description'  => 'Kos kami dilengkapi dengan fasilitas yang lengkap.',
        ]));
    }

    /**
     * Halaman login.
     */
    public function login()
    {
        return view('auth.login', [
            'judul'          => 'Login',
            'css'            => ['styles.css', 'ionicons.min.css'],
            'minimal_header' => true,
        ]);
    }

    /**
     * Halaman tentang kami.
     */
    public function about()
    {
        return view('home.about', $this->baseViewData('About', [
            'js' => 'script.js',
        ]));
    }

    /**
     * Halaman panduan / guide.
     */
    public function guide()
    {
        return view('home.guide', $this->baseViewData('Guide', [
            'js' => 'script.js',
        ]));
    }

    /**
     * Halaman daftar kamar.
     */
    public function product()
    {
        $kamars = Kamar::with(['galeri', 'reviews', 'fasilitas', 'bookings'])
                    ->get()
                    ->sortByDesc(fn ($k) => [$k->bookings->count(), $k->rating])
                    ->values();

        return view('home.product', $this->baseViewData('Kamar', [
            'kamars' => $kamars,
            'js'     => 'script.js',
        ]));
    }

    /**
     * Halaman detail kamar.
     */
    public function detailKamar($id_kamar)
    {
        $kamar = Kamar::with([
            'galeri',
            'fasilitas',
            'reviews.user',
            'bookings',
        ])->findOrFail($id_kamar);

        // Kamar lain untuk rekomendasi (kecuali kamar ini, max 3)
        $rekomendasi = Kamar::with(['galeri', 'reviews', 'fasilitas'])
                        ->where('id_kamar', '!=', $id_kamar)
                        ->get()
                        ->sortByDesc(fn ($k) => $k->rating)
                        ->take(3)
                        ->values();

        return view('home.detail_kamar', $this->baseViewData('Detail Kamar', [
            'kamar'       => $kamar,
            'rekomendasi' => $rekomendasi,
            'js'          => 'script.js',
        ]));
    }
}