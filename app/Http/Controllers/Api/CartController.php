<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

// Handle preflight requests


class CartController extends Controller
{
    /**
     * Get cart items for a specific user
     */
    public function getCart(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User ID is required and must be valid',
                    'errors' => $validator->errors()
                ], 400);
            }

            $userId = $request->user_id;

            // Check if cart table exists and has data
            $cartCount = DB::table('cart')->where('user_id', $userId)->count();

            // Main query to get cart data with product details and stock
            $cartItems = DB::table('cart as c')
                ->join('products as p', 'c.product_id', '=', 'p.id')
                ->leftJoin('stocks as s', 'p.stok_id', '=', 's.id')
                ->select(
                    'c.id as cart_id',
                    'c.product_id',
                    'c.quantity as cart_quantity',
                    'c.added_at',
                    'c.updated_at',
                    'p.nama as product_name',
                    'p.deskripsi as product_description',
                    'p.harga as product_price',
                    'p.stok_id',
                    'p.status as product_status',
                    'p.rating as product_rating',
                    'p.berat as product_weight',
                    'p.created_at as product_created_at',
                    's.quantity as stock_quantity',
                    's.updated_at as stock_updated_at'
                )
                ->where('c.user_id', $userId)
                ->orderBy('c.updated_at', 'desc')
                ->get();

            $processedItems = [];
            $totalAmount = 0;
            $totalWeight = 0;

            foreach ($cartItems as $item) {
                // Get product images
                $images = DB::table('product_images')
                    ->select('id', 'is_main')
                    ->where('product_id', $item->product_id)
                    ->orderBy('is_main', 'desc')
                    ->orderBy('id', 'asc')
                    ->get()
                    ->map(function ($img) {
                        return [
                            'id' => (int)$img->id,
                            'image_url' => url('api/product-images/' . $img->id),
                            'is_main' => (int)$img->is_main === 1
                        ];
                    });

                // Calculate subtotal and weight
                $cartQuantity = (int)$item->cart_quantity;
                $productPrice = (float)$item->product_price;
                $productWeight = (int)$item->product_weight;
                $subtotal = $productPrice * $cartQuantity;
                $itemTotalWeight = $productWeight * $cartQuantity;

                $totalAmount += $subtotal;
                $totalWeight += $itemTotalWeight;

                // Check stock availability
                $stockQuantity = (int)$item->stock_quantity;
                $isAvailable = $stockQuantity >= $cartQuantity;

                $processedItems[] = [
                    'cart_id' => (int)$item->cart_id,
                    'product_id' => (int)$item->product_id,
                    'quantity' => $cartQuantity,
                    'subtotal' => $subtotal,
                    'total_weight' => $itemTotalWeight,
                    'added_at' => $item->added_at,
                    'updated_at' => $item->updated_at,
                    'is_available' => $isAvailable,
                    'product' => [
                        'id' => (int)$item->product_id,
                        'nama' => $item->product_name,
                        'deskripsi' => $item->product_description,
                        'harga' => $productPrice,
                        'berat' => $productWeight,
                        'stok_id' => (int)$item->stok_id,
                        'status' => $item->product_status,
                        'rating' => $item->product_rating ? (float)$item->product_rating : null,
                        'created_at' => $item->product_created_at,
                        'stock' => [
                            'quantity' => $stockQuantity,
                            'updated_at' => $item->stock_updated_at,
                            'is_sufficient' => $isAvailable
                        ],
                        'images' => $images
                    ]
                ];
            }

            // Calculate summary with availability check
            $availableItems = array_filter($processedItems, function ($item) {
                return $item['is_available'];
            });

            $availableTotal = array_sum(array_map(function ($item) {
                return $item['is_available'] ? $item['subtotal'] : 0;
            }, $processedItems));

            $availableWeight = array_sum(array_map(function ($item) {
                return $item['is_available'] ? $item['total_weight'] : 0;
            }, $processedItems));

            return response()->json([
                'success' => true,
                'message' => 'Cart items retrieved successfully',
                'data' => [
                    'cart_items' => $processedItems,
                    'summary' => [
                        'total_items' => count($processedItems),
                        'total_quantity' => array_sum(array_column($processedItems, 'quantity')),
                        'total_amount' => $totalAmount,
                        'total_weight' => $totalWeight,
                        'available_items' => count($availableItems),
                        'available_total_amount' => $availableTotal,
                        'available_total_weight' => $availableWeight,
                        'has_unavailable_items' => count($availableItems) < count($processedItems)
                    ]
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add product to cart
     */
    public function addToCart(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|min:1',
                'product_id' => 'required|integer|min:1',
                'quantity' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing required fields: user_id, product_id, quantity',
                    'errors' => $validator->errors()
                ], 400);
            }

            $userId = $request->user_id;
            $productId = $request->product_id;
            $quantity = $request->quantity;

            // Check if user exists
            $userExists = DB::table('users')->where('id', $userId)->exists();
            if (!$userExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Check if product exists and get stock information
            $product = DB::table('products as p')
                ->leftJoin('stocks as s', 'p.stok_id', '=', 's.id')
                ->select('p.id', 'p.nama', 'p.harga', 's.quantity as stock')
                ->where('p.id', $productId)
                ->where('p.status', 'available')
                ->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found or not available'
                ], 404);
            }

            // Check stock availability
            $availableStock = (int)$product->stock;
            if ($availableStock <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product is out of stock'
                ], 400);
            }

            if ($quantity > $availableStock) {
                return response()->json([
                    'success' => false,
                    'message' => "Only $availableStock items available in stock"
                ], 400);
            }

            DB::beginTransaction();

            // Check if item already exists in cart
            $existingItem = DB::table('cart')
                ->select('id', 'quantity as current_quantity')
                ->where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            $message = '';

            if ($existingItem) {
                // Update existing cart item
                $newQuantity = $existingItem->current_quantity + $quantity;

                if ($newQuantity > $availableStock) {
                    $remainingStock = $availableStock - $existingItem->current_quantity;
                    return response()->json([
                        'success' => false,
                        'message' => "Cannot add $quantity items. Only $remainingStock more items can be added"
                    ], 400);
                }

                DB::table('cart')
                    ->where('id', $existingItem->id)
                    ->update([
                        'quantity' => $newQuantity,
                        'updated_at' => now()
                    ]);

                $message = "Cart updated successfully. Total quantity: $newQuantity";
            } else {
                // Add new item to cart
                DB::table('cart')->insert([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'added_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $message = "Product added to cart successfully";
            }

            // Get updated cart info
            $cartInfo = DB::table('cart as c')
                ->join('products as p', 'c.product_id', '=', 'p.id')
                ->where('c.user_id', $userId)
                ->selectRaw('
                    COUNT(*) as cart_count,
                    COALESCE(SUM(c.quantity), 0) as total_items,
                    COALESCE(SUM(c.quantity * p.harga), 0) as total_price
                ')
                ->first();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'cart_count' => (int)$cartInfo->cart_count,
                'total_items' => (int)$cartInfo->total_items,
                'total_price' => (float)$cartInfo->total_price,
                'product_name' => $product->nama
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update cart item quantity
     */
    public function updateCart(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cart_id' => 'required|integer|min:1',
                'quantity' => 'required|integer|min:1',
                'user_id' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing required fields: cart_id, quantity, user_id',
                    'errors' => $validator->errors()
                ], 400);
            }

            $cartId = $request->cart_id;
            $quantity = $request->quantity;
            $userId = $request->user_id;

            // Verify cart item belongs to user and get product info
            $cartItem = DB::table('cart as c')
                ->join('products as p', 'c.product_id', '=', 'p.id')
                ->leftJoin('stocks as s', 'p.stok_id', '=', 's.id')
                ->select(
                    'c.id as cart_id',
                    'c.product_id',
                    'c.quantity as current_quantity',
                    'c.user_id',
                    'p.nama as product_name',
                    'p.harga as product_price',
                    's.quantity as stock_quantity'
                )
                ->where('c.id', $cartId)
                ->where('c.user_id', $userId)
                ->first();

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart item not found or unauthorized access'
                ], 404);
            }

            // Check stock availability
            $stockQuantity = (int)$cartItem->stock_quantity;
            if ($quantity > $stockQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Only $stockQuantity items available in stock",
                    'available_stock' => $stockQuantity,
                    'requested_quantity' => $quantity
                ], 400);
            }

            // Update cart item quantity
            DB::table('cart')
                ->where('id', $cartId)
                ->where('user_id', $userId)
                ->update([
                    'quantity' => $quantity,
                    'updated_at' => now()
                ]);

            // Calculate new subtotal
            $productPrice = (float)$cartItem->product_price;
            $newSubtotal = $productPrice * $quantity;

            return response()->json([
                'success' => true,
                'message' => 'Cart item updated successfully',
                'data' => [
                    'cart_id' => $cartId,
                    'product_name' => $cartItem->product_name,
                    'old_quantity' => (int)$cartItem->current_quantity,
                    'new_quantity' => $quantity,
                    'product_price' => $productPrice,
                    'new_subtotal' => $newSubtotal
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cart_id' => 'required|integer|min:1',
                'user_id' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing required fields: cart_id, user_id',
                    'errors' => $validator->errors()
                ], 400);
            }

            $cartId = $request->cart_id;
            $userId = $request->user_id;

            // Verify cart item belongs to user and get product info
            $cartItem = DB::table('cart as c')
                ->join('products as p', 'c.product_id', '=', 'p.id')
                ->select(
                    'c.id as cart_id',
                    'c.product_id',
                    'c.quantity',
                    'c.user_id',
                    'p.nama as product_name'
                )
                ->where('c.id', $cartId)
                ->where('c.user_id', $userId)
                ->first();

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart item not found or unauthorized access'
                ], 404);
            }

            // Delete cart item
            DB::table('cart')
                ->where('id', $cartId)
                ->where('user_id', $userId)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart successfully',
                'data' => [
                    'cart_id' => $cartId,
                    'product_name' => $cartItem->product_name,
                    'removed_quantity' => (int)$cartItem->quantity
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

        public function createTransaction(Request $request)
{
    // Force JSON response even on errors
    try {
        // Log incoming request for debugging
        \Log::info('Create Transaction Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'headers' => $request->headers->all(),
            'body' => $request->all()
        ]);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'total_harga' => 'required|numeric|min:0',
            'alamat_pemesanan' => 'required|string',
            'metode_pengiriman' => 'required|string',
            'metode_pembayaran' => 'required|string',
            'subtotal_items' => 'required|numeric|min:0',
            'jumlah_items' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.kuantitas' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.nama_produk' => 'nullable|string',
            'items.*.berat' => 'nullable|integer|min:0',
            'items.*.product_image' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            \Log::warning('Validation failed', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        // Check if user exists with more detailed logging
        $user = DB::table('users')->where('id', $request->user_id)->first();
        if (!$user) {
            \Log::error("User not found", ['user_id' => $request->user_id]);
            throw new Exception("Invalid user_id. User does not exist.");
        }

        \Log::info('User found', ['user_id' => $request->user_id, 'user' => $user]);

        // Generate order ID
        $orderID = $this->generateOrderID();
        \Log::info('Generated order ID', ['order_id' => $orderID]);

        // Create order with explicit field mapping
        $orderData = [
            'user_id' => $request->user_id,
            'waktu_order' => now(),
            'status' => 'pending',
            'total_harga' => (float) $request->total_harga,
            'alamat_pemesanan' => $request->alamat_pemesanan,
            'metode_pengiriman' => $request->metode_pengiriman,
            'notes' => $request->notes ?? '',
            'created_at' => now()
        ];

        \Log::info('Creating order', $orderData);

        $orderIdDb = DB::table('orders')->insertGetId($orderData);
        
        if (!$orderIdDb) {
            throw new Exception("Failed to create order record");
        }

        \Log::info('Order created', ['order_id_db' => $orderIdDb, 'order_id' => $orderID]);

        // Process order items
        $processedItems = [];
        foreach ($request->items as $index => $item) {
            \Log::info("Processing item $index", $item);

            // Check product and stock
            $product = DB::table('products as p')
                ->join('stocks as s', 'p.stok_id', '=', 's.id')
                ->select('p.id', 'p.stok_id', 's.quantity', 'p.nama as product_name')
                ->where('p.id', $item['product_id'])
                ->first();

            if (!$product) {
                \Log::error("Product not found", ['product_id' => $item['product_id']]);
                throw new Exception("Product ID {$item['product_id']} does not exist.");
            }

            if ($product->quantity < $item['kuantitas']) {
                \Log::error("Insufficient stock", [
                    'product_id' => $item['product_id'],
                    'requested' => $item['kuantitas'],
                    'available' => $product->quantity
                ]);
                throw new Exception("Product '{$product->product_name}' has insufficient stock. Available: {$product->quantity}, Requested: {$item['kuantitas']}");
            }

            // Insert order item
            $orderItemData = [
                'order_id' => $orderIdDb,
                'product_id' => $item['product_id'],
                'kuantitas' => $item['kuantitas'],
                'harga' => (float) $item['harga']
            ];

            \Log::info('Creating order item', $orderItemData);
            
            $orderItemId = DB::table('order_items')->insertGetId($orderItemData);
            
            if (!$orderItemId) {
                throw new Exception("Failed to create order item for product ID {$item['product_id']}");
            }

            // Update stock
            $stockUpdated = DB::table('stocks')
                ->where('id', $product->stok_id)
                ->decrement('quantity', $item['kuantitas']);

            if (!$stockUpdated) {
                throw new Exception("Failed to update stock for product ID {$item['product_id']}");
            }

            // Check if out of stock
            $updatedStock = DB::table('stocks')->where('id', $product->stok_id)->first();
            if ($updatedStock && $updatedStock->quantity <= 0) {
                DB::table('products')
                    ->where('id', $item['product_id'])
                    ->update(['status' => 'out_of_stock']);
                \Log::info("Product marked as out of stock", ['product_id' => $item['product_id']]);
            }

            $processedItems[] = [
                'product_id' => $item['product_id'],
                'product_name' => $product->product_name,
                'quantity' => $item['kuantitas'],
                'price' => $item['harga'],
                'order_item_id' => $orderItemId
            ];
        }

        // Create payment record
        $paymentData = [
            'order_id' => $orderIdDb,
            'metode_pembayaran' => $request->metode_pembayaran,
            'status_pembayaran' => 'pending',
            'waktu_pembayaran' => now()
        ];

        \Log::info('Creating payment record', $paymentData);
        
        $paymentId = DB::table('payments')->insertGetId($paymentData);
        
        if (!$paymentId) {
            throw new Exception("Failed to create payment record");
        }

        DB::commit();

        $responseData = [
            'success' => true,
            'message' => 'Transaction created successfully',
            'data' => [
                'order_id' => $orderID,
                'order_id_db' => $orderIdDb,
                'payment_id' => $paymentId,
                'total' => (float) $request->total_harga,
                'status' => 'pending',
                'payment_method' => $request->metode_pembayaran,
                'items_processed' => count($processedItems),
                'processed_items' => $processedItems
            ]
        ];

        \Log::info('Transaction completed successfully', $responseData);

        return response()->json($responseData, 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        \Log::error('Validation exception', ['errors' => $e->errors()]);
        
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Transaction creation failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Transaction failed: ' . $e->getMessage(),
            'error_details' => [
                'line' => $e->getLine(),
                'file' => basename($e->getFile())
            ]
        ], 500);

    } catch (\Throwable $e) {
        DB::rollBack();
        \Log::error('Unexpected error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'An unexpected error occurred',
            'error' => $e->getMessage()
        ], 500);
    }
}

    private function generateOrderID()
    {
        $datePrefix = date('Ymd');
        
        // Get the latest order ID for today
        $maxId = DB::table('orders')
            ->whereDate('waktu_order', today())
            ->max('id');
        
        $sequenceNumber = $maxId ? $maxId + 1 : 1;
        $formattedSequence = str_pad($sequenceNumber, 4, "0", STR_PAD_LEFT);
        
        return "UMKM-{$datePrefix}-{$formattedSequence}";
    }

// Add a simple test endpoint for debugging
public function testConnection()
{
    try {
        return response()->json([
            'success' => true,
            'message' => 'Server is working',
            'timestamp' => now(),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Test failed: ' . $e->getMessage()
        ], 500);
    }
}
}