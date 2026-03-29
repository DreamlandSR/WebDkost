<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ProductImages;

class ApiProductImageController extends Controller
{
    public function getImageBase64($id)
    {
        try {
            // Validasi ID
            if (!$id || !is_numeric($id)) {
                return response()->json([
                    'error' => 'Invalid image ID',
                    'image_id' => $id
                ], 400);
            }

            // Ambil data gambar dari database
            $imageRow = DB::table('product_images')
                ->select('image_product')
                ->where('id', $id)
                ->first();

            if (!$imageRow || !$imageRow->image_product) {
                return response()->json([
                    'error' => 'Image not found',
                    'image_id' => $id
                ], 404);
            }

            $imageData = $imageRow->image_product;

            // Deteksi mime-type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_buffer($finfo, $imageData);
            finfo_close($finfo);

            // Default ke JPEG jika tidak terdeteksi
            if (!$mimeType || $mimeType === 'application/octet-stream') {
                $mimeType = 'image/jpeg';
            }

            // Encode ke base64
            $base64 = base64_encode($imageData);
            $base64Image = "data:$mimeType;base64,$base64";

            return response()->json([
                'id' => $id,
                'image_base64' => $base64Image,
                'mime_type' => $mimeType,
                'success' => true
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error fetching image: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Database error',
                'message' => $e->getMessage(),
                'image_id' => $id
            ], 500);
        }
    }
}