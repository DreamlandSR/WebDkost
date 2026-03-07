<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class OtpResetController extends Controller
{
    // Menampilkan form verifikasi OTP
    public function showVerifyForm(Request $request)
    {
        return view('auth.otp-verify');
    }

    public function showRequestForm()
    {
        return view('auth.otp-request');
    }


    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = DB::table('users')->where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        $otp = rand(100000, 999999);

        DB::table('users')->where('email', $request->email)->update([
            'otp' => $otp,
        ]);

        // Kirim email menggunakan blade template
        Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Kode OTP Reset Password');
        });

        return redirect()->route('otp.verify.form', ['email' => $request->email]);
    }

    // Memverifikasi OTP yang dimasukkan
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',  // otp kode
        ]);

        // Ambil user berdasarkan email dan OTP
        $user = DB::table('users')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$user) {
            return back()->withErrors(['otp' => 'OTP tidak valid atau sudah kadaluarsa.']);
        }

        // Arahkan ke halaman ganti password setelah OTP valid
        return redirect()->route('password.reset.form', ['email' => $request->email]);
    }

    // Menampilkan form reset password
    public function showResetPasswordForm($email)
    {
        return view('auth.reset-password', compact('email'));
    }

    // Memproses reset password
    public function resetPassword(Request $request, $email)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = DB::table('users')->where('email', $email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        // Reset password dan hapus OTP
        DB::table('users')->where('email', $email)->update([
            'password' => Hash::make($request->password),
            'otp' => null,  // Menghapus OTP setelah reset
        ]);

        return redirect()->route('login')->with('status', 'Password berhasil diubah.');
    }
}
