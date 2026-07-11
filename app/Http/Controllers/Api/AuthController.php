<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * POST /api/login
     * Body: email, password
     *
     * Login khusus role "driver". Kalau berhasil, mengembalikan
     * token Sanctum yang harus disimpan di aplikasi Android dan
     * dikirim di header Authorization setiap request berikutnya:
     * Authorization: Bearer {token}
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        // Pastikan hanya driver yang boleh login lewat aplikasi ini
        if ($user->role !== 'driver') {
            throw ValidationException::withMessages([
                'email' => ['Akun ini bukan akun driver.'],
            ]);
        }

        // Hapus token lama supaya tidak menumpuk tiap kali login
        $user->tokens()->delete();

        $token = $user->createToken('driver-app')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token'   => $token,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role'  => $user->role,
            ],
        ]);
    }

    /**
     * POST /api/logout
     * Header: Authorization: Bearer {token}
     *
     * Menghapus token yang sedang dipakai (logout dari device ini saja).
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil',
        ]);
    }

    /**
     * GET /api/profile
     * Header: Authorization: Bearer {token}
     *
     * Mengembalikan data profil driver yang sedang login.
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role'  => $user->role,
        ]);
    }
}