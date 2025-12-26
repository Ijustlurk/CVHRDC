<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConferencePaper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ConferencePaperController extends Controller
{
    public function index(Request $request)
    {
        // Build query for published conference papers
        $query = ConferencePaper::query();
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('abstract', 'like', "%{$search}%")
                  ->orWhere('conference_name', 'like', "%{$search}%")
                  ->orWhere('conference_location', 'like', "%{$search}%")
                  ->orWhere('publisher', 'like', "%{$search}%")
                  ->orWhereJsonContains('authors', $search)
                  ->orWhereJsonContains('keywords', $search);
            });
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
        
        $conferencePapers = $query->paginate(12)->appends($request->except('page'));
        $deletedConferencePapers = ConferencePaper::onlyTrashed()->orderByDesc('deleted_at')->paginate(12);
        
        // Load other tab data to prevent errors
        $research = \App\Models\Research::orderByDesc('id')->paginate(12);
        $deletedResearch = \App\Models\Research::onlyTrashed()->orderByDesc('deleted_at')->paginate(12);
        $preprints = \App\Models\Preprint::orderByDesc('id')->paginate(12);
        $deletedPreprints = \App\Models\Preprint::onlyTrashed()->orderByDesc('deleted_at')->paginate(12);
        $books = \App\Models\Book::orderByDesc('id')->paginate(12);
        $deletedBooks = \App\Models\Book::onlyTrashed()->orderByDesc('deleted_at')->paginate(12);
        
        // Handle AJAX requests
        if ($request->ajax()) {
            $html = view('admin.research.partials.conference.conference-table', compact('conferencePapers'))->render();
            return response()->json([
                'html' => $html,
                'from' => $conferencePapers->firstItem(),
                'to' => $conferencePapers->lastItem(),
                'totalCount' => $conferencePapers->total()
            ]);
        }
        
        return view('admin.research.index', compact('conferencePapers', 'deletedConferencePapers', 'research', 'deletedResearch', 'preprints', 'deletedPreprints', 'books', 'deletedBooks'));
    }

    public function show(ConferencePaper $conference_paper)
    {
        return view('admin.research.partials.conference.show', ['conferencePaper' => $conference_paper]);
    }

    public function store(Request $request)
    {
        // Check for duplicate submission using submission ID
        $submissionId = $request->input('submission_id');
        if ($submissionId) {
            // Check if this submission ID was already processed
            $cacheKey = 'conference_paper_submission_' . $submissionId;
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
        
        // Additional duplicate prevention: Check for similar conference paper within last 5 minutes
        $recentPaper = ConferencePaper::where('user_id', Auth::id())
            ->where('title', $request->input('title'))
            ->where('created_at', '>=', now()->subMinutes(5))
            ->first();
            
        if ($recentPaper) {
            return response()->json([
                'success' => false,
                'message' => 'A conference paper with this title was already submitted recently. Please wait a few minutes before submitting again.',
                'duplicate' => true
            ], 409);
        }

        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:pdf', 'max:51200'],
            'title' => ['required', 'string', 'max:255'],
            'authors' => ['required', 'array', 'min:1'],
            'authors.*' => ['string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'conference_name' => ['required', 'string', 'max:255'],
            'conference_location' => ['required', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'abstract' => ['nullable', 'string'],
            'language' => ['nullable', 'string', 'size:2'],
            'keywords' => ['nullable', 'array'],
            'keywords.*' => ['string', 'max:255'],
            'is_public' => ['required', 'boolean'],
        ]);

        try {
            $file = $request->file('file');
            $path = $file->store('conference_papers', 'public');

            $paper = ConferencePaper::create([
                'user_id' => Auth::id(),
                'file_path' => $path,
                'file_original_name' => $file->getClientOriginalName(),
                'file_mime' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'title' => $validated['title'],
                'authors' => $validated['authors'],
                'published_at' => $validated['published_at'] ?? null,
                'conference_name' => $validated['conference_name'],
                'conference_location' => $validated['conference_location'],
                'publisher' => $validated['publisher'] ?? null,
                'abstract' => $validated['abstract'] ?? null,
                'language' => $validated['language'] ?? null,
                'keywords' => $validated['keywords'] ?? [],
                'is_public' => (bool) $validated['is_public'],
            ]);

            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Conference paper created successfully.',
                    'paper' => $paper
                ], 201);
            }

            // For regular form submissions (fallback)
            return redirect()->route('admin.tabs.index', ['tab' => 'conference'])
                ->with('success', 'Conference paper created successfully.');
                
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error creating conference paper: ' . $e->getMessage());
            
            // Remove the cached submission ID on error
            if ($submissionId) {
                cache()->forget('conference_paper_submission_' . $submissionId);
            }
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while creating the conference paper. Please try again.',
                    'error' => $e->getMessage()
                ], 409);
            }
            
            // For regular form submissions (fallback)
            return redirect()->back()->with('error', 'An error occurred while creating the conference paper. Please try again.');
        }
    }

    public function edit(ConferencePaper $conference_paper)
    {
        return view('admin.research.partials.conference.edit', ['paper' => $conference_paper]);
    }

    public function update(Request $request, ConferencePaper $conference_paper)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'authors' => ['required', 'array', 'min:1'],
            'authors.*' => ['string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'conference_name' => ['required', 'string', 'max:255'],
            'conference_location' => ['required', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'abstract' => ['nullable', 'string'],
            'language' => ['nullable', 'string', 'size:2'],
            'keywords' => ['nullable', 'array'],
            'keywords.*' => ['string', 'max:255'],
            'is_public' => ['required', 'boolean'],
        ]);

        $conference_paper->update([
            'title' => $validated['title'],
            'authors' => $validated['authors'],
            'published_at' => $validated['published_at'] ?? null,
            'conference_name' => $validated['conference_name'],
            'conference_location' => $validated['conference_location'],
            'publisher' => $validated['publisher'] ?? null,
            'abstract' => $validated['abstract'] ?? null,
            'language' => $validated['language'] ?? null,
            'keywords' => $validated['keywords'] ?? [],
            'is_public' => (bool) $validated['is_public'],
        ]);

        return back()->with('success', 'Conference paper updated successfully.');
    }

    public function updateVisibility(Request $request, ConferencePaper $conference_paper)
    {
        $request->validate(['is_public' => ['required', 'boolean']]);
        $conference_paper->update(['is_public' => (bool)$request->boolean('is_public')]);
        return back()->with('success', 'Visibility updated.');
    }

    public function destroy(ConferencePaper $conference_paper)
    {
        if ($conference_paper->file_path && Storage::disk('public')->exists($conference_paper->file_path)) {
            Storage::disk('public')->delete($conference_paper->file_path);
        }
        $conference_paper->delete();
        return back()->with('success', 'Conference paper deleted successfully.');
    }

    public function restore($id)
    {
        $paper = ConferencePaper::onlyTrashed()->findOrFail($id);
        $paper->restore();
        return back()->with('success', 'Conference paper restored successfully.');
    }

    public function forceDelete($id)
    {
        $paper = ConferencePaper::onlyTrashed()->findOrFail($id);
        if ($paper->file_path && Storage::disk('public')->exists($paper->file_path)) {
            Storage::disk('public')->delete($paper->file_path);
        }
        $paper->forceDelete();
        return back()->with('success', 'Conference paper permanently deleted.');
    }
}