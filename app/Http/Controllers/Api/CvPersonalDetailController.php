<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CvProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CvPersonalDetailController extends Controller
{
    // Tampilkan personal detail dari CV project.
    public function show(Request $request, CvProject $cvProject): JsonResponse
    {
        if ($cvProject->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json([
            'data' => $cvProject->personalDetail,
        ]);
    }

    // Simpan atau update personal detail.
    public function upsert(Request $request, CvProject $cvProject): JsonResponse
    {
        if ($cvProject->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'full_name'         => 'nullable|string|max:150',
            'phone_number'      => 'nullable|string|max:20',
            'email_address'     => 'nullable|email|max:100',
            'place_of_birth'    => 'nullable|string|max:100',
            'date_of_birth'     => 'nullable|string|max:50',
            'address'           => 'nullable|string',
            'website_url'       => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'foto_profil'       => 'nullable|string',
        ]);

        $detail = $cvProject->personalDetail()->updateOrCreate(
            ['cv_project_id' => $cvProject->id],
            $validated
        );

        return response()->json([
            'message' => 'Personal detail berhasil disimpan',
            'data'    => $detail,
        ]);
    }
}
