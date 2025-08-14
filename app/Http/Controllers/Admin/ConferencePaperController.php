<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConferencePaper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ConferencePaperController extends Controller
{
    public function index()
    {
        $conferencePapers = ConferencePaper::orderByDesc('id')->paginate(12);
        $deletedConferencePapers = ConferencePaper::onlyTrashed()->orderByDesc('deleted_at')->paginate(12);
        // Load other tab data to prevent errors
        $research = \App\Models\Research::orderByDesc('id')->paginate(12);
        $deletedResearch = \App\Models\Research::onlyTrashed()->orderByDesc('deleted_at')->paginate(12);
        $preprints = \App\Models\Preprint::orderByDesc('id')->paginate(12);
        $deletedPreprints = \App\Models\Preprint::onlyTrashed()->orderByDesc('deleted_at')->paginate(12);
        $books = \App\Models\Book::orderByDesc('id')->paginate(12);
        $deletedBooks = \App\Models\Book::onlyTrashed()->orderByDesc('deleted_at')->paginate(12);
        return view('admin.research.index', compact('conferencePapers', 'deletedConferencePapers', 'research', 'deletedResearch', 'preprints', 'deletedPreprints', 'books', 'deletedBooks'));
    }

    public function store(Request $request)
    {
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

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'id' => $paper->id]);
        }
        return back()->with('success', 'Conference paper created successfully.');
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


