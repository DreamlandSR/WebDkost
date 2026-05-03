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
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Payment\MidtransController;
use App\Http\Controllers\Api\GaleriKamarController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FurnitureController;

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
    Route::get('/laporan/pengeluaran/export', [\App\Http\Controllers\LaporanPengeluaran::class, 'exportExcel'])->name('pengeluaran.export');
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
    Route::delete('/kamar/image/{id_galeri}', [\App\Http\Controllers\KamarController::class, 'deleteImage'])->name('kamar.delete-image');
    Route::put('/kamar/image/{id_galeri}/main', [\App\Http\Controllers\KamarController::class, 'setMainImage'])->name('kamar.set-main-image');

    // Kelola Booking
    Route::get('/dashboard/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/dashboard/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/dashboard/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/dashboard/booking/{id_booking}', [BookingController::class, 'show'])->name('booking.show');
    Route::get('/dashboard/booking/{id_booking}/edit', [BookingController::class, 'edit'])->name('booking.edit');
    Route::put('/dashboard/booking/{id_booking}', [BookingController::class, 'update'])->name('booking.update');
    Route::delete('/dashboard/booking/{id_booking}', [BookingController::class, 'destroy'])->name('booking.destroy');

    // Kelola Furnitur
    Route::get('/dashboard/furnitur', [FurnitureController::class, 'index'])->name('furnitur.index');
    Route::post('/dashboard/furnitur', [FurnitureController::class, 'store'])->name('furnitur.store');
    Route::get('/dashboard/furnitur/{id_furnitur}', [FurnitureController::class, 'show'])->name('furnitur.show');
    Route::put('/dashboard/furnitur/{id_furnitur}', [FurnitureController::class, 'update'])->name('furnitur.update');
    Route::delete('/dashboard/furnitur/{id_furnitur}', [FurnitureController::class, 'destroy'])->name('furnitur.destroy');

    // Kelola User
    Route::get('/dashboard/user', [\App\Http\Controllers\UserController::class, 'index'])->name('user.index');
    Route::post('/dashboard/user', [\App\Http\Controllers\UserController::class, 'store'])->name('user.store');
    Route::get('/dashboard/user/{id_user}', [\App\Http\Controllers\UserController::class, 'show'])->name('user.show');
    Route::put('/dashboard/user/{id_user}', [\App\Http\Controllers\UserController::class, 'update'])->name('user.update');
    Route::delete('/dashboard/user/{id_user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('user.destroy');

    // Kelola Tagihan
    Route::get('/dashboard/tagihan', [TagihanController::class, 'index'])->name('tagihan.index');
    Route::post('/dashboard/tagihan', [TagihanController::class, 'store'])->name('tagihan.store');
    Route::get('/dashboard/tagihan/{id_tagihan}', [TagihanController::class, 'show'])->name('tagihan.show');
    Route::put('/dashboard/tagihan/{id_tagihan}', [TagihanController::class, 'update'])->name('tagihan.update');
    Route::delete('/dashboard/tagihan/{id_tagihan}', [TagihanController::class, 'destroy'])->name('tagihan.destroy');

    // Halaman register hanya bisa diakses admin
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::post('/register/verify-otp', [RegisteredUserController::class, 'verifyOtp'])->name('register.verify-otp');
    Route::get('/register/success', [RegisteredUserController::class, 'showSuccess'])->name('register.success');
    Route::get('/AdminPage', [AdminController::class, 'index'])->name('admin');
});

// routes untuk AJAX
Route::prefix('kamar')->group(function () {
    Route::delete('image/{id_galeri}', [\App\Http\Controllers\KamarController::class, 'deleteImage'])->name('kamar.delete-image');
    Route::put('image/{id_galeri}/main', [\App\Http\Controllers\KamarController::class, 'setMainImage'])->name('kamar.set-main-image');
});

require __DIR__ . '/auth.php';
