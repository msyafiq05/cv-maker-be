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
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordOtpMail;

class AuthController extends Controller
{
    /**
     * Redirect ke Google
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Handle Callback dari Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Cari user berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Jika user sudah ada, hubungkan google_id jika belum terisi
                if (empty($user->google_id)) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                    ]);
                }
            } else {
                // Jika user belum ada di database, buat baru
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

            // Redirect ke React frontend
            return redirect(env('FRONTEND_URL', 'http://localhost:5173') . "/login?token={$token}");

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

    /**
     * Forgot Password (Kirim OTP).
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        
        $user = User::where('email', $request->email)->first();
        
        // Generate 6 digit OTP
        $otp = sprintf("%06d", mt_rand(1, 999999));
        
        $user->update(['reset_token' => $otp]);
        
        // Kirim email ke user
        try {
            Mail::to($user->email)->send(new ResetPasswordOtpMail($otp));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Gagal mengirim email OTP: " . $e->getMessage());
            return response()->json([
                'message' => 'Gagal mengirim email. Silakan coba lagi.'
            ], 500);
        }
        
        // Untuk tahap trial & error, log OTP-nya agar bisa dilihat dari terminal
        \Illuminate\Support\Facades\Log::info("OTP Reset Password untuk {$user->email} adalah: {$otp}");
        
        return response()->json([
            'message' => 'OTP telah dikirim ke email.',
            'dev_otp' => $otp // Mempermudah trial & error dari frontend (Network tab)
        ]);
    }

    /**
     * Reset Password.
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'reset_token' => 'required|string|size:6',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::where('reset_token', $validated['reset_token'])->first();

        if (!$user) {
            return response()->json([
                'message' => 'Token OTP tidak valid atau salah.'
            ], 400);
        }

        // Update password dan hapus token
        $user->update([
            'password' => $validated['password'],
            'reset_token' => null
        ]);

        return response()->json([
            'message' => 'Password berhasil direset.'
        ]);
    }
}