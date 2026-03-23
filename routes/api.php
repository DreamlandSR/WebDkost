<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\KamarController;
use App\Http\Controllers\API\FurniturController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\TagihanController;
use App\Http\Controllers\API\PembayaranController;
use App\Http\Controllers\API\KeluhanController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\GaleriKamarController;

// ── PUBLIC ────────────────────────────────────────────────
Route::post('/register',       [AuthController::class, 'register']);
Route::post('/login',          [AuthController::class, 'login']);
Route::post('/lupa-password',  [AuthController::class, 'lupaPassword']);
Route::post('/cek-otp',        [AuthController::class, 'cekOtp']);
Route::post('/ganti-password', [AuthController::class, 'gantiPassword']);

Route::get('/cek-api', function () {
    return response()->json(['message' => 'API D\'Kost terhubung!']);
});

Route::post('/galeri-kamar/{id}', [GaleriKamarController::class, 'store']);

// Webhook Midtrans — PUBLIC (tidak pakai auth)
Route::post('/pembayaran/webhook', [PembayaranController::class, 'webhook']);

// Image proxy — PUBLIC
Route::get('/image/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) abort(404);

    $ext     = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
    $mimeMap = [
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'webp' => 'image/webp',
        'gif'  => 'image/gif',
    ];
    $mimeType = $mimeMap[$ext] ?? 'image/jpeg';

    return response()->stream(function () use ($fullPath) {
        $handle = fopen($fullPath, 'rb');
        while (!feof($handle)) {
            echo fread($handle, 8192);
            flush();
        }
        fclose($handle);
    }, 200, [
        'Content-Type'                => $mimeType,
        'Content-Length'              => filesize($fullPath),
        'Access-Control-Allow-Origin' => '*',
        'Cache-Control'               => 'public, max-age=86400',
    ]);
})->where('path', '.*');

// ── PROTECTED (auth:sanctum) ──────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // User
    Route::get('/user/{id}', [UserController::class, 'show']);
    Route::put('/user/{id}', [UserController::class, 'update']);

    // Kamar
    Route::get('/kamar',      [KamarController::class, 'index']);
    Route::get('/kamar/{id}', [KamarController::class, 'show']);

    // Furnitur
    Route::get('/furnitur', [FurniturController::class, 'index']);

    // Booking
    Route::post('/booking',              [BookingController::class, 'store']);
    Route::get('/booking/user/{userId}', [BookingController::class, 'indexByUser']);
    Route::get('/booking/{id}',          [BookingController::class, 'show']);
    Route::put('/booking/{id}/batal',    [BookingController::class, 'batal']);

    // Tagihan
    Route::get('/tagihan/booking/{bookingId}', [TagihanController::class, 'indexByBooking']);
    Route::get('/tagihan/{id}',                [TagihanController::class, 'show']);
    Route::get('/tagihan/bulan-ini/{bookingId}',  [TagihanController::class, 'cekBulanIni']);  // ← baru
    Route::delete('/tagihan/{id}',                [TagihanController::class, 'destroy']);       // ← baru

    // Pembayaran — spesifik dulu, baru dinamis
    Route::post('/pembayaran',                       [PembayaranController::class, 'store']);
    Route::get('/pembayaran/status/{idTagihan}',     [PembayaranController::class, 'checkStatus']);
    Route::get('/pembayaran/{id}',                   [PembayaranController::class, 'show']);

    // Keluhan
    Route::post('/keluhan',              [KeluhanController::class, 'store']);
    Route::get('/keluhan/user/{userId}', [KeluhanController::class, 'indexByUser']);
    Route::put('/keluhan/{id}', [KeluhanController::class, 'update']);
    
    // Review
    Route::post('/review',                [ReviewController::class, 'store']);
    Route::get('/review/kamar/{kamarId}', [ReviewController::class, 'indexByKamar']);
    Route::put('/review/{id}',            [ReviewController::class, 'update']);
    Route::delete('/review/{id}',         [ReviewController::class, 'destroy']);
});