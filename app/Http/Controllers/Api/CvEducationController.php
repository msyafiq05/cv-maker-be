<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CvEducation;
use App\Models\CvProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CvEducationController extends Controller
{
    /**
     * Tampilkan semua education dari CV project.
     */
    public function index(Request $request, CvProject $cvProject): JsonResponse
    {
        if ($cvProject->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json([
            'data' => $cvProject->educations,
        ]);
    }

    /**
     * Tambah education baru.
     */
    public function store(Request $request, CvProject $cvProject): JsonResponse
    {
        if ($cvProject->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'institution_name' => 'nullable|string|max:150',
            'degree'           => 'nullable|string|max:100',
            'field_of_study'   => 'nullable|string|max:150',
            'start_year'       => 'nullable|string|max:20',
            'end_year'         => 'nullable|string|max:20',
            'gpa'              => 'nullable|string|max:10',
            'location'         => 'nullable|string|max:150',
            'description'      => 'nullable|string',
        ]);

        $education = $cvProject->educations()->create($validated);

        return response()->json([
            'message' => 'Education berhasil ditambahkan',
            'data'    => $education,
        ], 201);
    }

    /**
     * Update education.
     */
    public function update(Request $request, CvProject $cvProject, CvEducation $education): JsonResponse
    {
        if ($cvProject->user_id !== $request->user()->id || $education->cv_project_id !== $cvProject->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'institution_name' => 'nullable|string|max:150',
            'degree'           => 'nullable|string|max:100',
            'field_of_study'   => 'nullable|string|max:150',
            'start_year'       => 'nullable|string|max:20',
            'end_year'         => 'nullable|string|max:20',
            'gpa'              => 'nullable|string|max:10',
            'location'         => 'nullable|string|max:150',
            'description'      => 'nullable|string',
        ]);

        $education->update($validated);

        return response()->json([
            'message' => 'Education berhasil diupdate',
            'data'    => $education,
        ]);
    }

    /**
     * Hapus education.
     */
    public function destroy(Request $request, CvProject $cvProject, CvEducation $education): JsonResponse
    {
        if ($cvProject->user_id !== $request->user()->id || $education->cv_project_id !== $cvProject->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $education->delete();

        return response()->json([
            'message' => 'Education berhasil dihapus',
        ]);
    }
}
