<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Research;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserResearchCTRL extends Controller
{
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

    return redirect()->back()->with('success', 'Research uploaded successfully!');

    }


}
