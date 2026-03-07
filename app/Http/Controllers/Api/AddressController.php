<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AddressController extends Controller
{
    /**
     * Get addresses by user ID
     */
    public function getAddresses(Request $request): JsonResponse
    {
        try {
            Log::info('GET addresses request', ['user_id' => $request->user_id]);

            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or missing user_id parameter',
                    'errors' => $validator->errors()
                ], 400);
            }

            $userId = $request->user_id;

            $addresses = Address::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($address) {
                    return [
                        'id' => $address->id,
                        'user_id' => $address->user_id,
                        'nama_lengkap' => $address->nama_lengkap,
                        'nomor_hp' => $address->nomor_hp,
                        'provinsi' => $address->provinsi,
                        'kota' => $address->kota,
                        'kecamatan' => $address->kecamatan,
                        'kode_pos' => $address->kode_pos,
                        'alamat_lengkap' => $address->alamat_lengkap,
                        'created_at' => $address->created_at->toISOString()
                    ];
                });

            if ($addresses->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No addresses found for this user',
                    'data' => []
                ], 200);
            }

            Log::info('Found addresses', ['count' => $addresses->count(), 'user_id' => $userId]);

            return response()->json([
                'success' => true,
                'message' => 'Addresses retrieved successfully',
                'data' => $addresses
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error getting addresses', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error processing request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create new address
     */
    public function createAddress(Request $request): JsonResponse
    {
        try {
            Log::info('POST create address request', $request->all());

            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|min:1',
                'nama_lengkap' => 'required|string|max:255',
                'nomor_hp' => 'required|string|max:20',
                'provinsi' => 'required|string|max:100',
                'kota' => 'required|string|max:100',
                'kecamatan' => 'required|string|max:100',
                'kode_pos' => 'required|string|max:10',
                'alamat_lengkap' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Field berikut tidak ditemukan atau kosong: ' . implode(', ', array_keys($validator->errors()->toArray())),
                    'errors' => $validator->errors()
                ], 400);
            }

            $address = Address::create([
                'user_id' => $request->user_id,
                'nama_lengkap' => $request->nama_lengkap,
                'nomor_hp' => $request->nomor_hp,
                'provinsi' => $request->provinsi,
                'kota' => $request->kota,
                'kecamatan' => $request->kecamatan,
                'kode_pos' => $request->kode_pos,
                'alamat_lengkap' => $request->alamat_lengkap
            ]);

            Log::info('Address created successfully', ['id' => $address->id]);

            return response()->json([
                'success' => true,
                'message' => 'Alamat berhasil disimpan',
                'id' => $address->id
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error creating address', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error processing request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update existing address
     */
    public function updateAddress(Request $request): JsonResponse
    {
        try {
            Log::info('POST update address request', $request->all());

            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|min:1',
                'user_id' => 'required|integer|min:1',
                'nama_lengkap' => 'required|string|max:255',
                'nomor_hp' => 'required|string|max:20',
                'provinsi' => 'required|string|max:100',
                'kota' => 'required|string|max:100',
                'kecamatan' => 'required|string|max:100',
                'kode_pos' => 'required|string|max:10',
                'alamat_lengkap' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'The following fields are missing or empty: ' . implode(', ', array_keys($validator->errors()->toArray())),
                    'errors' => $validator->errors()
                ], 400);
            }

            // Verify address belongs to user
            $address = Address::where('id', $request->id)
                ->where('user_id', $request->user_id)
                ->first();

            if (!$address) {
                return response()->json([
                    'success' => false,
                    'message' => 'Address not found or you don\'t have permission to edit it'
                ], 403);
            }

            // Update address
            $updated = $address->update([
                'nama_lengkap' => $request->nama_lengkap,
                'nomor_hp' => $request->nomor_hp,
                'provinsi' => $request->provinsi,
                'kota' => $request->kota,
                'kecamatan' => $request->kecamatan,
                'kode_pos' => $request->kode_pos,
                'alamat_lengkap' => $request->alamat_lengkap
            ]);

            if ($updated) {
                Log::info('Address updated successfully', ['id' => $request->id]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Alamat berhasil diperbarui'
                ], 200);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Tidak ada perubahan pada alamat'
                ], 200);
            }

        } catch (\Exception $e) {
            Log::error('Error updating address', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error processing request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete address
     */
    public function deleteAddress(Request $request): JsonResponse
    {
        try {
            Log::info('POST delete address request', $request->all());

            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|min:1',
                'user_id' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing required fields: id and user_id are required',
                    'errors' => $validator->errors()
                ], 400);
            }

            // Verify address belongs to user
            $address = Address::where('id', $request->id)
                ->where('user_id', $request->user_id)
                ->first();

            if (!$address) {
                return response()->json([
                    'success' => false,
                    'message' => 'Address not found or you don\'t have permission to delete it'
                ], 403);
            }

            // Delete address
            $deleted = $address->delete();

            if ($deleted) {
                Log::info('Address deleted successfully', ['id' => $request->id]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Alamat berhasil dihapus'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete address: No rows affected'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Error deleting address', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error processing request: ' . $e->getMessage()
            ], 500);
        }
    }
}