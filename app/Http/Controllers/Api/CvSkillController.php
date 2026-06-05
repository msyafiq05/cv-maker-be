<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CvProject;
use App\Models\CvSkill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CvSkillController extends Controller
{
    // Tampilkan semua skills dari CV project.
    public function index(Request $request, CvProject $cvProject): JsonResponse
    {
        if ($cvProject->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json([
            'data' => $cvProject->skills,
        ]);
    }

    // Tambah skill baru.
    public function store(Request $request, CvProject $cvProject): JsonResponse
    {
        if ($cvProject->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'activity_name' => 'nullable|string|max:150',
            'year'          => 'nullable|string|max:20',
            'elaboration'   => 'nullable|string',
        ]);

        $skill = $cvProject->skills()->create($validated);

        return response()->json([
            'message' => 'Skill berhasil ditambahkan',
            'data'    => $skill,
        ], 201);
    }

    // Update skill.
    public function update(Request $request, CvProject $cvProject, CvSkill $skill): JsonResponse
    {
        if ($cvProject->user_id !== $request->user()->id || $skill->cv_project_id !== $cvProject->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'activity_name' => 'nullable|string|max:150',
            'year'          => 'nullable|string|max:20',
            'elaboration'   => 'nullable|string',
        ]);

        $skill->update($validated);

        return response()->json([
            'message' => 'Skill berhasil diupdate',
            'data'    => $skill,
        ]);
    }

    // Hapus skill.
    public function destroy(Request $request, CvProject $cvProject, CvSkill $skill): JsonResponse
    {
        if ($cvProject->user_id !== $request->user()->id || $skill->cv_project_id !== $cvProject->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $skill->delete();

        return response()->json([
            'message' => 'Skill berhasil dihapus',
        ]);
    }
}
