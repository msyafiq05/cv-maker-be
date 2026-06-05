<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CvOrganization;
use App\Models\CvProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CvOrganizationController extends Controller
{
    // Tampilkan semua organizations dari CV project.
    public function index(Request $request, CvProject $cvProject): JsonResponse
    {
        if ($cvProject->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json([
            'data' => $cvProject->organizations,
        ]);
    }

    // Tambah organization baru.
    public function store(Request $request, CvProject $cvProject): JsonResponse
    {
        if ($cvProject->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'organization_name' => 'nullable|string|max:150',
            'role'              => 'nullable|string|max:100',
            'start_year'        => 'nullable|string|max:20',
            'end_year'          => 'nullable|string|max:20',
            'location'          => 'nullable|string|max:150',
            'description'       => 'nullable|string',
        ]);

        $organization = $cvProject->organizations()->create($validated);

        return response()->json([
            'message' => 'Organization berhasil ditambahkan',
            'data'    => $organization,
        ], 201);
    }

    // Update organization.
    public function update(Request $request, CvProject $cvProject, CvOrganization $organization): JsonResponse
    {
        if ($cvProject->user_id !== $request->user()->id || $organization->cv_project_id !== $cvProject->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'organization_name' => 'nullable|string|max:150',
            'role'              => 'nullable|string|max:100',
            'start_year'        => 'nullable|string|max:20',
            'end_year'          => 'nullable|string|max:20',
            'location'          => 'nullable|string|max:150',
            'description'       => 'nullable|string',
        ]);

        $organization->update($validated);

        return response()->json([
            'message' => 'Organization berhasil diupdate',
            'data'    => $organization,
        ]);
    }

    // Hapus organization.
    public function destroy(Request $request, CvProject $cvProject, CvOrganization $organization): JsonResponse
    {
        if ($cvProject->user_id !== $request->user()->id || $organization->cv_project_id !== $cvProject->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $organization->delete();

        return response()->json([
            'message' => 'Organization berhasil dihapus',
        ]);
    }
}
