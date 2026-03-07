<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class searchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json([]);
        }

        $users = User::where('nama', 'like', "%$query%")->get();

        return response()->json($users);
    }
}
