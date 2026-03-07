<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{

    public function index()
    {
        $products = Product::with('images')->get();

        return view('home.index', [
            'judul' => 'Beranda',
            'css' => ['nav.css', 'styles.css', 'ionicons.min.css'],
            'minimal_header' => true,
            'products' => $products
        ]);
    }



    public function login()
    {
        $data = [
            'judul' => 'Login',
            'css' => ['styles.css', 'ionicons.min.css'],
            'minimal_header' => true
        ];

        return view('home.login', $data);
    }

    public function about()
    {
        $data = [
            'judul' => 'About',
            'css' => ['nav.css', 'styles.css', 'ionicons.min.css'],
            'minimal_header' => true,
            'js' => 'script.js'
        ];

        return view('home.about', $data);
    }

    public function guide()
    {
        $data = [
            'judul' => 'Guide',
            'css' => ['nav.css', 'styles.css', 'ionicons.min.css'],
            'minimal_header' => true,
            'js' => 'script.js'
        ];

        return view('home.guide', $data);
    }

    public function product()
    {
        $products = Product::with('mainImage')->orderBy('id', 'asc')->get();

        $data = [
            'judul' => 'Product',
            'css' => ['nav.css', 'styles.css', 'ionicons.min.css'],
            'minimal_header' => true,
            'js' => 'script.js',
            'products' => $products
        ];

        return view('home.product', $data);
    }
}
