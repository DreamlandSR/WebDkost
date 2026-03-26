<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Kamar;
class HomeController extends Controller
{
    public function index()
    {
        $products = Kamar::with('galeri')->get();
        $data = [
            'judul' => 'Beranda',
            'css' => ['nav.css', 'styles.css', 'ionicons.min.css'],
            'minimal_header' => true,
            'products' => $products,
            'company_name' => 'D\'Kost',
            'tagline' => 'Platform Untuk pemasaran kos secara online dan terpercaya',
            'description' => 'Kos kami dilengkapi dengan fasilitas yang lengkap.'
        ];
        return view('home.index', $data);
    }
    public function login()
    {
        $data = ['judul' => 'Login', 'css' => ['styles.css', 'ionicons.min.css'], 'minimal_header' => true];
        return view('auth.login', $data);
    }
    public function about()
    {
        $data = ['judul' => 'About', 'css' => ['nav.css', 'styles.css', 'ionicons.min.css'], 'minimal_header' => true, 'js' => 'script.js'];
        return view('home.about', $data);
    }
    public function guide()
    {
        $data = ['judul' => 'Guide', 'css' => ['nav.css', 'styles.css', 'ionicons.min.css'], 'minimal_header' => true, 'js' => 'script.js'];
        return view('home.guide', $data);
    }
    public function product()
    {
        $products = Kamar::with('galeri')->orderBy('id_kamar', 'asc')->get();
        $data = ['judul' => 'Kamar', 'css' => ['nav.css', 'styles.css', 'ionicons.min.css'], 'minimal_header' => true, 'js' => 'script.js', 'products' => $products];
        return view('home.product', $data);
    }
}
