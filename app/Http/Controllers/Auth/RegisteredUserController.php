<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RegisteredUserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'no_hp' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => bcrypt($request->password),
            'role' => 'admin',
        ]);

        // Login user secara otomatis setelah registrasi
        Auth::login($user);

        // Redirect ke halaman register dengan pesan sukses
        return redirect('/register')->with('success', 'Registrasi berhasil!');
    }

    public function create()
    {
        // Jika user sudah login, tetap tampilkan halaman register
        return view('auth.register');
    }
}
