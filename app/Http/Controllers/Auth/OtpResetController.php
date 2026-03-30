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

        if ($user->role !== 'admin') {
            return back()->withErrors(['email' => 'Hanya admin yang dapat melakukan reset password.']);
        }

        $otp = rand(100000, 999999);

        // Simpan OTP ke session bukan database
        session([
            'otp_code' => $otp,
            'otp_email' => $request->email,
            'otp_expires_at' => now()->addMinutes(15) // Berlaku 15 menit
        ]);

        // Kirim email menggunakan blade template dengan penanganan error
        try {
            Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Kode OTP Reset Password');
            });
        } catch (\Exception $e) {
            // Jika gagal kirim email, hapus session agar tidak nanggung
            session()->forget(['otp_code', 'otp_email', 'otp_expires_at']);
            
            return back()->withInput()->withErrors([
                'email' => 'Gagal mengirim email. Silakan cek koneksi internet atau coba lagi nanti. (Error: ' . $e->getMessage() . ')'
            ]);
        }

        return redirect()->route('otp.verify.form', ['email' => $request->email]);
    }

    // Memverifikasi OTP yang dimasukkan
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',  // otp kode
        ]);

        // Ambil OTP dari session
        $session_otp = session('otp_code');
        $session_email = session('otp_email');
        $session_expires = session('otp_expires_at');

        if (!$session_otp || $session_otp != $request->otp || $session_email != $request->email) {
            return back()->withErrors(['otp' => 'OTP tidak valid atau email salah.']);
        }

        if (now()->isAfter($session_expires)) {
            session()->forget(['otp_code', 'otp_email', 'otp_expires_at']);
            return back()->withErrors(['otp' => 'OTP sudah kadaluarsa. Silakan kirim ulang.']);
        }

        // Tandai session sebagai terverifikasi
        session(['otp_verified' => true]);

        // Arahkan ke halaman ganti password setelah OTP valid
        return redirect()->route('password.reset.form', ['email' => $request->email]);
    }

    // Menampilkan form reset password
    public function showResetPasswordForm($email)
    {
        if (!session('otp_verified') || session('otp_email') != $email) {
            return redirect()->route('otp.request')->withErrors(['email' => 'Sesi verifikasi tidak valid.']);
        }
        return view('auth.reset-password', compact('email'));
    }

    // Memproses reset password
    public function resetPassword(Request $request, $email)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        if (!session('otp_verified') || session('otp_email') != $email) {
            return redirect()->route('otp.request')->withErrors(['email' => 'Sesi verifikasi telah berakhir.']);
        }

        $user = DB::table('users')->where('email', $email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        // Reset password dan hapus session OTP
        DB::table('users')->where('email', $email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Hapus session setelah berhasil
        session()->forget(['otp_code', 'otp_email', 'otp_expires_at', 'otp_verified']);

        return redirect()->route('login')->with('status', 'Password berhasil diubah.');
    }
}
