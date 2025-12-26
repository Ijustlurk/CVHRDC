<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Preprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PreprintController extends Controller
{
    public function index(Request $request)
    {
        // Build query for published preprints
        $query = Preprint::query();
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('abstract', 'like', "%{$search}%")
                  ->orWhere('repository_name', 'like', "%{$search}%")
                  ->orWhereJsonContains('authors', $search)
                  ->orWhereJsonContains('keywords', $search);
            });
        }
        
        // Apply peer-reviewed filter
        if ($request->filled('peer_reviewed')) {
            $isPeerReviewed = $request->peer_reviewed === 'Yes' ? 1 : 0;
            $query->where('is_peer_reviewed', $isPeerReviewed);
        }
        
        // Apply visibility filter
        if ($request->filled('visibility')) {
            $isPublic = $request->visibility === 'Public' ? 1 : 0;
            $query->where('is_public', $isPublic);
        }
        
        // Apply date filter
        if ($request->filled('date_filter')) {
            switch ($request->date_filter) {
                case 'last_7_days':
                    $query->where('created_at', '>=', now()->subDays(7));
                    break;
                case 'last_30_days':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
                case 'last_6_months':
                    $query->where('created_at', '>=', now()->subMonths(6));
                    break;
                case 'custom':
                    if ($request->filled('date_from')) {
                        $query->where('created_at', '>=', $request->date_from);
                    }
                    if ($request->filled('date_to')) {
                        $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
                    }
                    break;
            }
        }
        
        // Apply sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'a_z':
                $query->orderBy('title', 'asc');
                break;
            case 'z_a':
                $query->orderBy('title', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        $preprints = $query->paginate(12)->appends($request->except('page'));
        $deletedPreprints = Preprint::onlyTrashed()->orderByDesc('deleted_at')->paginate(12);
        
        // Also load other tab data to prevent errors
        $research = \App\Models\Research::orderByDesc('id')->paginate(12);
        $deletedResearch = \App\Models\Research::onlyTrashed()->orderByDesc('deleted_at')->paginate(12);
        $conferencePapers = \App\Models\ConferencePaper::orderByDesc('id')->paginate(12);
        $deletedConferencePapers = \App\Models\ConferencePaper::onlyTrashed()->orderByDesc('deleted_at')->paginate(12);
        $books = \App\Models\Book::orderByDesc('id')->paginate(12);
        $deletedBooks = \App\Models\Book::onlyTrashed()->orderByDesc('deleted_at')->paginate(12);
        
        // Handle AJAX requests
        if ($request->ajax()) {
            $html = view('admin.research.partials.preprint.preprint-table', compact('preprints'))->render();
            return response()->json([
                'html' => $html,
                'from' => $preprints->firstItem(),
                'to' => $preprints->lastItem(),
                'totalCount' => $preprints->total()
            ]);
        }
        
        return view('admin.research.index', compact('preprints', 'deletedPreprints', 'research', 'deletedResearch', 'conferencePapers', 'deletedConferencePapers', 'books', 'deletedBooks'));
    }

    public function show(Preprint $preprint)
    {
        return view('admin.research.partials.preprint.show', compact('preprint'));
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

    public function edit(Preprint $preprint)
    {
        return view('admin.research.partials.preprint.edit', compact('preprint'));
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

        return redirect()->route('admin.research.index', ['type' => 'preprint'])->with('success', 'Preprint updated successfully.');
    }

    public function updateVisibility(Request $request, Preprint $preprint)
    {
        $request->validate(['is_public' => ['required', 'boolean']]);
        $preprint->update(['is_public' => (bool)$request->boolean('is_public')]);
        return redirect()->route('admin.research.index', ['type' => 'preprint'])->with('success', 'Visibility updated.');
    }

    public function destroy(Preprint $preprint)
    {
        // Only soft delete - don't remove the file yet
        $preprint->delete();
        return redirect()->route('admin.research.index', ['type' => 'preprint'])->with('success', 'Preprint moved to trash successfully.');
    }

    public function restore($id)
    {
        $preprint = Preprint::onlyTrashed()->findOrFail($id);
        $preprint->restore();
        return redirect()->route('admin.research.index', ['type' => 'preprint'])->with('success', 'Preprint restored successfully.');
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
        
        return redirect()->route('admin.research.index', ['type' => 'preprint'])->with('success', 'Preprint permanently deleted.');
    }
}
