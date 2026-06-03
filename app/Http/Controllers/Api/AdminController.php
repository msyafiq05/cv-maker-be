<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CvProject;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function dashboardStats()
    {
        $totalUsers = User::where('role', 'user')->count();
        $newUsersSinceLastWeek = User::where('role', 'user')
            ->where('created_at', '>=', now()->subWeek())
            ->count();

        $totalDownloads = CvProject::sum('download_count');

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_users' => $totalUsers,
                'new_users_since_last_week' => $newUsersSinceLastWeek,
                'active_templates' => 1, // We only have 1 active template (Minimalist Black)
                'total_downloads' => $totalDownloads,
            ]
        ]);
    }

    /**
     * Get user list with optional search
     */
    public function getUsers(Request $request)
    {
        $query = User::where('role', 'user')->withCount('cvProjects');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->get()->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->nama,
                'email' => $user->email,
                'cv_count' => $user->cv_projects_count,
                'last_access' => $user->updated_at ? $user->updated_at->diffForHumans() : null,
                'avatar' => $user->avatar,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    /**
     * Delete a user
     */
    public function deleteUser($id)
    {
        $user = User::where('role', 'user')->find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        // The user's cv projects will be deleted automatically due to cascade on delete in DB
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully'
        ]);
    }
}
