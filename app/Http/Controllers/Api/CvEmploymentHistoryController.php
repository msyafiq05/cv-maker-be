<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CvEmploymentHistory;
use App\Models\CvProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CvEmploymentHistoryController extends Controller
{
    /**
     * Tampilkan semua employment history dari CV project.
     */
    public function index(Request $request, CvProject $cvProject): JsonResponse
    {
        if ($cvProject->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json([
            'data' => $cvProject->employmentHistories,
        ]);
    }

    /**
     * Tambah employment history baru.
     */
    public function store(Request $request, CvProject $cvProject): JsonResponse
    {
        if ($cvProject->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'company_name'        => 'required|string|max:150',
            'job_title'           => 'required|string|max:150',
            'start_year'          => 'nullable|string|max:20',
            'end_year'            => 'nullable|string|max:20',
            'company_location'    => 'nullable|string|max:150',
            'company_description' => 'nullable|string',
        ]);

        $employment = $cvProject->employmentHistories()->create($validated);

        return response()->json([
            'message' => 'Employment history berhasil ditambahkan',
            'data'    => $employment,
        ], 201);
    }

    /**
     * Update employment history.
     */
    public function update(Request $request, CvProject $cvProject, CvEmploymentHistory $employment): JsonResponse
    {
        if ($cvProject->user_id !== $request->user()->id || $employment->cv_project_id !== $cvProject->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'company_name'        => 'sometimes|required|string|max:150',
            'job_title'           => 'sometimes|required|string|max:150',
            'start_year'          => 'nullable|string|max:20',
            'end_year'            => 'nullable|string|max:20',
            'company_location'    => 'nullable|string|max:150',
            'company_description' => 'nullable|string',
        ]);

        $employment->update($validated);

        return response()->json([
            'message' => 'Employment history berhasil diupdate',
            'data'    => $employment,
        ]);
    }

    /**
     * Hapus employment history.
     */
    public function destroy(Request $request, CvProject $cvProject, CvEmploymentHistory $employment): JsonResponse
    {
        if ($cvProject->user_id !== $request->user()->id || $employment->cv_project_id !== $cvProject->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $employment->delete();

        return response()->json([
            'message' => 'Employment history berhasil dihapus',
        ]);
    }
}
