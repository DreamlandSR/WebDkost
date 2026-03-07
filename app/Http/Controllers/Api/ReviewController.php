<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    /**
     * Get all reviews for a specific product
     */
    public function getProductReviews(Request $request): JsonResponse
{
    $request->validate([
        'product_id' => 'required|integer'
    ]);

    $product_id = $request->get('product_id');

    try {
        $reviews = Review::select(
                'reviews.id', 
                'reviews.rating', 
                'reviews.komentar', 
                'reviews.created_at', 
                'reviews.user_id',  // TAMBAHKAN INI
                'users.nama'
            )
            ->join('users', 'reviews.user_id', '=', 'users.id')
            ->where('reviews.product_id', $product_id)
            ->orderBy('reviews.created_at', 'desc')
            ->get();

        return response()->json($reviews);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Update or Delete review (handles both PUT and DELETE)
     */
    public function updateOrDeleteReview(Request $request): JsonResponse
    {
        // Validate required fields
        $request->validate([
            'user_id' => 'required|integer',
            'product_id' => 'required|integer'
        ]);

        $user_id = $request->input('user_id');
        $product_id = $request->input('product_id');

        try {
            // Find the review
            $review = Review::where('user_id', $user_id)
                          ->where('product_id', $product_id)
                          ->first();

            if (!$review) {
                return response()->json([
                    'message' => 'Review tidak ditemukan'
                ], 404);
            }

            // Handle DELETE method
            if ($request->isMethod('delete') || $request->header('X-HTTP-Method-Override') === 'DELETE') {
                $review->delete();
                
                return response()->json([
                    'message' => 'Review berhasil dihapus',
                    'success' => true
                ]);
            }

            // Handle PUT method (Update)
            if ($request->isMethod('put') || $request->header('X-HTTP-Method-Override') === 'PUT') {
                // Check if at least one field is provided for update
                if (!$request->has('rating') && !$request->has('komentar')) {
                    return response()->json([
                        'message' => 'Minimal satu field harus diubah: rating atau komentar'
                    ], 400);
                }

                // Update fields if provided
                if ($request->has('rating')) {
                    $request->validate(['rating' => 'integer|min:1|max:5']);
                    $review->rating = $request->input('rating');
                }

                if ($request->has('komentar')) {
                    $review->komentar = $request->input('komentar');
                }

                $review->save();

                return response()->json([
                    'message' => 'Review berhasil diperbarui',
                    'success' => true
                ]);
            }

            return response()->json([
                'message' => 'Method not allowed'
            ], 405);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete review (dedicated DELETE endpoint)
     */
    // Di ReviewController.php
public function store(Request $request): JsonResponse
{
    $request->validate([
        'user_id' => 'required|integer',
        'product_id' => 'required|integer',
        'rating' => 'required|integer|min:1|max:5',
        'komentar' => 'required|string'
    ]);

    try {
        // Check if review already exists
        $existingReview = Review::where('user_id', $request->user_id)
                              ->where('product_id', $request->product_id)
                              ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah memberikan review untuk produk ini'
            ], 409);
        }

        $review = Review::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'komentar' => $request->komentar
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil ditambahkan',
            'data' => $review
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal menambahkan review: ' . $e->getMessage()
        ], 500);
    }
}
    public function deleteReview(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer',
            'product_id' => 'required|integer'
        ]);

        $user_id = $request->input('user_id');
        $product_id = $request->input('product_id');

        try {
            $deleted = Review::where('user_id', $user_id)
                           ->where('product_id', $product_id)
                           ->delete();

            if ($deleted === 0) {
                return response()->json([
                    'message' => 'Review tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'message' => 'Review berhasil dihapus',
                'success' => true
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}