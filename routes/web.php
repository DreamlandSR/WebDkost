<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\OtpResetController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Payment\MidtransController;
use App\Http\Controllers\Api\GaleriKamarController;
/*
|--------------------------------------------------------------------------
| Guest Routes (untuk tamu yang belum login)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/forgot-password-otp', [OtpResetController::class, 'showRequestForm'])->name('otp.request');
    Route::post('/send-otp', [OtpResetController::class, 'sendOtp'])->name('otp.send');

    Route::get('/verify-otp', [OtpResetController::class, 'showVerifyForm'])->name('otp.verify.form');
    Route::post('/verify-otp', [OtpResetController::class, 'verifyOtp'])->name('otp.verify');

    Route::get('/reset-password/{email}', [OtpResetController::class, 'showResetPasswordForm'])->name('password.reset.form');
    Route::post('/reset-password/{email}', [OtpResetController::class, 'resetPassword'])->name('otp.reset.password');

    Route::get('forgotPassword', [PasswordResetLinkController::class, 'create'])->name('forgot-password');
});

// CORS untuk storage files
// Override storage route dengan CORS
Route::get('storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    
    if (!file_exists($fullPath)) {
        abort(404);
    }
    
    return response()->file($fullPath, [
        'Access-Control-Allow-Origin' => '*',
    ]);
})->where('path', '.*')->name('storage.local');
/*
|--------------------------------------------------------------------------
| Public Routes (bisa diakses semua orang)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/product', [HomeController::class, 'product'])->name('product');
Route::get('/galeri', [ProductController::class, 'showGallery'])->name('produk.galeri');

Route::get('/product-image/{id}', [ProductController::class, 'showImage'])->name('product.image');


/*
|--------------------------------------------------------------------------
| Authenticated Routes (hanya untuk user login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');

    Route::get('/ProfilePage', function () {
        return view("dashboard.profile");
    });

    Route::get('/PengaturanPage', function () {
        return view('dashboard.pengaturan');
    });

    Route::get('/ProductPage', [ProductController::class, 'index'])->name('products.index');

    // Pengiriman
    Route::get('/PengirimanPage', [PengirimanController::class, 'index'])->name('pengiriman.index');
    Route::get('/pengiriman/create', [PengirimanController::class, 'create'])->name('pengiriman.create');
    Route::post('/pengiriman', [PengirimanController::class, 'store'])->name('pengiriman.store');
    Route::put('/pengiriman/{pengiriman}/edit', [PengirimanController::class, 'update'])->name('pengiriman.update');
    Route::delete('/pengiriman/{pengiriman}', [PengirimanController::class, 'destroy'])->name('pengiriman.destroy');

    // Product management via resource
    Route::resource('/dashboard/products', ProductController::class);
});

/*
|--------------------------------------------------------------------------
| Admin Routes (hanya untuk user login dan role admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/terlaris', [AdminController::class, 'produkTerlaris'])->name('admin.terlaris');

    // Laporan Keluhan
    Route::get('/laporan/keluhan', [\App\Http\Controllers\LaporanKeluhan::class, 'index'])->name('keluhan.page');
    Route::put('/laporan/keluhan/{id_keluhan}', [\App\Http\Controllers\LaporanKeluhan::class, 'updateStatus'])->name('keluhan.updateStatus');

    // Laporan Pengeluaran
    Route::get('/laporan/pengeluaran', [\App\Http\Controllers\LaporanPengeluaran::class, 'index'])->name('pengeluaran.page');
    Route::post('/laporan/pengeluaran/store', [\App\Http\Controllers\LaporanPengeluaran::class, 'store'])->name('pengeluaran.store');
    Route::put('/laporan/pengeluaran/{id}', [\App\Http\Controllers\LaporanPengeluaran::class, 'update'])->name('pengeluaran.update');
    Route::delete('/laporan/pengeluaran/{id}', [\App\Http\Controllers\LaporanPengeluaran::class, 'destroy'])->name('pengeluaran.destroy');
    // Halaman register hanya bisa diakses admin
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    // Tambahan halaman admin jika ada
    Route::get('/AdminPage', [AdminController::class, 'index'])->name('admin');
});

/*
|--------------------------------------------------------------------------
| Include default auth routes (jika pakai Laravel Breeze/Fortify)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';


//Midtrans routes
Route::middleware('auth')->group(function () {
    Route::get('/payment/{orderId}', [PaymentController::class, 'show'])->name('payment.show');
    Route::get('/payment/success',   [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/pending',   [PaymentController::class, 'pending'])->name('payment.pending');
    Route::get('/payment/failed',    [PaymentController::class, 'failed'])->name('payment.failed');
});