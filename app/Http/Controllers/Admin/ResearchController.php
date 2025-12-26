<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Research;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ResearchController extends Controller
{
    public function index(Request $request)
    {
        // Build query for published research
        $query = Research::query();
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('abstract', 'like', "%{$search}%")
                  ->orWhereJsonContains('authors', $search)
                  ->orWhereJsonContains('keywords', $search);
            });
        }
        
        // Apply category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
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
        
        $research = $query->paginate(12)->appends($request->except('page'));
        $deletedResearch = Research::onlyTrashed()->orderByDesc('deleted_at')->paginate(12);
        
        // For Conference tab to render lists when selected
        $conferencePapers = \App\Models\ConferencePaper::orderByDesc('id')->paginate(12);
        $deletedConferencePapers = \App\Models\ConferencePaper::onlyTrashed()->orderByDesc('deleted_at')->paginate(12);
        // For Preprint tab to render lists when selected
        $preprints = \App\Models\Preprint::orderByDesc('id')->paginate(12);
        $deletedPreprints = \App\Models\Preprint::onlyTrashed()->orderByDesc('deleted_at')->paginate(12);
        // For Book tab to render lists when selected
        $books = \App\Models\Book::orderByDesc('id')->paginate(12);
        $deletedBooks = \App\Models\Book::onlyTrashed()->orderByDesc('deleted_at')->paginate(12);
        
        // Handle AJAX requests
        if ($request->ajax()) {
            $html = view('admin.research.partials.research.research-table', compact('research'))->render();
            return response()->json([
                'html' => $html,
                'from' => $research->firstItem(),
                'to' => $research->lastItem(),
                'totalCount' => $research->total()
            ]);
        }
        
        return view('admin.research.index', compact('research', 'deletedResearch', 'conferencePapers', 'deletedConferencePapers', 'preprints', 'deletedPreprints', 'books', 'deletedBooks'));
    }

    public function show(Research $research)
    {
        return view('admin.research.partials.research.show', compact('research'));
    }

    public function store(Request $request)
    {
        // Check for duplicate submission using submission ID
        $submissionId = $request->input('submission_id');
        if ($submissionId) {
            // Check if this submission ID was already processed
            $cacheKey = 'research_submission_' . $submissionId;
            if (cache()->has($cacheKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'This submission has already been processed.',
                    'duplicate' => true
                ], 409);
            }
            
            // Mark this submission ID as processed (cache for 5 minutes)
            cache()->put($cacheKey, true, 300);
        }
        
        // Additional duplicate prevention: Check for similar research within last 5 minutes
        $recentResearch = Research::where('user_id', Auth::id())
            ->where('title', $request->input('title'))
            ->where('created_at', '>=', now()->subMinutes(5))
            ->first();
            
        if ($recentResearch) {
            return response()->json([
                'success' => false,
                'message' => 'A research entry with this title was already submitted recently. Please wait a few minutes before submitting again.',
                'duplicate' => true
            ], 409);
        }
        
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:pdf', 'max:51200'], // 50MB
            'title' => ['required', 'string', 'max:255'],
            'authors' => ['required', 'array', 'min:1'],
            'authors.*' => ['string', 'max:255'],
            'published_at' => ['required', 'date'],
            'abstract' => ['nullable', 'string'],
            'category' => ['required', 'in:Terminal,Technological,Social'],
            'keywords' => ['nullable', 'array'],
            'keywords.*' => ['string', 'max:255'],
            'is_public' => ['required', 'boolean'],
        ]);
    
        try {
            $file = $request->file('file');
            $path = $file->store('research', 'public');
        
            $research = Research::create([
                'user_id' => Auth::id(),
                'file_path' => $path,
                'file_original_name' => $file->getClientOriginalName(),
                'file_mime' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'title' => $validated['title'],
                'authors' => $validated['authors'],
                'published_at' => $validated['published_at'],
                'abstract' => $validated['abstract'] ?? null,
                'category' => $validated['category'],
                'keywords' => $validated['keywords'] ?? [],
                'is_public' => (bool)$validated['is_public'],
            ]);
        
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Research entry published successfully.',
                    'research' => $research
                ], 201);
            }
        
            // For regular form submissions (fallback)
            return redirect()->route('admin.tabs.index', ['tab' => 'research'])
                ->with('success', 'Research entry published successfully.');
                
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error creating research: ' . $e->getMessage());
            
            // Remove the cached submission ID on error
            if ($submissionId) {
                cache()->forget('research_submission_' . $submissionId);
            }
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while creating the research entry. Please try again.',
                    'error' => $e->getMessage()
                ], 409);
            }
            
            // For regular form submissions (fallback)
            return redirect()->back()->with('error', 'An error occurred while creating the research entry. Please try again.');
        }
    }

    public function edit(Research $research)
    {
        return view('admin.research.partials.research.edit', compact('research'));
    }

    public function update(Request $request, Research $research)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'authors' => ['required', 'array', 'min:1'],
            'authors.*' => ['string', 'max:255'],
            'published_at' => ['required', 'date'],
            'abstract' => ['nullable', 'string'],
            'category' => ['required', 'in:Terminal,Technological,Social'],
            'keywords' => ['nullable', 'array'],
            'keywords.*' => ['string', 'max:255'],
            'is_public' => ['required', 'boolean'],
        ]);

        $research->update([
            'title' => $validated['title'],
            'authors' => $validated['authors'],
            'published_at' => $validated['published_at'],
            'abstract' => $validated['abstract'] ?? null,
            'category' => $validated['category'],
            'keywords' => $validated['keywords'] ?? [],
            'is_public' => (bool)$validated['is_public'],
        ]);

        return back()->with('success', 'Research updated successfully.');
    }

    public function destroy(Research $research)
    {
        // Optionally remove file from storage
        if ($research->file_path && Storage::disk('public')->exists($research->file_path)) {
            Storage::disk('public')->delete($research->file_path);
        }
        $research->delete();
        return back()->with('success', 'Research deleted successfully.');
    }

    public function updateVisibility(Request $request, Research $research)
    {
        $request->validate(['is_public' => ['required', 'boolean']]);
        $research->update(['is_public' => (bool)$request->boolean('is_public')]);
        return back()->with('successvisibility', 'Visibility updated.');
    }

    public function restore($id)
    {
        $research = Research::onlyTrashed()->findOrFail($id);
        $research->restore();
        return back()->with('success', 'Research restored successfully.');
    }

    public function forceDelete($id)
    {
        $research = Research::onlyTrashed()->findOrFail($id);
        if ($research->file_path && Storage::disk('public')->exists($research->file_path)) {
            Storage::disk('public')->delete($research->file_path);
        }
        $research->forceDelete();
        return back()->with('success', 'Research permanently deleted.');
    }
}
