<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactMessageController;
use App\Http\Controllers\Api\CvEducationController;
use App\Http\Controllers\Api\CvEmploymentHistoryController;
use App\Http\Controllers\Api\CvOrganizationController;
use App\Http\Controllers\Api\CvPersonalDetailController;
use App\Http\Controllers\Api\CvProjectController;
use App\Http\Controllers\Api\CvSkillController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\AdminController;

use Illuminate\Support\Facades\Route;



// AUTH (Public)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// CONTACT US (Public)
Route::post('/contact-messages', [ContactMessageController::class, 'store']);

// PROTECTED ROUTES
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);

    // CV Projects
    Route::apiResource('cv-projects', CvProjectController::class)
        ->parameters(['cv-projects' => 'cvProject']);
    Route::post('cv-projects/{cvProject}/download', [CvProjectController::class, 'incrementDownload']);

    // CV Sections
    Route::prefix('cv-projects/{cvProject}')->group(function () {

        // Personal Detail
        Route::get('/personal-detail', [CvPersonalDetailController::class, 'show']);
        Route::post('/personal-detail', [CvPersonalDetailController::class, 'upsert']);

        // Employment History
        Route::get('/employments', [CvEmploymentHistoryController::class, 'index']);
        Route::post('/employments', [CvEmploymentHistoryController::class, 'store']);
        Route::put('/employments/{employment}', [CvEmploymentHistoryController::class, 'update']);
        Route::delete('/employments/{employment}', [CvEmploymentHistoryController::class, 'destroy']);

        // Education
        Route::get('/educations', [CvEducationController::class, 'index']);
        Route::post('/educations', [CvEducationController::class, 'store']);
        Route::put('/educations/{education}', [CvEducationController::class, 'update']);
        Route::delete('/educations/{education}', [CvEducationController::class, 'destroy']);

        // Skills
        Route::get('/skills', [CvSkillController::class, 'index']);
        Route::post('/skills', [CvSkillController::class, 'store']);
        Route::put('/skills/{skill}', [CvSkillController::class, 'update']);
        Route::delete('/skills/{skill}', [CvSkillController::class, 'destroy']);

        // Organizations
        Route::get('/organizations', [CvOrganizationController::class, 'index']);
        Route::post('/organizations', [CvOrganizationController::class, 'store']);
        Route::put('/organizations/{organization}', [CvOrganizationController::class, 'update']);
        Route::delete('/organizations/{organization}', [CvOrganizationController::class, 'destroy']);
    });

    // ADMIN ROUTES
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard-stats', [AdminController::class, 'dashboardStats']);
        Route::get('/users', [AdminController::class, 'getUsers']);
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);
        Route::get('/contact-messages', [ContactMessageController::class, 'index']);
        Route::delete('/contact-messages/{id}', [ContactMessageController::class, 'destroy']);
    });
});
