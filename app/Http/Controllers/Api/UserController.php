<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json([
            'code' => 200,
            'message' => 'Daftar User Berhasil Diambil',
            'data' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // Email harus unik
            'password' => 'required|string|min:8', // Tambahkan validasi minimum panjang password
            'role' => 'required|string', // Tambahkan validasi minimum panjang password
            
        ]);

        $user = User::create([
            'username' => $request->username,
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Gunakan Hash::make
            'role' =>  $request->role,
        ]);

        return response()->json([
            'code' => 201,
            'message' => 'User Berhasil Dibuat',
            'data' => $user,
        ], 201);
    }

    // Ambil laporan berdasarkan ID
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        } else {
            return response()->json([
                'code' => 200,
                'message' => 'User Berhasil Diambil',
                'data' => $user
            ]);
        }
    }

    // Update laporan
    public function update(Request $request, $id)
    {
        // $request->headers->set('Accept', 'application/json'); // Set header untuk JSON response

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        // Validasi data yang diterima
        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id, // Unik kecuali untuk user ini
            'password' => 'nullable|string|min:8', // Password opsional, minimal panjang 8
            'role' => 'nullable|string|in:teacher,user', // Validasi role (opsional)
        ]);

        $user->username = $validatedData['username'];
        $user->fullname = $validatedData['fullname'];
        

        if (!empty($validatedData['email'])) {
            $user->email = $validatedData['email'];
        }

        // Perbarui password jika ada
        if (!empty($validatedData['password'])) {
            $user->password = bcrypt($validatedData['password']);
        }

        // Perbarui role jika ada
        if (!empty($validatedData['role'])) {
            $user->role = $validatedData['role'];
        }

        $user->save();

        return response()->json([
            'code' => 200,
            'message' => 'User updated successfully',
            'data' => $user,
        ]);
    }





    // Hapus laporan
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();
        return response()->json([
            'code' => 200,
            'message' => 'User Berhasil Dihapus',
        ]);
    }
}