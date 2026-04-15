<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\OtpResetController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Payment\MidtransController;
use App\Http\Controllers\Api\GaleriKamarController;
use App\Http\Controllers\BookingController;

// ── CORS untuk storage files ────────────────────────────────
Route::get('storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        abort(404);
    }
    return response()->file($fullPath, [
        'Access-Control-Allow-Origin' => '*',
    ]);
})->where('path', '.*');

// ── Guest Routes ────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::get('/forgot-password-otp', [OtpResetController::class, 'showRequestForm'])->name('otp.request');
Route::post('/send-otp', [OtpResetController::class, 'sendOtp'])->name('otp.send');
Route::get('/verify-otp', [OtpResetController::class, 'showVerifyForm'])->name('otp.verify.form');
Route::post('/verify-otp', [OtpResetController::class, 'verifyOtp'])->name('otp.verify');
Route::get('/reset-password/{email}', [OtpResetController::class, 'showResetPasswordForm'])->name('password.reset.form');
Route::post('/reset-password/{email}', [OtpResetController::class, 'resetPassword'])->name('otp.reset.password');
Route::get('forgotPassword', [PasswordResetLinkController::class, 'create'])->name('forgot-password');

// ── Public Routes ───────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/product', [HomeController::class, 'product'])->name('product');
Route::get('/kamar/{id_kamar}', [HomeController::class, 'detailKamar'])->name('kamar.detail');

// ── Authenticated Routes ────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');

    Route::get('/ProfilePage', fn() => view("dashboard.profile"));
    Route::get('/PengaturanPage', fn() => view('dashboard.pengaturan'));

    Route::get('/PengirimanPage', [PengirimanController::class, 'index'])->name('pengiriman.index');
    Route::get('/pengiriman/create', [PengirimanController::class, 'create'])->name('pengiriman.create');
    Route::post('/pengiriman', [PengirimanController::class, 'store'])->name('pengiriman.store');
    Route::put('/pengiriman/{pengiriman}/edit', [PengirimanController::class, 'update'])->name('pengiriman.update');
    Route::delete('/pengiriman/{pengiriman}', [PengirimanController::class, 'destroy'])->name('pengiriman.destroy');
});

// ── Admin Routes ────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/laporan/keluhan', [\App\Http\Controllers\LaporanKeluhan::class, 'index'])->name('keluhan.page');
    Route::put('/laporan/keluhan/{id_keluhan}', [\App\Http\Controllers\LaporanKeluhan::class, 'updateStatus'])->name('keluhan.updateStatus');

    Route::get('/laporan/pengeluaran', [\App\Http\Controllers\LaporanPengeluaran::class, 'index'])->name('pengeluaran.page');
    Route::post('/laporan/pengeluaran/store', [\App\Http\Controllers\LaporanPengeluaran::class, 'store'])->name('pengeluaran.store');
    Route::put('/laporan/pengeluaran/{id}', [\App\Http\Controllers\LaporanPengeluaran::class, 'update'])->name('pengeluaran.update');
    Route::delete('/laporan/pengeluaran/{id}', [\App\Http\Controllers\LaporanPengeluaran::class, 'destroy'])->name('pengeluaran.destroy');

    // Kelola Kamar
    Route::get('/dashboard/kamar', [\App\Http\Controllers\KamarController::class, 'index'])->name('kamar.index');
    Route::get('/dashboard/kamar/create', [\App\Http\Controllers\KamarController::class, 'create'])->name('kamar.create');
    Route::post('/dashboard/kamar', [\App\Http\Controllers\KamarController::class, 'store'])->name('kamar.store');
    Route::get('/dashboard/kamar/{id_kamar}', [\App\Http\Controllers\KamarController::class, 'show'])->name('kamar.show');
    Route::get('/dashboard/kamar/{id_kamar}/edit', [\App\Http\Controllers\KamarController::class, 'edit'])->name('kamar.edit');
    Route::put('/dashboard/kamar/{id_kamar}', [\App\Http\Controllers\KamarController::class, 'update'])->name('kamar.update');
    Route::delete('/dashboard/kamar/{id_kamar}', [\App\Http\Controllers\KamarController::class, 'destroy'])->name('kamar.destroy');

    // Kelola Booking
    Route::get('/dashboard/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/dashboard/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/dashboard/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/dashboard/booking/{id_booking}', [BookingController::class, 'show'])->name('booking.show');
    Route::get('/dashboard/booking/{id_booking}/edit', [BookingController::class, 'edit'])->name('booking.edit');
    Route::put('/dashboard/booking/{id_booking}', [BookingController::class, 'update'])->name('booking.update');
    Route::delete('/dashboard/booking/{id_booking}', [BookingController::class, 'destroy'])->name('booking.destroy');

    // Kelola User
    Route::get('/dashboard/user', [\App\Http\Controllers\UserController::class, 'index'])->name('user.index');
    Route::post('/dashboard/user', [\App\Http\Controllers\UserController::class, 'store'])->name('user.store');
    Route::get('/dashboard/user/{id_user}', [\App\Http\Controllers\UserController::class, 'show'])->name('user.show');
    Route::put('/dashboard/user/{id_user}', [\App\Http\Controllers\UserController::class, 'update'])->name('user.update');
    Route::delete('/dashboard/user/{id_user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('user.destroy');

    // Halaman register hanya bisa diakses admin
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/AdminPage', [AdminController::class, 'index'])->name('admin');
});

require __DIR__ . '/auth.php';
