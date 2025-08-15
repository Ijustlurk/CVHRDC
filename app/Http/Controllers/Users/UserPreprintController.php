<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Preprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UserPreprintController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'file' => ['required', 'file', 'mimes:pdf', 'max:51200'],
                'title' => ['required', 'string', 'max:255'],
                'authors' => ['required', 'array', 'min:1'],
                'authors.*' => ['string', 'max:255'],
                'published_at' => ['nullable', 'date'],
                'repository_name' => ['required', 'string', 'max:255'],
                'abstract' => ['nullable', 'string'],
                'keywords' => ['nullable', 'array'],
                'keywords.*' => ['string', 'max:255'],
                'is_peer_reviewed' => ['nullable', 'boolean'],
                'is_public' => ['required', 'boolean'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['errors' => $e->errors()], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        }

        try {
            $file = $request->file('file');
            $path = $file->store('preprints', 'public');

            $preprint = Preprint::create([
                'user_id' => Auth::id(),
                'file_path' => $path,
                'file_original_name' => $file->getClientOriginalName(),
                'file_mime' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'title' => $validated['title'],
                'authors' => $validated['authors'],
                'published_at' => $validated['published_at'] ?? null,
                'repository_name' => $validated['repository_name'],
                'abstract' => $validated['abstract'] ?? null,
                'keywords' => $validated['keywords'] ?? [],
                'is_peer_reviewed' => (bool) ($validated['is_peer_reviewed'] ?? false),
                'is_public' => (bool) $validated['is_public'],
            ]);

            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['success' => true, 'id' => $preprint->id, 'message' => 'Preprint created successfully!']);
            }
            return redirect()->route('admin.research.index', ['type' => 'preprint'])->with('success', 'Preprint created successfully.');
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Preprint creation failed: ' . $e->getMessage());

            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['error' => 'Failed to create preprint: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Failed to create preprint: ' . $e->getMessage())->withInput();
        }
    }

}
