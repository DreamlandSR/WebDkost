<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class FavoriteController extends Controller
{
    /**
     * Get user's favorites
     * GET /api/favorites/{userId}
     */
    public function getFavorites(Request $request, $userId): JsonResponse
    {
        try {
            // Validate user_id
            if (!is_numeric($userId) || $userId <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid user ID'
                ], 400);
            }

            // Get favorites for the user
            $favorites = Favorite::forUser($userId)
                               ->pluck('product_id')
                               ->toArray();

            return response()->json([
                'success' => true,
                'favorites' => $favorites
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get favorites',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle favorite status
     * POST /api/favorites/toggle
     */
    public function toggleFavorite(Request $request): JsonResponse
    {
        try {
            // Validate request
            $validated = $request->validate([
                'user_id' => 'required|integer|min:1',
                'product_id' => 'required|integer|min:1'
            ]);

            $userId = $validated['user_id'];
            $productId = $validated['product_id'];

            // Check if favorite exists
            $favorite = Favorite::where('user_id', $userId)
                              ->where('product_id', $productId)
                              ->first();

            if ($favorite) {
                // Remove from favorites
                $favorite->delete();
                
                return response()->json([
                    'success' => true,
                    'favorited' => false,
                    'message' => 'Removed from favorites'
                ]);
            } else {
                // Add to favorites
                Favorite::create([
                    'user_id' => $userId,
                    'product_id' => $productId
                ]);

                return response()->json([
                    'success' => true,
                    'favorited' => true,
                    'message' => 'Added to favorites'
                ]);
            }

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle favorite',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if product is favorited by user
     * GET /api/favorites/check/{userId}/{productId}
     */
    public function checkFavorite($userId, $productId): JsonResponse
    {
        try {
            $isFavorited = Favorite::isFavorited($userId, $productId);

            return response()->json([
                'success' => true,
                'favorited' => $isFavorited
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check favorite status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}