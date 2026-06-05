<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    // Simpan pesan dari form Contact Us
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:100',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $contactMessage = ContactMessage::create($validated);

        return response()->json([
            'message' => 'Pesan berhasil dikirim. Terima kasih telah menghubungi kami!',
            'data'    => $contactMessage,
        ], 201);
    }

    // Ambil semua pesan kontak
    public function index(): JsonResponse
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->get();

        return response()->json([
            'data' => $messages,
        ]);
    }

    // Hapus pesan kontak
    public function destroy($id): JsonResponse
    {
        $message = ContactMessage::find($id);

        if (!$message) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Pesan tidak ditemukan',
            ], 404);
        }

        $message->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Pesan berhasil dihapus',
        ]);
    }
}
