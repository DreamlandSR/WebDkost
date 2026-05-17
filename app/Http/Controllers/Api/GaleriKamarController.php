<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GaleriKamar;
use Illuminate\Http\Request;

class GaleriKamarController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'foto'    => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'is_main' => 'boolean',
        ]);

        $path = $request->file('foto')->store('kamar', 'public');

        $galeri = GaleriKamar::create([
            'id_kamar' => $id,
            'url_foto' => $path,
            'is_main'  => $request->is_main ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'data'    => [
                'id_foto'  => $galeri->id_foto,
                'id_kamar' => $galeri->id_kamar,
                'url_foto' => asset('storage/' . $galeri->url_foto), // generate di sini
                'is_main'  => $galeri->is_main,
            ],
        ]);
    }
}