<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageBuilderController extends Controller
{
    public function edit(Page $page): View
    {
        return view('admin.builder', compact('page'));
    }

    public function update(Request $request, Page $page): JsonResponse
    {
        $validated = $request->validate([
            'content'  => ['nullable', 'string'],
            'css'      => ['nullable', 'string'],
            'gjs_data' => ['nullable'], // Receives project JSON tree
        ]);

        $page->update([
            'content'     => $validated['content'] ?? '',
            'css'         => $validated['css'] ?? '',
            'gjs_data'    => $validated['gjs_data'] ?? null,
            'editor_type' => 'grapesjs',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Page design saved successfully!',
        ]);
    }
}
