<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ConferencePaper;
use Illuminate\Support\Facades\Auth;

class UserConfeBookCTRL extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
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
        return back()->with('confesuccess', 'Conference paper created.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
