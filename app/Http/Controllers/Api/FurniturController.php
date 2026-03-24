<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Furnitur;

class FurniturController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data'    => Furnitur::all(),
        ]);
    }
}