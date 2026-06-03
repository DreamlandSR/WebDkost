<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail; // Tambahkan ini

class RegisteredUserController extends Controller
{
    // 1. Menampilkan halaman form pendaftaran awal
    public function create()
    {
        return view('auth.register');
    }

    // 2. Memvalidasi form, buat OTP, simpan ke Session, dan kirim Email
    public function store(Request $request)
    {
        // agar email yang masuk selalu dalam bentuk lowercase, untuk menghindari masalah unik di database
        $request->merge([
        'email' => strtolower($request->email),
    ]);

        $validatedData = $request->validate([
            'nama_depan' => ['required', 'string', 'max:255'],
            'nama_belakang' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Generate 6 digit OTP acak
        $otp = rand(100000, 999999);

        // Simpan data pendaftaran dan OTP ke dalam Session
        Session::put('register_data', $validatedData);
        Session::put('register_otp', $otp);
        Session::put('otp_email', $validatedData['email']);
        Session::put('otp_created_at', now());

            // Kirim Email HTML (mengarah ke file emails/otp.blade.php)
           Mail::send('emails.otp-verification', ['otp' => $otp], function ($message) use ($validatedData) {
                $message->to($validatedData['email'])
                        ->subject('Verifikasi Kode OTP D\'Kost');
            });

        // Alihkan user ke halaman verifikasi OTP
        return redirect()->route('otp.verify.form')->with('success', 'Kode OTP telah dikirim ke email Anda. Silakan cek Inbox atau Spam.');
    }

    // 3. Menampilkan halaman input OTP
    public function showOtpForm()
    {
        // Jika user memaksa masuk ke URL ini tanpa mengisi form register, kembalikan ke form
        if (!Session::has('register_data')) {
            return redirect('/register');
        }

        return view('auth.otp-verify');
    }

    // 4. Memeriksa OTP dan membuat akun di Database
    public function verifyOtp(Request $request)
    {
        // Validasi input form OTP
        $request->validate([
            'otp' => ['required', 'numeric']
        ]);

        $sessionOtp = Session::get('register_otp');
        $userData = Session::get('register_data');
        $createdAt = Session::get('otp_created_at');

        // Cek apakah session OTP ada dan apakah sudah lewat 10 menit
        if (!$sessionOtp || !$createdAt || now()->diffInMinutes($createdAt) > 10) {
            // Hapus session jika sudah kadaluwarsa
            Session::forget(['register_otp', 'otp_created_at']);

            return back()->withErrors(['otp' => 'Kode OTP telah kadaluwarsa (lebih dari 10 menit). Silakan register ulang.']);
        }

        // Pengecekan OTP
        if ($request->otp == $sessionOtp) {
            // OTP Benar -> Masukkan data ke Database
            $namaBelakang = !empty($userData['nama_belakang']) ? ' ' . $userData['nama_belakang'] : '';
            $user = User::create([
                'nama' => $userData['nama_depan'] . $namaBelakang,
                'email' => $userData['email'],
                'no_telepon' => $userData['no_hp'],
                'alamat' => $userData['alamat'] ?? null,
                'password' => bcrypt($userData['password']),
                'role' => 'admin',
            ]);

            // Gunakan flush atau hapus semua yang berkaitan dengan registrasi
            Session::forget(['register_data', 'register_otp', 'otp_email', 'otp_created_at']);

            // Login user secara otomatis
            Auth::login($user);

            // Redirect ke halaman sukses pendaftaran
            return redirect()->route('register.success');
        }

        // OTP Salah -> Kembalikan ke halaman input OTP dengan pesan error
        return back()->withErrors(['otp' => 'Kode OTP salah atau tidak valid. Silakan coba lagi.']);
    }

    // 5. Menampilkan halaman sukses setelah pendaftaran
    public function showSuccess()
    {
        return view('auth.register-success');
    }
}
