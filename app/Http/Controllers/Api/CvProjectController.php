<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CvProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CvProjectController extends Controller
{
    /**
     * Tampilkan semua CV projects milik user yang login.
     */
    public function index(Request $request): JsonResponse
    {
        $projects = $request->user()
            ->cvProjects()
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'data' => $projects,
        ]);
    }

    /**
     * Buat CV project baru.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'judul_cv'    => 'nullable|string|max:100',
        ]);

        $project = $request->user()->cvProjects()->create([
            'judul_cv'    => $validated['judul_cv'] ?? 'Untitled Resume',
        ]);

        return response()->json([
            'message' => 'CV project berhasil dibuat',
            'data'    => $project,
        ], 201);
    }

    /**
     * Tampilkan detail satu CV project beserta semua relasinya.
     */
    public function show(Request $request, CvProject $cvProject): JsonResponse
    {
        // Pastikan user hanya bisa lihat miliknya sendiri
        if ($cvProject->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $cvProject->load([
            'personalDetail',
            'employmentHistories',
            'educations',
            'skills',
            'organizations',
        ]);

        return response()->json([
            'data' => $cvProject,
        ]);
    }

    /**
     * Update CV project (judul / template).
     */
    public function update(Request $request, CvProject $cvProject): JsonResponse
    {
        if ($cvProject->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'judul_cv'    => 'nullable|string|max:100',
        ]);

        $cvProject->update($validated);

        return response()->json([
            'message' => 'CV project berhasil diupdate',
            'data'    => $cvProject,
        ]);
    }

    /**
     * Hapus CV project (cascade delete semua section).
     */
    public function destroy(Request $request, CvProject $cvProject): JsonResponse
    {
        if ($cvProject->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $cvProject->delete();

        return response()->json([
            'message' => 'CV project berhasil dihapus',
        ]);
    }

    /**
     * Increment download count.
     */
    public function incrementDownload(Request $request, CvProject $cvProject): JsonResponse
    {
        // Allowed even if not owner, or we can restrict it. Usually anyone can download their own.
        if ($cvProject->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $cvProject->increment('download_count');

        return response()->json([
            'message' => 'Download count incremented',
            'download_count' => $cvProject->download_count,
        ]);
    }
}
