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

    // Pesanan
    Route::get('PesananPage', [OrderController::class, 'index'])->name('pesanan.page');
    Route::get('/order/{order}/edit', [OrderController::class, 'edit'])->name('order.edit');
    Route::put('/order/{order}', [OrderController::class, 'update'])->name('order.update');
    Route::delete('/order/{order}', [OrderController::class, 'destroy'])->name('order.destroy');
    Route::get('DetailOrder', [OrderController::class, 'detailOrder'])->name('detail.page');
    Route::get('/export-pesanan/pdf', [OrderController::class, 'exportPDF'])->name('export.pesanan.pdf');
    Route::get('/export-pesanan/tahunan', [OrderController::class, 'exportRekapTahunan'])->name('export.pesanan.tahunan');

    // Payment
    Route::get('PaymentPage', [PaymentController::class, 'index'])->name('payment.page');

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
