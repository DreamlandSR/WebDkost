<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'no_hp'    => 'required|string',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'nama'       => $request->nama,
            'email'      => $request->email,
            'no_telepon' => $request->no_hp,
            'password'   => Hash::make($request->password),
            'role'       => 'penyewa',
        ]);

        return response()->json([
            'error'   => false,
            'message' => 'Registrasi berhasil.',
            'data'    => $user,
        ], 201);
    }

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

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'error'   => false,
            'message' => 'Logout berhasil.',
        ]);
    }

    public function lupaPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put("otp_{$request->email}", $otp, now()->addMinutes(10));
        return response()->json([
            'error'   => false,
            'message' => "OTP dikirim ke {$request->email}.",
            'otp'     => $otp, // hapus di production
        ]);
    }

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

    public function gantiPassword(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email|exists:users,email',
            'password'              => 'required|min:6',
            'password_confirmation' => 'required|same:password',
        ]);
        if (!Cache::get("otp_verified_{$request->email}")) {
            return response()->json([
                'error'   => true,
                'message' => 'Verifikasi OTP diperlukan.',
            ], 403);
        }
        User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);
        Cache::forget("otp_verified_{$request->email}");
        return response()->json([
            'error'   => false,
            'message' => 'Password berhasil diubah.',
        ]);
    }
}