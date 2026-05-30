<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CvEducationController;
use App\Http\Controllers\Api\CvEmploymentHistoryController;
use App\Http\Controllers\Api\CvOrganizationController;
use App\Http\Controllers\Api\CvPersonalDetailController;
use App\Http\Controllers\Api\CvProjectController;
use App\Http\Controllers\Api\CvSkillController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TemplateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Route untuk CV Maker API.
| Prefix: /api (otomatis dari Laravel)
|
*/

// ===== AUTH (Public) =====
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Tambahkan ini untuk Google Auth
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// ===== TEMPLATES (Public) =====
Route::get('/templates', [TemplateController::class, 'index']);
Route::get('/templates/{template}', [TemplateController::class, 'show']);

// ===== PROTECTED ROUTES (Butuh Login) =====
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);

    // CV Projects (CRUD)
    Route::apiResource('cv-projects', CvProjectController::class)
        ->parameters(['cv-projects' => 'cvProject']);

    // CV Sections (nested di bawah cv-projects)
    Route::prefix('cv-projects/{cvProject}')->group(function () {

        // Personal Detail (1-to-1, pakai upsert)
        Route::get('/personal-detail', [CvPersonalDetailController::class, 'show']);
        Route::post('/personal-detail', [CvPersonalDetailController::class, 'upsert']);

        // Employment History (1-to-many)
        Route::get('/employments', [CvEmploymentHistoryController::class, 'index']);
        Route::post('/employments', [CvEmploymentHistoryController::class, 'store']);
        Route::put('/employments/{employment}', [CvEmploymentHistoryController::class, 'update']);
        Route::delete('/employments/{employment}', [CvEmploymentHistoryController::class, 'destroy']);

        // Education (1-to-many)
        Route::get('/educations', [CvEducationController::class, 'index']);
        Route::post('/educations', [CvEducationController::class, 'store']);
        Route::put('/educations/{education}', [CvEducationController::class, 'update']);
        Route::delete('/educations/{education}', [CvEducationController::class, 'destroy']);

        // Skills (1-to-many)
        Route::get('/skills', [CvSkillController::class, 'index']);
        Route::post('/skills', [CvSkillController::class, 'store']);
        Route::put('/skills/{skill}', [CvSkillController::class, 'update']);
        Route::delete('/skills/{skill}', [CvSkillController::class, 'destroy']);

        // Organizations (1-to-many)
        Route::get('/organizations', [CvOrganizationController::class, 'index']);
        Route::post('/organizations', [CvOrganizationController::class, 'store']);
        Route::put('/organizations/{organization}', [CvOrganizationController::class, 'update']);
        Route::delete('/organizations/{organization}', [CvOrganizationController::class, 'destroy']);
    });
});
