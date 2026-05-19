<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CourseEnglishContactSubmissionController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'message' => ['nullable', 'string', 'max:2000'],
            'subject' => ['nullable', 'string', 'max:255'],
        ]);

        // Require at least one contact method
        if (empty($data['email']) && empty($data['phone'])) {
            return response()->json([
                'message' => 'Please provide an email or phone number.',
            ], 422);
        }

        $name = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));

        $submission = ContactSubmission::create([
            'name' => $name,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'subject' => $data['subject'] ?? 'Contact Form',
            'message' => $data['message'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Submitted successfully',
            'id' => $submission->id,
        ], 201);
    }
}
