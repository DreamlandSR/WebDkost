<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kamar;

class HomeController extends Controller
{
    public function index()
    {
        $kamars = Kamar::with('galeri')->get();

        return view('home.index', [
            'judul' => 'Beranda',
            'css' => ['nav.css', 'styles.css', 'ionicons.min.css'],
            'minimal_header' => true,
            'kamars' => $kamars
        ]);
    }

    public function login()
    {
        return view('home.login', [
            'judul' => 'Login',
            'css' => ['styles.css', 'ionicons.min.css'],
            'minimal_header' => true
        ]);
    }

    public function about()
    {
        return view('home.about', [
            'judul' => 'About',
            'css' => ['nav.css', 'styles.css', 'ionicons.min.css'],
            'minimal_header' => true,
            'js' => 'script.js'
        ]);
    }

    public function guide()
    {
        return view('home.guide', [
            'judul' => 'Guide',
            'css' => ['nav.css', 'styles.css', 'ionicons.min.css'],
            'minimal_header' => true,
            'js' => 'script.js'
        ]);
    }

    public function product()
    {
        $kamars = Kamar::with('galeri')->orderBy('id_kamar', 'asc')->get();

        return view('home.product', [
            'judul' => 'Kamar',
            'css' => ['nav.css', 'styles.css', 'ionicons.min.css'],
            'minimal_header' => true,
            'js' => 'script.js',
            'kamars' => $kamars
        ]);
    }
}