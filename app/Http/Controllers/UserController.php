<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = User::query();
            
            // Filter berdasarkan role
            if ($request->filled('role') && $request->role !== 'Semua') {
                $query->where('role', $request->role);
            }
            
            // Search berdasarkan nama atau email
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%')
                      ->orWhere('no_telepon', 'like', '%' . $search . '%');
                });
            }
            
            $users = $query->orderBy('id_user', 'desc')->paginate(10);
            
            return view('dashboard.user.index', compact('users'));
            
        } catch (\Exception $e) {
            Log::error('Error loading user index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat data user: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'nama' => 'required|string|max:100',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'no_telepon' => 'nullable|string|max:15',
                'alamat' => 'nullable|string|max:500',
                'role' => 'required|string|in:admin,penyewa',
            ]);
            
            // Hash password
            $validated['password'] = Hash::make($validated['password']);
            
            // Simpan ke tabel users
            $user = User::create($validated);
            
            Log::info('User created successfully', ['id' => $user->id_user]);
            
            return redirect()->route('user.index')
                ->with('success', 'User berhasil ditambahkan!');
                
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Error storing user: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal menambahkan user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id_user)
    {
        try {
            $user = User::findOrFail($id_user);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id_user,
                    'nama' => $user->nama,
                    'email' => $user->email,
                    'no_telepon' => $user->no_telepon,
                    'alamat' => $user->alamat,
                    'role' => $user->role,
                    'role_display' => ucfirst($user->role),
                    'created_at' => $user->created_at->format('d M, Y'),
                ],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error showing user: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_user)
    {
        try {
            $user = User::findOrFail($id_user);
            
            // Validasi input
            $validated = $request->validate([
                'nama' => 'required|string|max:100',
                'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($id_user, 'id_user')],
                'no_telepon' => 'nullable|string|max:15',
                'alamat' => 'nullable|string|max:500',
                'role' => 'required|string|in:admin,penyewa',
                'password' => 'nullable|string|min:6',
            ]);
            
            // Hash password jika diubah
            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }
            
            // Update user
            $user->update($validated);
            
            Log::info('User updated successfully', ['id' => $user->id_user]);
            
            return redirect()->route('user.index')
                ->with('success', 'User berhasil diperbarui!');
                
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal memperbarui user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_user)
    {
        try {
            $user = User::findOrFail($id_user);
            
            // Cegah penghapusan user sendiri
           if ($user->id_user === auth()->user()->id_user) {
                return redirect()->back()
                    ->with('error', 'Anda tidak dapat menghapus user sendiri!');
            }
            
            // Hapus user
            $user->delete();
            
            Log::info('User deleted successfully', ['id' => $id_user]);
            
            return redirect()->route('user.index')
                ->with('success', 'User berhasil dihapus!');
                
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }

    /**
     * Get role badge
     */
    private function getRoleBadge($role)
    {
        $badges = [
            'admin' => ['bg' => '#e0e7ff', 'color' => '#3730a3', 'text' => 'Admin'],
            'penyewa' => ['bg' => '#fef3c7', 'color' => '#d97706', 'text' => 'Penyewa'],
        ];
        
        return $badges[$role] ?? ['bg' => '#f3f4f6', 'color' => '#6b7280', 'text' => ucfirst($role)];
    }
}
