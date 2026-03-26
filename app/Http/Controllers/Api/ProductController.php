<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Get all products with stock and rating
     */
    public function index()
    {
        try {
            // Ambil semua produk dengan stok
            $products = DB::table('products as p')
                ->leftJoin('stocks as s', 'p.stok_id', '=', 's.id')
                ->select('p.*', 's.quantity')
                ->where('p.status', 'available')
                ->orderBy('p.created_at', 'desc')
                ->get();

            if ($products->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

            // Ambil gambar utama untuk setiap produk (is_main = 1)
            $mainImages = DB::table('product_images')
                ->select('id', 'product_id', 'is_main', DB::raw('TO_BASE64(image_product) as image_product'))
                ->where('is_main', 1)
                ->get();

            // Buat mapping gambar utama berdasarkan product_id
            $mainImageMap = [];
            foreach ($mainImages as $img) {
                $img->image_product = str_replace(["\r", "\n"], '', $img->image_product);
                $mainImageMap[$img->product_id] = $img;
            }

            // Ambil semua gambar produk untuk fallback
            $allImages = DB::table('product_images')
                ->select('id', 'product_id', 'is_main', DB::raw('TO_BASE64(image_product) as image_product'))
                ->orderBy('is_main', 'desc')
                ->orderBy('id', 'asc')
                ->get();

            // Kelompokkan semua gambar berdasarkan product_id
            $imageMap = [];
            foreach ($allImages as $img) {
                $img->image_product = str_replace(["\r", "\n"], '', $img->image_product);
                $imageMap[$img->product_id][] = $img;
            }

            // Ambil rata-rata rating produk
            $ratings = DB::table('reviews')
                ->select('product_id', DB::raw('ROUND(AVG(rating), 1) as avg_rating'))
                ->groupBy('product_id')
                ->get();

            $ratingMap = [];
            foreach ($ratings as $rating) {
                $ratingMap[$rating->product_id] = (float) $rating->avg_rating;
            }

            // Tambahkan gambar dan rating ke setiap produk
            foreach ($products as $product) {
                // Prioritas: gambar utama (is_main = 1), jika tidak ada ambil gambar pertama
                $product->main_image = $mainImageMap[$product->id] ?? (isset($imageMap[$product->id]) ? $imageMap[$product->id][0] : null);
                $product->images = $imageMap[$product->id] ?? [];
                $product->rating = $ratingMap[$product->id] ?? 0.0;
                
                // Tambahkan URL endpoint untuk thumbnail
                if ($product->main_image) {
                    $product->thumbnail_url = url('/api/images/' . $product->main_image->id);
                    $product->main_image_base64 = 'data:image/jpeg;base64,' . $product->main_image->image_product;
                } else {
                    $product->thumbnail_url = null;
                    $product->main_image_base64 = null;
                }
            }

            return response()->json([
                'success' => true,
                'data' => $products
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving products: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product detail by ID
     */
    public function show($id)
    {
        try {
            // Validasi ID
            if (!is_numeric($id)) {
                return response()->json([
                    'message' => 'Invalid product ID'
                ], 400);
            }

            // Ambil detail produk
            $product = DB::table('products as p')
                ->leftJoin('stocks as s', 'p.stok_id', '=', 's.id')
                ->select(
                    'p.id',
                    'p.nama',
                    'p.deskripsi', 
                    'p.harga',
                    'p.stok_id',
                    'p.status',
                    'p.rating',
                    'p.berat',
                    'p.created_at',
                    's.quantity'
                )
                ->where('p.id', $id)
                ->first();

            if (!$product) {
                return response()->json([
                    'message' => 'Produk tidak ditemukan'
                ], 404);
            }

            // Convert types
            $product->rating = (float) $product->rating;
            $product->berat = (int) $product->berat;

            // Ambil gambar produk dengan prioritas gambar utama
            $images = DB::table('product_images')
                ->select(
                    'id',
                    'product_id',
                    'is_main',
                    DB::raw('CONCAT("data:image/jpeg;base64,", TO_BASE64(image_product)) as image_base64')
                )
                ->where('product_id', $id)
                ->orderBy('is_main', 'desc')
                ->orderBy('id', 'asc')
                ->get();

            $product->images = $images;

            // Tambahkan gambar utama terpisah untuk kemudahan akses
            $mainImage = $images->where('is_main', 1)->first();
            $product->main_image = $mainImage ?? $images->first();

            return response()->json($product);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product image by image ID
     */
    public function getImage(Request $request, $imageId)
    {
        try {
            if (!is_numeric($imageId)) {
                return response()->json(['error' => 'Invalid image ID'], 400);
            }

            // Ambil gambar
            $image = DB::table('product_images')
                ->select('image_product')
                ->where('id', $imageId)
                ->first();

            if (!$image || !$image->image_product) {
                return response()->json([
                    'error' => 'Image not found',
                    'image_id' => $imageId
                ], 404);
            }

            $imageData = $image->image_product;

            // Deteksi MIME type
            $imageInfo = getimagesizefromstring($imageData);
            if ($imageInfo !== false) {
                $mimeType = $imageInfo['mime'];
            } else {
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->buffer($imageData);
                if (!$mimeType || $mimeType === 'application/octet-stream') {
                    $mimeType = 'image/jpeg';
                }
            }

            // Optimasi ukuran gambar jika parameter width disediakan
            if ($request->has('width') && function_exists('imagecreatefromstring')) {
                $maxWidth = (int) $request->get('width');
                if ($maxWidth > 0 && $maxWidth <= 1200) {
                    $sourceImage = imagecreatefromstring($imageData);
                    if ($sourceImage !== false) {
                        $origWidth = imagesx($sourceImage);
                        $origHeight = imagesy($sourceImage);
                        
                        if ($origWidth > $maxWidth) {
                            $ratio = $maxWidth / $origWidth;
                            $newHeight = $origHeight * $ratio;
                            
                            $resizedImage = imagecreatetruecolor($maxWidth, $newHeight);
                            imagecopyresampled(
                                $resizedImage, $sourceImage, 
                                0, 0, 0, 0, 
                                $maxWidth, $newHeight, $origWidth, $origHeight
                            );
                            
                            // Return optimized image
                            ob_start();
                            if ($mimeType == 'image/jpeg') {
                                imagejpeg($resizedImage, null, 85);
                            } elseif ($mimeType == 'image/png') {
                                imagepng($resizedImage, null, 6);
                            }
                            $optimizedImageData = ob_get_contents();
                            ob_end_clean();
                            
                            imagedestroy($sourceImage);
                            imagedestroy($resizedImage);
                            
                            return response($optimizedImageData)
                                ->header('Content-Type', $mimeType)
                                ->header('Cache-Control', 'public, max-age=86400')
                                ->header('Expires', gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT')
                                ->header('Pragma', 'public');
                        }
                        imagedestroy($sourceImage);
                    }
                }
            }

            // Return original image
            return response($imageData)
                ->header('Content-Type', $mimeType)
                ->header('Content-Length', strlen($imageData))
                ->header('Cache-Control', 'public, max-age=86400')
                ->header('Expires', gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT')
                ->header('Pragma', 'public');

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Database error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get main product image by product ID
     */
    public function getMainImageByProduct($productId)
    {
        try {
            if (!is_numeric($productId)) {
                return response()->json(['error' => 'Invalid product ID'], 400);
            }

            // Cari gambar utama terlebih dahulu
            $image = DB::table('product_images')
                ->select('image_product')
                ->where('product_id', $productId)
                ->where('is_main', 1)
                ->first();

            // Jika tidak ada gambar utama, ambil gambar pertama
            if (!$image) {
                $image = DB::table('product_images')
                    ->select('image_product')
                    ->where('product_id', $productId)
                    ->orderBy('id', 'asc')
                    ->first();
            }

            if (!$image || !$image->image_product) {
                return response()->json([
                    'error' => 'Image not found for product',
                    'product_id' => $productId
                ], 404);
            }

            $imageData = $image->image_product;

            // Deteksi MIME type
            $imageInfo = getimagesizefromstring($imageData);
            if ($imageInfo !== false) {
                $mimeType = $imageInfo['mime'];
            } else {
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->buffer($imageData);
                if (!$mimeType || $mimeType === 'application/octet-stream') {
                    $mimeType = 'image/jpeg';
                }
            }

            return response($imageData)
                ->header('Content-Type', $mimeType)
                ->header('Content-Length', strlen($imageData))
                ->header('Cache-Control', 'public, max-age=3600')
                ->header('Pragma', 'public');

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Database error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get main product image by image ID
     */
    public function getMainImage($imageId)
    {
        try {
            if (!is_numeric($imageId)) {
                return response()->json(['error' => 'Invalid image ID'], 400);
            }

            $image = DB::table('product_images')
                ->select('image_product')
                ->where('id', $imageId)
                ->first();

            if (!$image || !$image->image_product) {
                return response()->json([
                    'error' => 'Image not found',
                    'image_id' => $imageId
                ], 404);
            }

            $imageData = $image->image_product;

            // Deteksi MIME type
            $imageInfo = getimagesizefromstring($imageData);
            if ($imageInfo !== false) {
                $mimeType = $imageInfo['mime'];
            } else {
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->buffer($imageData);
                if (!$mimeType || $mimeType === 'application/octet-stream') {
                    $mimeType = 'image/jpeg';
                }
            }

            return response($imageData)
                ->header('Content-Type', $mimeType)
                ->header('Content-Length', strlen($imageData))
                ->header('Cache-Control', 'public, max-age=3600')
                ->header('Pragma', 'public');

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Database error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product images by product ID
     */
    public function getProductImages($productId)
    {
        try {
            if (!is_numeric($productId)) {
                return response()->json(['error' => 'Invalid product ID'], 400);
            }

            $images = DB::table('product_images')
                ->select('id', 'product_id', 'is_main', DB::raw('TO_BASE64(image_product) as image_product'))
                ->where('product_id', $productId)
                ->orderBy('is_main', 'desc')
                ->orderBy('id', 'asc')
                ->get();

            // Clean base64 strings
            foreach ($images as $img) {
                $img->image_product = str_replace(["\r", "\n"], '', $img->image_product);
                $img->image_url = url('/api/images/' . $img->id);
                $img->image_base64 = 'data:image/jpeg;base64,' . $img->image_product;
            }

            return response()->json([
                'success' => true,
                'data' => $images
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Database error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}