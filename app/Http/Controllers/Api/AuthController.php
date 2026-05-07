<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use App\Mail\OtpMail;
use Google\Client as GoogleClient;

class AuthController extends Controller
{
    // ── Register (simpan pending, kirim OTP verifikasi) ──────────────
    public function register(Request $request)
    {
        $request->validate([
            'nama'     => ['required', 'string', 'regex:/^[\pL\s]+$/u'],
            'email'    => 'required|email|unique:users',
            'no_hp'    => 'required|string',
            'password' => [
                'required',
                Password::min(8)
                    ->mixedCase()   // huruf besar & kecil
                    ->symbols(),    // minimal 1 simbol
            ],
        ], [
            'nama.regex'     => 'Nama hanya boleh berisi huruf dan spasi.',
            'password.min'   => 'Password harus minimal 8 karakter.',
            'password.mixed' => 'Password harus mengandung minimal 1 huruf besar.',
            'password.symbols' => 'Password harus mengandung minimal 1 simbol (contoh: @, #, !).',
        ]);

        $otp        = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $pendingKey = "pending_register_{$request->email}";

        Cache::put($pendingKey, [
            'nama'       => $request->nama,
            'email'      => $request->email,
            'no_telepon' => $request->no_hp,
            'password'   => Hash::make($request->password),
            'alamat'     => $request->alamat,
            'otp'        => $otp,
        ], now()->addMinutes(15));

        Mail::to($request->email)->send(new OtpMail($otp, 'Verifikasi Email Registrasi'));

        return response()->json([
            'error'   => false,
            'message' => "Kode OTP verifikasi telah dikirim ke {$request->email}. Berlaku 15 menit.",
        ], 200);
    }

    // ── Verifikasi Email Registrasi ──────────────────────────────────
    public function verifikasiEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6',
        ]);

        $pendingKey = "pending_register_{$request->email}";
        $pending    = Cache::get($pendingKey);

        if (!$pending) {
            return response()->json([
                'error'   => true,
                'message' => 'Sesi registrasi tidak ditemukan atau kadaluarsa. Silakan daftar ulang.',
            ], 422);
        }

        if ($pending['otp'] !== $request->otp) {
            return response()->json([
                'error'   => true,
                'message' => 'Kode OTP tidak valid.',
            ], 422);
        }

        $user = User::create([
            'nama'              => $pending['nama'],
            'email'             => $pending['email'],
            'no_telepon'        => $pending['no_telepon'],
            'password'          => $pending['password'],
            'alamat'            => $pending['alamat'] ?? null,
            'role'              => 'penyewa',
            'email_verified_at' => now(),
        ]);

        Cache::forget($pendingKey);

        return response()->json([
            'error'   => false,
            'message' => 'Email berhasil diverifikasi. Akun anda telah aktif.',
            'data'    => [
                'id_user' => $user->id_user,
                'nama'    => $user->nama,
                'email'   => $user->email,
            ],
        ], 201);
    }

    // ── Login biasa ──────────────────────────────────────────────────
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'error'   => true,
                'message' => 'Email atau password salah.',
            ], 401);
        }

        if (!$user->hasVerifiedEmail() && !$user->google_id) {
            return response()->json([
                'error'             => true,
                'message'           => 'Email belum diverifikasi. Silakan cek inbox anda.',
                'need_verification' => true,
                'email'             => $user->email,
            ], 403);
        }

        $token = $user->createToken('dkost')->plainTextToken;

        return response()->json([
            'error'   => false,
            'message' => 'Login berhasil.',
            'token'   => $token,
            'user'    => [
                'id_user' => $user->id_user,
                'nama'    => $user->nama,
                'email'   => $user->email,
                'role'    => $user->role,
            ],
        ]);
    }

    // ── Google Login ─────────────────────────────────────────────────
    public function googleLogin(Request $request)
    {
        $request->validate([
            'id_token' => 'required|string',
        ]);

        try {
            $client  = new GoogleClient(['client_id' => env('GOOGLE_CLIENT_ID')]);
            $payload = $client->verifyIdToken($request->id_token);

            if (!$payload) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Token Google tidak valid.',
                ], 401);
            }

            $googleId = $payload['sub'];
            $email    = $payload['email'];
            $nama     = $payload['name'];

            $user = User::where('google_id', $googleId)
                        ->orWhere('email', $email)
                        ->first();

            if ($user) {
                $user->update([
                    'google_id'         => $googleId,
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ]);
            } else {
                $user = User::create([
                    'nama'              => $nama,
                    'email'             => $email,
                    'google_id'         => $googleId,
                    'password'          => Hash::make(\Illuminate\Support\Str::random(32)),
                    'role'              => 'penyewa',
                    'email_verified_at' => now(),
                ]);
            }

            if ($user->role === 'admin') {
                return response()->json([
                    'error'   => true,
                    'message' => 'Akun admin tidak dapat masuk ke aplikasi mobile.',
                ], 403);
            }

            $token = $user->createToken('dkost-google')->plainTextToken;

            return response()->json([
                'error'   => false,
                'message' => 'Login dengan Google berhasil.',
                'token'   => $token,
                'user'    => [
                    'id_user' => $user->id_user,
                    'nama'    => $user->nama,
                    'email'   => $user->email,
                    'role'    => $user->role,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error'   => true,
                'message' => 'Gagal memverifikasi akun Google: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ── Logout ───────────────────────────────────────────────────────
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['error' => false, 'message' => 'Logout berhasil.']);
    }

public function lupaPassword(Request $request)
{
    $request->validate(['email' => 'required|email|exists:users,email']);

    // ── Rate limit: 1 menit per email ──────────────────────
    $cooldownKey = "otp_cooldown_{$request->email}";
    if (Cache::has($cooldownKey)) {
        $remaining = (int) Cache::get($cooldownKey);
        return response()->json([
            'error'   => true,
            'message' => "Tunggu {$remaining} detik sebelum mengirim ulang OTP.",
        ], 429);
    }

    $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    Cache::put("otp_{$request->email}", $otp, now()->addMinutes(10));

    // Simpan cooldown 60 detik
    Cache::put($cooldownKey, 60, now()->addSeconds(60));

    Mail::to($request->email)->send(new OtpMail($otp));

    return response()->json([
        'error'   => false,
        'message' => "Kode OTP telah dikirim ke {$request->email}.",
    ]);
}

    // ── Cek OTP (Lupa Password) ──────────────────────────────────────
    public function cekOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6',
        ]);

        $cached = Cache::get("otp_{$request->email}");
        if (!$cached || $cached !== $request->otp) {
            return response()->json([
                'error'   => true,
                'message' => 'OTP tidak valid atau kadaluarsa.',
            ], 422);
        }

        Cache::put("otp_verified_{$request->email}", true, now()->addMinutes(10));
        Cache::forget("otp_{$request->email}");

        return response()->json(['error' => false, 'message' => 'OTP valid.']);
    }

    // ── Ganti Password ───────────────────────────────────────────────
public function gantiPassword(Request $request)
{
    $request->validate([
        'email'                 => 'required|email|exists:users,email',
        'password'              => [
            'required',
            Password::min(8)
                ->mixedCase()
                ->symbols(),
        ],
        'password_confirmation' => 'required|same:password',
    ], [
        'password.min'     => 'Password harus minimal 8 karakter.',
        'password.mixed'   => 'Password harus mengandung minimal 1 huruf besar.',
        'password.symbols' => 'Password harus mengandung minimal 1 simbol (contoh: @, #, !).',
    ]);

    if (!Cache::get("otp_verified_{$request->email}")) {
        return response()->json([
            'error'   => true,
            'message' => 'Verifikasi OTP diperlukan.',
        ], 403);
    }

    $user = User::where('email', $request->email)->first();

    // ── Tolak jika password baru sama dengan password lama ──
    if (Hash::check($request->password, $user->password)) {
        return response()->json([
            'error'   => true,
            'message' => 'Password baru tidak boleh sama dengan password lama.',
        ], 422);
    }

    $user->update(['password' => Hash::make($request->password)]);

    Cache::forget("otp_verified_{$request->email}");

    return response()->json([
        'error'   => false,
        'message' => 'Password berhasil diubah.',
    ]);
}

    // ── Resend OTP Register ──────────────────────────────────────────
    public function resendOtpRegister(Request $request)
{
    $request->validate(['email' => 'required|email']);

    $pendingKey = "pending_register_{$request->email}";
    $pending    = Cache::get($pendingKey);

    if (!$pending) {
        return response()->json([
            'error'   => true,
            'message' => 'Sesi registrasi tidak ditemukan. Silakan daftar ulang.',
        ], 422);
    }

    // ── Rate limit: 1 menit per email ──────────────────────
    $cooldownKey = "otp_cooldown_{$request->email}";
    if (Cache::has($cooldownKey)) {
        $remaining = (int) Cache::get($cooldownKey);
        return response()->json([
            'error'   => true,
            'message' => "Tunggu {$remaining} detik sebelum mengirim ulang OTP.",
        ], 429);
    }

    $otp            = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $pending['otp'] = $otp;
    Cache::put($pendingKey, $pending, now()->addMinutes(15));

    // Simpan cooldown 60 detik
    Cache::put($cooldownKey, 60, now()->addSeconds(60));

    Mail::to($request->email)->send(new OtpMail($otp, 'Verifikasi Email Registrasi'));

    return response()->json([
        'error'   => false,
        'message' => 'Kode OTP baru telah dikirim.',
    ]);
    }
}