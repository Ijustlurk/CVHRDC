<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Research;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ResearchController extends Controller
{
    public function index()
    {
        $research = Research::orderByDesc('id')->paginate(12);
        $deletedResearch = Research::onlyTrashed()->orderByDesc('deleted_at')->paginate(12);
        // For Conference tab to render lists when selected
        $conferencePapers = \App\Models\ConferencePaper::orderByDesc('id')->paginate(12);
        $deletedConferencePapers = \App\Models\ConferencePaper::onlyTrashed()->orderByDesc('deleted_at')->paginate(12);
        // For Preprint tab to render lists when selected
        $preprints = \App\Models\Preprint::orderByDesc('id')->paginate(12);
        $deletedPreprints = \App\Models\Preprint::onlyTrashed()->orderByDesc('deleted_at')->paginate(12);
        return view('admin.research.index', compact('research', 'deletedResearch', 'conferencePapers', 'deletedConferencePapers', 'preprints', 'deletedPreprints'));
    }

    public function store(Request $request)
    {
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

        $file = $request->file('file');
        $path = $file->store('research', 'public');

        $research = Research::create([
            'user_id' => Auth::id(),
            'file_path' => $path,
            'file_original_name' => $file->getClientOriginalName(),
            'file_mime' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'title' => $validated['title'],
            'authors' => $validated['authors'],
            'published_at' => $validated['published_at'],
            'abstract' => $validated['abstract'] ?? null,
            'category' => $validated['category'],
            'keywords' => $validated['keywords'] ?? [],
            'is_public' => (bool)$validated['is_public'],
        ]);

        return redirect()->route('admin.tabs.index', ['tab' => 'research'])
            ->with('success', 'Research entry published successfully.');
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
        return back()->with('success', 'Visibility updated.');
    }

    public function restore($id)
    {
        $research = Research::onlyTrashed()->findOrFail($id);
        $research->restore();
        return back()->with('success', 'Research restored successfully.');
    }
}
