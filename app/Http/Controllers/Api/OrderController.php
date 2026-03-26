<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class OrderController extends Controller
{
    /**
     * Get orders by user ID and optional status filter
     */
    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|exists:users,id',
                'status' => 'nullable|string|in:pending,paid,shipped,completed,cancelled'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 400);
            }

            $userId = $request->user_id;
            $status = $request->status;

            $query = DB::table('orders')->where('user_id', $userId);
            
            if ($status) {
                $query->where('status', $status);
            }
            
            $orders = $query->orderBy('waktu_order', 'desc')->get();

            // Add order items and shipping info for each order
            foreach ($orders as &$order) {
                $order->status_display = $this->mapStatusForUI($order->status);
                
                // Get order items with base64 images
                $order->items = DB::table('order_items as oi')
                    ->join('products as p', 'oi.product_id', '=', 'p.id')
                    ->leftJoin('product_images as pi', function($join) {
                        $join->on('pi.product_id', '=', 'p.id')
                             ->where('pi.is_main', '=', 1);
                    })
                    ->select('oi.*', 'p.nama', 'p.deskripsi', 'pi.image_product')
                    ->where('oi.order_id', $order->id)
                    ->get();

                // Convert image to base64 for each item
                foreach ($order->items as &$item) {
                    $item->image_base64 = $item->image_product ? 
                        'data:image/jpeg;base64,' . base64_encode($item->image_product) : null;
                    // Remove the binary data from response
                    unset($item->image_product);
                }

                // Get shipping info
                $shipping = DB::table('pengiriman')->where('order_id', $order->id)->first();
                if ($shipping) {
                    $shipping->status_display = $this->mapShippingStatusForUI($shipping->status_pengiriman);
                    $order->shipping = $shipping;
                }
            }

            return response()->json([
                'status' => 'success',
                'data' => $orders
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific order details
     */
    public function show(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 400);
            }

            $userId = $request->user_id;

            $order = DB::table('orders')
                ->where('id', $id)
                ->where('user_id', $userId)
                ->first();

            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Order not found'
                ], 404);
            }

            $order->status_display = $this->mapStatusForUI($order->status);

            // Get order items with base64 images
            $order->items = DB::table('order_items as oi')
                ->join('products as p', 'oi.product_id', '=', 'p.id')
                ->leftJoin('product_images as pi', function($join) {
                    $join->on('pi.product_id', '=', 'p.id')
                         ->where('pi.is_main', '=', 1);
                })
                ->select('oi.*', 'p.nama', 'p.deskripsi', 'pi.image_product')
                ->where('oi.order_id', $id)
                ->get();

            // Convert image to base64 for each item
            foreach ($order->items as &$item) {
                $item->image_base64 = $item->image_product ? 
                    'data:image/jpeg;base64,' . base64_encode($item->image_product) : null;
                // Remove the binary data from response
                unset($item->image_product);
            }

            // Get payment info
            $payment = DB::table('payments')->where('order_id', $id)->first();
            if ($payment) {
                $order->payment = $payment;
            }

            // Get shipping info
            $shipping = DB::table('pengiriman')->where('order_id', $id)->first();
            if ($shipping) {
                $shipping->status_display = $this->mapShippingStatusForUI($shipping->status_pengiriman);
                $order->shipping = $shipping;
            }

            return response()->json([
                'status' => 'success',
                'data' => $order
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create new order
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|exists:users,id',
                'alamat_pemesanan' => 'required|string',
                'metode_pengiriman' => 'required|string',
                'metode_pembayaran' => 'nullable|string',
                'notes' => 'nullable|string',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|integer|exists:products,id',
                'items.*.kuantitas' => 'required|integer|min:1',
                'items.*.harga' => 'required|numeric|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 400);
            }

            DB::beginTransaction();

            // Create order
            $orderId = DB::table('orders')->insertGetId([
                'user_id' => $request->user_id,
                'waktu_order' => now(),
                'status' => 'pending',
                'alamat_pemesanan' => $request->alamat_pemesanan,
                'metode_pengiriman' => $request->metode_pengiriman,
                'notes' => $request->notes ?? '',
                'created_at' => now()
            ]);

            $totalHarga = 0;

            // Add order items and check stock
            foreach ($request->items as $item) {
                // Check product and stock
                $product = DB::table('products as p')
                    ->join('stocks as s', 'p.stok_id', '=', 's.id')
                    ->select('p.id', 'p.stok_id', 's.quantity')
                    ->where('p.id', $item['product_id'])
                    ->where('s.quantity', '>=', $item['kuantitas'])
                    ->first();

                if (!$product) {
                    throw new Exception("Product ID {$item['product_id']} does not exist or has insufficient stock.");
                }

                $subtotal = $item['harga'] * $item['kuantitas'];
                $totalHarga += $subtotal;

                // Insert order item
                DB::table('order_items')->insert([
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'kuantitas' => $item['kuantitas'],
                    'harga' => $item['harga']
                ]);

                // Update stock
                DB::table('stocks')
                    ->where('id', $product->stok_id)
                    ->decrement('quantity', $item['kuantitas']);

                // Check if out of stock
                $updatedStock = DB::table('stocks')->where('id', $product->stok_id)->first();
                if ($updatedStock->quantity <= 0) {
                    DB::table('products')
                        ->where('id', $item['product_id'])
                        ->update(['status' => 'out_of_stock']);
                }
            }

            // Update order total
            DB::table('orders')
                ->where('id', $orderId)
                ->update(['total_harga' => $totalHarga]);

            // Add payment record if method provided
            if ($request->metode_pembayaran) {
                DB::table('payments')->insert([
                    'order_id' => $orderId,
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'status_pembayaran' => 'pending',
                    'waktu_pembayaran' => now()
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Order created successfully',
                'data' => [
                    'order_id' => $orderId,
                    'total' => $totalHarga,
                    'status' => 'pending'
                ]
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create transaction (backward compatibility with old API)
     */
    public function createTransaction(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|exists:users,id',
                'total_harga' => 'required|numeric|min:0',
                'alamat_pemesanan' => 'required|string',
                'metode_pengiriman' => 'required|string',
                'metode_pembayaran' => 'required|string',
                'notes' => 'nullable|string',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|integer|exists:products,id',
                'items.*.kuantitas' => 'required|integer|min:1',
                'items.*.harga' => 'required|numeric|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing required data'
                ], 400);
            }

            DB::beginTransaction();

            // Check if user exists
            $userExists = DB::table('users')->where('id', $request->user_id)->exists();
            if (!$userExists) {
                throw new Exception("Invalid user_id. User does not exist.");
            }

            // Generate order ID
            $orderID = $this->generateOrderID();

            // Create order
            $orderIdDb = DB::table('orders')->insertGetId([
                'user_id' => $request->user_id,
                'waktu_order' => now(),
                'status' => 'pending',
                'total_harga' => $request->total_harga,
                'alamat_pemesanan' => $request->alamat_pemesanan,
                'metode_pengiriman' => $request->metode_pengiriman,
                'notes' => $request->notes ?? '',
                'created_at' => now()
            ]);

            // Add order items and update stock
            foreach ($request->items as $item) {
                // Check product and stock
                $product = DB::table('products as p')
                    ->join('stocks as s', 'p.stok_id', '=', 's.id')
                    ->select('p.id', 'p.stok_id', 's.quantity')
                    ->where('p.id', $item['product_id'])
                    ->where('s.quantity', '>=', $item['kuantitas'])
                    ->first();

                if (!$product) {
                    throw new Exception("Product ID {$item['product_id']} does not exist or has insufficient stock.");
                }

                // Insert order item
                DB::table('order_items')->insert([
                    'order_id' => $orderIdDb,
                    'product_id' => $item['product_id'],
                    'kuantitas' => $item['kuantitas'],
                    'harga' => $item['harga']
                ]);

                // Update stock
                DB::table('stocks')
                    ->where('id', $product->stok_id)
                    ->decrement('quantity', $item['kuantitas']);

                // Check if out of stock
                $updatedStock = DB::table('stocks')->where('id', $product->stok_id)->first();
                if ($updatedStock->quantity <= 0) {
                    DB::table('products')
                        ->where('id', $item['product_id'])
                        ->update(['status' => 'out_of_stock']);
                }
            }

            // Create payment record
            DB::table('payments')->insert([
                'order_id' => $orderIdDb,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_pembayaran' => 'pending',
                'waktu_pembayaran' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaction created successfully',
                'data' => [
                    'order_id' => $orderID,
                    'order_id_db' => $orderIdDb,
                    'total' => $request->total_harga,
                    'status' => 'pending',
                    'payment_method' => $request->metode_pembayaran
                ]
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|string|in:pending,paid,shipped,completed,cancelled'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 400);
            }

            $updated = DB::table('orders')
                ->where('id', $id)
                ->update(['status' => $request->status]);

            if ($updated) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Order status updated'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update order status'
                ], 500);
            }

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'payment_status' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 400);
            }

            $updated = DB::table('payments')
                ->where('order_id', $id)
                ->update(['status_pembayaran' => $request->payment_status]);

            if ($updated) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment status updated'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update payment status'
                ], 500);
            }

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update shipping information
     */
    public function updateShipping(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nomor_resi' => 'required|string',
                'jasa_kurir' => 'required|string',
                'status_pengiriman' => 'nullable|string|in:diproses,dikirim,dalam_perjalanan,sampai,gagal',
                'catatan' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 400);
            }

            $statusPengiriman = $request->status_pengiriman ?? 'dikirim';
            $catatan = $request->catatan;

            // Check if shipping record exists
            $shippingExists = DB::table('pengiriman')->where('order_id', $id)->exists();

            if ($shippingExists) {
                // Update existing record
                DB::table('pengiriman')
                    ->where('order_id', $id)
                    ->update([
                        'nomor_resi' => $request->nomor_resi,
                        'jasa_kurir' => $request->jasa_kurir,
                        'status_pengiriman' => $statusPengiriman,
                        'tanggal_dikirim' => $statusPengiriman == 'dikirim' ? now() : DB::raw('tanggal_dikirim'),
                        'catatan' => $catatan
                    ]);
            } else {
                // Create new record
                DB::table('pengiriman')->insert([
                    'order_id' => $id,
                    'nomor_resi' => $request->nomor_resi,
                    'jasa_kurir' => $request->jasa_kurir,
                    'status_pengiriman' => $statusPengiriman,
                    'tanggal_dikirim' => $statusPengiriman == 'dikirim' ? now() : null,
                    'catatan' => $catatan,
                    'created_at' => now()
                ]);
            }

            // Update order status based on shipping status
            $orderStatusMapping = [
                'diproses' => 'paid',
                'dikirim' => 'shipped',
                'dalam_perjalanan' => 'shipped',
                'sampai' => 'completed',
                'gagal' => 'cancelled'
            ];

            $orderStatus = $orderStatusMapping[$statusPengiriman] ?? 'paid';
            DB::table('orders')->where('id', $id)->update(['status' => $orderStatus]);

            return response()->json([
                'status' => 'success',
                'message' => 'Informasi pengiriman diperbarui',
                'data' => [
                    'order_status' => $orderStatus,
                    'shipping_status' => $statusPengiriman
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel order
     */
    public function cancel(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'reason' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed'
                ], 400);
            }

            $reason = $request->reason ?? 'Payment timeout';

            // Check if order exists and can be cancelled
            $order = DB::table('orders')->where('id', $id)->first();
            
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => "Order ID $id not found in database"
                ], 404);
            }

            if ($order->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot cancel order. Current status: {$order->status}"
                ], 400);
            }

            DB::beginTransaction();

            // Update order status to cancelled
            DB::table('orders')
                ->where('id', $id)
                ->update(['status' => 'cancelled']);

            // Update payment status to failed
            DB::table('payments')
                ->where('order_id', $id)
                ->update(['status_pembayaran' => 'failed']);

            // Restore stock for order items
            $orderItems = DB::table('order_items')->where('order_id', $id)->get();

            foreach ($orderItems as $item) {
                // Get stock ID from product
                $product = DB::table('products')->where('id', $item->product_id)->first();
                
                if ($product && $product->stok_id) {
                    // Update stock quantity
                    DB::table('stocks')
                        ->where('id', $product->stok_id)
                        ->increment('quantity', $item->kuantitas);

                    // Check updated stock quantity
                    $updatedStock = DB::table('stocks')->where('id', $product->stok_id)->first();
                    
                    if ($updatedStock->quantity > 0) {
                        // Update product status to available if it was out_of_stock
                        DB::table('products')
                            ->where('id', $item->product_id)
                            ->where('status', 'out_of_stock')
                            ->update(['status' => 'available']);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order has been cancelled successfully',
                'data' => [
                    'order_id' => $id,
                    'reason' => $reason
                ]
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete order
     */
    public function complete(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'nullable|integer|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 400);
            }

            DB::beginTransaction();

            // Verify order belongs to user if user_id provided
            if ($request->user_id) {
                $order = DB::table('orders')
                    ->where('id', $id)
                    ->where('user_id', $request->user_id)
                    ->first();

                if (!$order) {
                    throw new Exception("Order not found or access denied");
                }

                if ($order->status === 'completed') {
                    throw new Exception("Order is already completed");
                }
            }

            // Update order status to completed
            $updated = DB::table('orders')
                ->where('id', $id)
                ->update(['status' => 'completed']);

            if ($updated == 0) {
                throw new Exception("No order found with the given ID");
            }

            // Update payment status to completed (if payment exists)
            DB::table('payments')
                ->where('order_id', $id)
                ->update(['status_pembayaran' => 'completed']);

            // Update shipping status to sampai (if shipping exists)
            DB::table('pengiriman')
                ->where('order_id', $id)
                ->update(['status_pengiriman' => 'sampai']);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Order completed successfully'
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate unique order ID
     */
    private function generateOrderID()
    {
        $datePrefix = date('Ymd');
        
        // Get the latest order ID for today
        $maxId = DB::table('orders')
            ->whereDate('waktu_order', today())
            ->max('id');
        
        $sequenceNumber = $maxId ? $maxId + 1 : 1;
        $formattedSequence = str_pad($sequenceNumber, 0, "0", STR_PAD_LEFT);
        
        return "{$formattedSequence}";
    }

    /**
     * Map status values for UI display
     */
    private function mapStatusForUI($dbStatus)
    {
        switch($dbStatus) {
            case 'pending':
                return 'Belum bayar';
            case 'paid':
                return 'Dibayar';
            case 'shipped':
                return 'Dikirim';
            case 'completed':
                return 'Selesai';
            case 'cancelled':
                return 'Batal';
            default:
                return 'Unknown';
        }
    }

    /**
     * Map shipping status values for UI display
     */
    private function mapShippingStatusForUI($dbStatus)
    {
        switch($dbStatus) {
            case 'diproses':
                return 'Diproses';
            case 'dikirim':
                return 'Dalam Pengiriman';
            case 'dalam_perjalanan':
                return 'Dalam Perjalanan';
            case 'sampai':
                return 'Sampai Tujuan';
            case 'gagal':
                return 'Gagal';
            default:
                return 'Unknown';
        }
    }
}