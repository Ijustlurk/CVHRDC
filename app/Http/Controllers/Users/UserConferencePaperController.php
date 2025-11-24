<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\ConferencePaper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UserConferencePaperController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // Filter all records by the currently authenticated user
        $conferencePapers = ConferencePaper::where('user_id', $userId)->orderByDesc('id')->paginate(12);
        $deletedConferencePapers = ConferencePaper::onlyTrashed()->where('user_id', $userId)->orderByDesc('deleted_at')->paginate(12);
        // Load other tab data to prevent errors
        $research = \App\Models\Research::where('user_id', $userId)->orderByDesc('id')->paginate(12);
        $deletedResearch = \App\Models\Research::onlyTrashed()->where('user_id', $userId)->orderByDesc('deleted_at')->paginate(12);
        $preprints = \App\Models\Preprint::where('user_id', $userId)->orderByDesc('id')->paginate(12);
        $deletedPreprints = \App\Models\Preprint::onlyTrashed()->where('user_id', $userId)->orderByDesc('deleted_at')->paginate(12);
        $books = \App\Models\Book::where('user_id', $userId)->orderByDesc('id')->paginate(12);
        $deletedBooks = \App\Models\Book::onlyTrashed()->where('user_id', $userId)->orderByDesc('deleted_at')->paginate(12);
        return view('users.research.index', compact('conferencePapers', 'deletedConferencePapers', 'research', 'deletedResearch', 'preprints', 'deletedPreprints', 'books', 'deletedBooks'));
    }

    public function show(ConferencePaper $conference_paper)
    {
        // Ensure the conference paper belongs to the authenticated user
        if ($conference_paper->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this conference paper.');
        }

        return view('users.research.partials.conference.show', ['conferencePaper' => $conference_paper]);
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
            return redirect()->route('users.tabs.index', ['tab' => 'conference'])
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
        return view('users.research.partials.conference.edit', ['paper' => $conference_paper]);
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