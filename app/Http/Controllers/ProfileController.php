<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id_user . ',id_user',
            'no_telepon' => 'nullable|string|max:20', // Sesuaikan dengan kolom db: no_telepon
            'alamat' => 'nullable|string', // Tambahkan validasi alamat
        ]);

        $user->nama = $request->input('nama');
        $user->email = $request->input('email');
        $user->no_telepon = $request->input('no_telepon'); // Sesuaikan dengan kolom db
        $user->alamat = $request->input('alamat'); // Simpan alamat

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }

    public function edit()
{
    return view('dashboard.profile'); // Ganti dengan nama view kamu
}

    public function updatePhoto(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Hapus lama
        if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
        }


        $file = $request->file('avatar');
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('avatars', $filename, 'public');

        $user->avatar = $filename;
        $user->save();

        return Redirect::back()->with('success', 'Foto profil diperbarui.');
    }
}
