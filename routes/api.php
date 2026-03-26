<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ApiProductImageController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ChatbotController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AddressController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/user/{id}', [UserController::class, 'show']);
Route::put('/user/{id}', [UserController::class, 'update']);


Route::get('/cek-api', function () {
    return response()->json(['message' => 'API terhubung!']);
});

Route::prefix('favorites')->group(function () {
    // Get user favorites - GET /api/favorites/{userId}
    Route::get('/{userId}', [FavoriteController::class, 'getFavorites'])
         ->where('userId', '[0-9]+');
    
    // Toggle favorite - POST /api/favorites/toggle
    Route::post('/toggle', [FavoriteController::class, 'toggleFavorite']);
    
    // Check if favorited - GET /api/favorites/check/{userId}/{productId}
    Route::get('/check/{userId}/{productId}', [FavoriteController::class, 'checkFavorite'])
         ->where(['userId' => '[0-9]+', 'productId' => '[0-9]+']);
});


// Favorites routes (dari sebelumnya)
Route::prefix('favorites')->group(function () {
    Route::get('/{userId}', [FavoriteController::class, 'getFavorites'])
         ->where('userId', '[0-9]+');
    
    Route::post('/toggle', [FavoriteController::class, 'toggleFavorite']);
    
    Route::get('/check/{userId}/{productId}', [FavoriteController::class, 'checkFavorite'])
         ->where(['userId' => '[0-9]+', 'productId' => '[0-9]+']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//produk

Route::prefix('products')->group(function () {
    // Get all products
    Route::get('/', [ProductController::class, 'index']);
    
    // Get product detail by ID
    Route::get('/{id}', [ProductController::class, 'show'])->where('id', '[0-9]+');
    
    // Get main image by product ID
    Route::get('/{productId}/main-image', [ProductController::class, 'getMainImageByProduct'])
        ->where('productId', '[0-9]+');
    
    // Get all images by product ID (JSON response with URLs)
    Route::get('/{productId}/images', [ProductController::class, 'getProductImages'])
        ->where('productId', '[0-9]+');
});

// Image Routes
Route::prefix('images')->group(function () {
    // Get image by image ID (with optional width parameter for resizing)
    Route::get('/{imageId}', [ProductController::class, 'getImage'])
        ->where('imageId', '[0-9]+');
    
    // Get main image by image ID - route harus sebelum route umum
    Route::get('/main/{imageId}', [ProductController::class, 'getMainImage'])
        ->where('imageId', '[0-9]+');
});




Route::prefix('v1')->group(function () {
    // Get reviews for a product
    Route::get('/reviews', [ReviewController::class, 'getProductReviews']);
    
    // Update or Delete review (unified endpoint like your original PHP)
    Route::match(['put', 'delete', 'post'], '/reviews/manage', [ReviewController::class, 'updateOrDeleteReview']);
    
    // Dedicated delete endpoint (alternative)
    Route::delete('/reviews', [ReviewController::class, 'deleteReview']);
    // Di routes/api.php
    Route::post('/reviews', [ReviewController::class, 'store']);
});

//chatbot
Route::post('/chatbot', [ChatbotController::class, 'handle']);

Route::get('/image/{id}', [ApiProductImageController::class, 'getImageBase64'])
    ->where('id', '[0-9]+');

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'getCart']);
    Route::post('/add', [CartController::class, 'addToCart']);
    Route::put('/update', [CartController::class, 'updateCart']);
    Route::delete('/remove', [CartController::class, 'removeFromCart']);
    Route::post('/create-transaction', [CartController::class, 'createTransaction']);
});

// routes/api.php
Route::prefix('api')->group(function () {
    // Order Management Routes
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);
    Route::put('/orders/{id}/payment-status', [OrderController::class, 'updatePaymentStatus']);
    Route::put('/orders/{id}/shipping', [OrderController::class, 'updateShipping']);
    Route::put('/orders/{id}/cancel', [OrderController::class, 'cancel']);
    Route::put('/orders/{id}/complete', [OrderController::class, 'complete']);
    
    // Transaction endpoint (backward compatibility)
    Route::post('/create-transaction', [OrderController::class, 'createTransaction']);
});

Route::prefix('addresses')->group(function () {
    Route::get('/', [AddressController::class, 'getAddresses']);
    Route::post('/create', [AddressController::class, 'createAddress']);
    Route::post('/update', [AddressController::class, 'updateAddress']);
    Route::post('/delete', [AddressController::class, 'deleteAddress']);
});