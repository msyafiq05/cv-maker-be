<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Redirect ke Google (Baru)
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Callback dari Google (Baru)
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cari user berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Jika user sudah ada (baik terdaftar manual atau sebelumnya lewat Google)
                // Cukup hubungkan / update google_id jika belum terisi
                if (empty($user->google_id)) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                    ]);
                }
            } else {
                // Jika user belum ada di database, buat baru
                // Kita gunakan Str::random(16) langsung karena model User memiliki cast 'password' => 'hashed'
                $user = User::create([
                    'email'     => $googleUser->getEmail(),
                    'nama'      => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'username'  => explode('@', $googleUser->getEmail())[0] . Str::random(4),
                    'password'  => Str::random(16), 
                    'role'      => 'user',
                ]);
            }

            // Buat token Sanctum
            $token = $user->createToken('auth_token')->plainTextToken;

            // Lempar ke React (Ganti URL jika React kamu bukan di localhost:5173)
            return redirect("http://localhost:5173/login?token={$token}");

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal login via Google',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Register user baru (Manual).
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'email'    => 'required|email|max:100|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'nama'     => $validated['nama'],
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'password' => $validated['password'], 
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }

    /**
     * Login user (Manual).
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($validated)) {
            return response()->json([
                'message' => 'Email atau password salah',
            ], 401);
        }

        /** @var \App\Models\User $user */
        $user  = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'user'    => $user,
            'token'   => $token,
        ]);
    }

    /**
     * Logout user.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil',
        ]);
    }

    /**
     * Get data user.
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }
}