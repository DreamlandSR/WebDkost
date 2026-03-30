<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 404);
        }
        return response()->json([
            'success' => true,
            'data'    => [
                'id_user'    => $user->id_user,
                'nama'       => $user->nama,
                'email'      => $user->email,
                'no_telepon' => $user->no_telepon,
                'alamat'     => $user->alamat,
                'role'       => $user->role,
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'   => 'required|string',
            'email'  => "required|email|unique:users,email,{$id},id_user",
            'no_hp'  => 'required|string',
            'alamat' => 'nullable|string',
        ]);

        $user = User::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 404);
        }

        $user->update([
            'nama'       => $request->nama,
            'email'      => $request->email,
            'no_telepon' => $request->no_hp,
            'alamat'     => $request->alamat,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
        ]);
    }
}