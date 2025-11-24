<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserBookController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // Filter all records by the currently authenticated user
        $books = Book::where('user_id', $userId)->orderByDesc('id')->paginate(12);
        $deletedBooks = Book::onlyTrashed()->where('user_id', $userId)->orderByDesc('deleted_at')->paginate(12);
        // Load others so tabs render without errors
        $research = \App\Models\Research::where('user_id', $userId)->orderByDesc('id')->paginate(12);
        $deletedResearch = \App\Models\Research::onlyTrashed()->where('user_id', $userId)->orderByDesc('deleted_at')->paginate(12);
        $conferencePapers = \App\Models\ConferencePaper::where('user_id', $userId)->orderByDesc('id')->paginate(12);
        $deletedConferencePapers = \App\Models\ConferencePaper::onlyTrashed()->where('user_id', $userId)->orderByDesc('deleted_at')->paginate(12);
        $preprints = \App\Models\Preprint::where('user_id', $userId)->orderByDesc('id')->paginate(12);
        $deletedPreprints = \App\Models\Preprint::onlyTrashed()->where('user_id', $userId)->orderByDesc('deleted_at')->paginate(12);
        return view('users.research.index', compact('books', 'deletedBooks', 'research', 'deletedResearch', 'conferencePapers', 'deletedConferencePapers', 'preprints', 'deletedPreprints'));
    }

    public function show(Book $book)
    {
        // Ensure the book belongs to the authenticated user
        if ($book->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this book.');
        }

        return view('users.research.partials.book.show', compact('book'));
    }

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
            'isbn' => ['required', 'string', 'max:20'],
            'edition' => ['nullable', 'string', 'max:100'],
            'doi' => ['nullable', 'string', 'max:255'],
            'keywords' => ['nullable', 'array'],
            'keywords.*' => ['string', 'max:255'],
            'is_public' => ['required', 'boolean'],
        ]);

        $file = $request->file('file');
        $path = $file->store('books', 'public');

        Book::create([
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

        return redirect()->route('users.research.index', ['type' => 'book'])->with('success', 'Book created successfully.');
    }

    public function edit(Book $book)
    {
        return view('users.research.partials.book.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'authors' => ['required', 'array', 'min:1'],
            'authors.*' => ['string', 'max:255'],
            'editors' => ['nullable', 'array'],
            'editors.*' => ['string', 'max:255'],
            'published_at' => ['required', 'date'],
            'publisher' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'max:20'],
            'edition' => ['nullable', 'string', 'max:100'],
            'doi' => ['nullable', 'string', 'max:255'],
            'keywords' => ['nullable', 'array'],
            'keywords.*' => ['string', 'max:255'],
            'is_public' => ['required', 'boolean'],
        ]);

        $book->update([
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

        return redirect()->route('users.research.index', ['type' => 'book'])->with('success', 'Book updated successfully.');
    }

    public function updateVisibility(Request $request, Book $book)
    {
        $request->validate(['is_public' => ['required', 'boolean']]);
        $book->update(['is_public' => (bool) $request->boolean('is_public')]);
        return redirect()->route('users.research.index', ['type' => 'book'])->with('success', 'Visibility updated.');
    }

    public function destroy(Book $book)
    {
        // Keep file; just soft-delete
        $book->delete();
        return redirect()->route('users.research.index', ['type' => 'book'])->with('success', 'Book moved to trash successfully.');
    }

    public function restore($id)
    {
        $book = Book::onlyTrashed()->findOrFail($id);
        $book->restore();
        return redirect()->route('users.research.index', ['type' => 'book'])->with('success', 'Book restored successfully.');
    }

    public function forceDelete($id)
    {
        $book = Book::onlyTrashed()->findOrFail($id);
        if ($book->file_path && Storage::disk('public')->exists($book->file_path)) {
            Storage::disk('public')->delete($book->file_path);
        }
        $book->forceDelete();
        return redirect()->route('users.research.index', ['type' => 'book'])->with('success', 'Book permanently deleted.');
    }
}


