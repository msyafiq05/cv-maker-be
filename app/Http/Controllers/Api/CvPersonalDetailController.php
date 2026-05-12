<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CvProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CvPersonalDetailController extends Controller
{
    /**
     * Tampilkan / buat personal detail dari CV project.
     */
    public function show(Request $request, CvProject $cvProject): JsonResponse
    {
        if ($cvProject->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json([
            'data' => $cvProject->personalDetail,
        ]);
    }

    /**
     * Simpan atau update personal detail (upsert).
     */
    public function upsert(Request $request, CvProject $cvProject): JsonResponse
    {
        if ($cvProject->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'full_name'         => 'required|string|max:150',
            'phone_number'      => 'required|string|max:20',
            'email_address'     => 'required|email|max:100',
            'place_of_birth'    => 'required|string|max:100',
            'date_of_birth'     => 'required|string|max:50',
            'address'           => 'nullable|string',
            'website_url'       => 'nullable|url|max:255',
            'short_description' => 'nullable|string|max:150',
            'foto_profil'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $dataToSave = $validated;

        // Cek apakah ada upload file foto_profil
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            $existingDetail = $cvProject->personalDetail;
            if ($existingDetail && $existingDetail->foto_profil) {
                Storage::disk('public')->delete($existingDetail->foto_profil);
            }

            // Simpan foto baru
            $path = $request->file('foto_profil')->store('profiles', 'public');
            $dataToSave['foto_profil'] = $path;
        } else {
            // Jika tidak ada upload, jangan override foto yang sudah ada dengan null
            unset($dataToSave['foto_profil']);
        }

        $detail = $cvProject->personalDetail()->updateOrCreate(
            ['cv_project_id' => $cvProject->id],
            $dataToSave
        );

        return response()->json([
            'message' => 'Personal detail berhasil disimpan',
            'data'    => $detail,
        ]);
    }
}
