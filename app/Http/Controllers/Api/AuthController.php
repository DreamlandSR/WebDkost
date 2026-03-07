<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = DB::table('users')->where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => true, 'message' => 'Email tidak ditemukan!']);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['error' => true, 'message' => 'Password salah!']);
        }
        unset($user->password);

        return response()->json([
            'error' => false,
            'message' => 'Login berhasil!',
            'user' => $user
        ]);
    }

    public function register(Request $request)
    {
        $exists = DB::table('users')->where('email', $request->email)->exists();

        if ($exists) {
            return response()->json(['error' => true, 'message' => 'Email sudah terdaftar!']);
        }

        DB::table('users')->insert([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json(['error' => false, 'message' => 'Registrasi berhasil!']);
    }

    public function getUser($id)
    {
        $user = DB::table('users')->find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        return response()->json(['success' => true, 'data' => $user]);
    }

    public function updateUser(Request $request)
    {
        DB::table('users')->where('id', $request->id)->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
        ]);

        return response()->json(['success' => true, 'message' => 'Profil berhasil diperbarui']);
    }
}
