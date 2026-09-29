<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function send(Request $request): JsonResponse
    {
        // 1. Separate system meta-keys from user inputs
        $formType = $request->input('_form_type', 'contact');
        $pageId   = $request->input('_page_id');

        $dynamicInputs = $request->except([
            '_token',
            '_form_type',
            '_page_id',
        ]);

        // 2. Reject empty submissions
        if (empty(array_filter($dynamicInputs))) {
            return response()->json([
                'success' => false,
                'message' => 'Please fill in at least one field before submitting.',
            ], 422);
        }

        // 3. Dynamic sanitization: strip tags and limit max string length per field
        $sanitizedData = [];
        foreach ($dynamicInputs as $key => $value) {
            // Clean keys and values to prevent XSS/injection
            $cleanKey = substr(strip_tags(trim($key)), 0, 100);

            if (is_array($value)) {
                $sanitizedData[$cleanKey] = array_map(fn($item) => substr(strip_tags(trim($item)), 0, 500), $value);
            } else {
                $sanitizedData[$cleanKey] = substr(strip_tags(trim((string)$value)), 0, 2000);
            }
        }

        // 4. Save to database
        FormSubmission::create([
            'form_type'  => $formType,
            'page_id'    => $pageId,
            'data'       => $sanitizedData,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your submission has been received.',
        ]);
    }
}
