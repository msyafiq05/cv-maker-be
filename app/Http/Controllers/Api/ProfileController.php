<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    // Tampilkan data profil user yang sedang login.
    public function show(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $request->user(),
        ]);
    }

    // Update profil user yang sedang login.
    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'nama'         => 'sometimes|string|max:100',
            'username'     => 'sometimes|string|max:50|unique:users,username,' . $user->id,
            'email'        => 'sometimes|email|max:100|unique:users,email,' . $user->id,
            'phone'        => 'nullable|string|max:20',
            'country'      => 'nullable|string|max:60',
            'skills'       => 'nullable|string|max:1000',
            'about'        => 'nullable|string|max:2000',
            'social_media' => 'nullable|string|max:255',
            'avatar'       => 'nullable|string',
            'password'     => ['nullable', 'confirmed', Password::min(8)],
        ]);

        // Jika password kosong, jangan update password
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'data'    => $user->fresh(),
        ]);
    }
}
