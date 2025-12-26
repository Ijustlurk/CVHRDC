<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Preprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UserPreprintController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        // Start with base query filtered by user
        $query = Preprint::where('user_id', $userId);

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('abstract', 'like', "%{$search}%")
                  ->orWhere('repository_name', 'like', "%{$search}%")
                  ->orWhereRaw("JSON_SEARCH(authors, 'one', ?, NULL, '$[*]') IS NOT NULL", ["%{$search}%"])
                  ->orWhereRaw("JSON_SEARCH(keywords, 'one', ?, NULL, '$[*]') IS NOT NULL", ["%{$search}%"]);
            });
        }

        // Apply peer-reviewed filter
        if ($request->filled('peer_reviewed')) {
            $peerReviewed = $request->input('peer_reviewed');
            if ($peerReviewed === 'Yes') {
                $query->where('is_peer_reviewed', true);
            } elseif ($peerReviewed === 'No') {
                $query->where('is_peer_reviewed', false);
            }
        }

        // Apply visibility filter
        if ($request->filled('visibility')) {
            $visibility = $request->input('visibility');
            if ($visibility === 'Public') {
                $query->where('is_public', true);
            } elseif ($visibility === 'Private') {
                $query->where('is_public', false);
            }
        }

        // Apply date filter
        if ($request->filled('date_filter')) {
            $dateFilter = $request->input('date_filter');
            
            switch ($dateFilter) {
                case 'last_7_days':
                    $query->where('published_at', '>=', now()->subDays(7));
                    break;
                case 'last_30_days':
                    $query->where('published_at', '>=', now()->subDays(30));
                    break;
                case 'last_6_months':
                    $query->where('published_at', '>=', now()->subMonths(6));
                    break;
                case 'custom':
                    if ($request->filled('date_from')) {
                        $query->where('published_at', '>=', $request->input('date_from'));
                    }
                    if ($request->filled('date_to')) {
                        $query->where('published_at', '<=', $request->input('date_to'));
                    }
                    break;
            }
        }

        // Apply sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;
            case 'a_z':
                $query->orderBy('title', 'asc');
                break;
            case 'z_a':
                $query->orderBy('title', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('published_at', 'desc');
                break;
        }

        // Paginate results
        $preprints = $query->paginate(12)->appends($request->except('page'));
        
        // Get deleted preprints
        $deletedPreprints = Preprint::onlyTrashed()->where('user_id', $userId)->orderByDesc('deleted_at')->paginate(12);
        
        // Also load other tab data to prevent errors
        $research = \App\Models\Research::where('user_id', $userId)->orderByDesc('id')->paginate(12);
        $deletedResearch = \App\Models\Research::onlyTrashed()->where('user_id', $userId)->orderByDesc('deleted_at')->paginate(12);
        $conferencePapers = \App\Models\ConferencePaper::where('user_id', $userId)->orderByDesc('id')->paginate(12);
        $deletedConferencePapers = \App\Models\ConferencePaper::onlyTrashed()->where('user_id', $userId)->orderByDesc('deleted_at')->paginate(12);
        $books = \App\Models\Book::where('user_id', $userId)->orderByDesc('id')->paginate(12);
        $deletedBooks = \App\Models\Book::onlyTrashed()->where('user_id', $userId)->orderByDesc('deleted_at')->paginate(12);

        // Handle AJAX requests
        if ($request->wantsJson() || $request->ajax()) {
            $html = view('users.research.partials.preprint.preprint-table', compact('preprints'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'from' => $preprints->firstItem(),
                'to' => $preprints->lastItem(),
                'totalCount' => $preprints->total(),
                'currentPage' => $preprints->currentPage(),
                'lastPage' => $preprints->lastPage(),
            ]);
        }
        
        return view('users.research.index', compact('preprints', 'deletedPreprints', 'research', 'deletedResearch', 'conferencePapers', 'deletedConferencePapers', 'books', 'deletedBooks'));
    }

    public function show(Preprint $preprint)
    {
        // Ensure the preprint belongs to the authenticated user
        if ($preprint->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this preprint.');
        }

        return view('users.research.partials.preprint.show', compact('preprint'));
    }

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
            return redirect()->route('users.research.index', ['type' => 'preprint'])->with('success', 'Preprint created successfully.');
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Preprint creation failed: ' . $e->getMessage());
            
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['error' => 'Failed to create preprint: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Failed to create preprint: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Preprint $preprint)
    {
        return view('users.research.partials.preprint.edit', compact('preprint'));
    }

    public function update(Request $request, Preprint $preprint)
    {
        $validated = $request->validate([
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

        $preprint->update([
            'title' => $validated['title'],
            'authors' => $validated['authors'],
            'published_at' => $validated['published_at'] ?? null,
            'repository_name' => $validated['repository_name'],
            'abstract' => $validated['abstract'] ?? null,
            'keywords' => $validated['keywords'] ?? [],
            'is_peer_reviewed' => (bool) ($validated['is_peer_reviewed'] ?? false),
            'is_public' => (bool) $validated['is_public'],
        ]);

        return redirect()->route('users.research.index', ['type' => 'preprint'])->with('success', 'Preprint updated successfully.');
    }

    public function updateVisibility(Request $request, Preprint $preprint)
    {
        $request->validate(['is_public' => ['required', 'boolean']]);
        $preprint->update(['is_public' => (bool)$request->boolean('is_public')]);
        return redirect()->route('users.research.index', ['type' => 'preprint'])->with('success', 'Visibility updated.');
    }

    public function destroy(Preprint $preprint)
    {
        // Only soft delete - don't remove the file yet
        $preprint->delete();
        return redirect()->route('users.research.index', ['type' => 'preprint'])->with('success', 'Preprint moved to trash successfully.');
    }

    public function restore($id)
    {
        $preprint = Preprint::onlyTrashed()->findOrFail($id);
        $preprint->restore();
        return redirect()->route('users.research.index', ['type' => 'preprint'])->with('success', 'Preprint restored successfully.');
    }

    public function forceDelete($id)
    {
        $preprint = Preprint::onlyTrashed()->findOrFail($id);
        
        // Delete the actual file from storage
        if ($preprint->file_path && Storage::disk('public')->exists($preprint->file_path)) {
            Storage::disk('public')->delete($preprint->file_path);
        }
        
        // Permanently delete the record
        $preprint->forceDelete();
        
        return redirect()->route('users.research.index', ['type' => 'preprint'])->with('success', 'Preprint permanently deleted.');
    }
}
