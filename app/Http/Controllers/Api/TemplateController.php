<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Tampilkan semua template yang tersedia.
     */
    public function index(): JsonResponse
    {
        $templates = Template::all();

        return response()->json([
            'data' => $templates,
        ]);
    }

    /**
     * Tampilkan detail satu template.
     */
    public function show(Template $template): JsonResponse
    {
        return response()->json([
            'data' => $template,
        ]);
    }
}
