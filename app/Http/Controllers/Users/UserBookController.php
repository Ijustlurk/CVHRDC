<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class UserBookController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:pdf', 'max:51200'],
            'title' => ['required', 'string', 'max:255'],
            'authors' => ['required', 'array', 'min:1'],
            'authors.*' => ['string', 'max:255'],
            'editors' => ['nullable', 'array'],
            'editors.*' => ['string', 'max:255'],
            'published_at' => ['required', 'date'],
            'publisher' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'max:32'],
            'edition' => ['nullable', 'string', 'max:64'],
            'doi' => ['nullable', 'string', 'max:255'],
            'keywords' => ['nullable', 'array'],
            'keywords.*' => ['string', 'max:255'],
            'is_public' => ['required', 'boolean'],
        ]);

        $file = $request->file('file');
        $path = $file->store('books', 'public');

        $book = Book::create([
            'user_id' => Auth::id(),
            'file_path' => $path,
            'file_original_name' => $file->getClientOriginalName(),
            'file_mime' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'title' => $validated['title'],
            'authors' => $validated['authors'],
            'editors' => $validated['editors'] ?? [],
            'published_at' => $validated['published_at'],
            'publisher' => $validated['publisher'],
            'isbn' => $validated['isbn'],
            'edition' => $validated['edition'] ?? null,
            'doi' => $validated['doi'] ?? null,
            'keywords' => $validated['keywords'] ?? [],
            'is_public' => (bool) $validated['is_public'],
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'id' => $book->id]);
        }

        return back()->with('success', 'Book created successfully.');
    }
}


