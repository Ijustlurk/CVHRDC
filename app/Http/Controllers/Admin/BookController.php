<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        // Start with base query
        $query = Book::query();

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('publisher', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%")
                  ->orWhere('doi', 'like', "%{$search}%")
                  ->orWhere('edition', 'like', "%{$search}%")
                  ->orWhereRaw("JSON_SEARCH(authors, 'one', ?, NULL, '$[*]') IS NOT NULL", ["%{$search}%"])
                  ->orWhereRaw("JSON_SEARCH(editors, 'one', ?, NULL, '$[*]') IS NOT NULL", ["%{$search}%"])
                  ->orWhereRaw("JSON_SEARCH(keywords, 'one', ?, NULL, '$[*]') IS NOT NULL", ["%{$search}%"]);
            });
        }

        // Apply visibility filter
        if ($request->filled('visibility')) {
            $visibility = $request->input('visibility');
            if ($visibility === 'Public') {
                $query->where('is_public', true);
            } elseif ($visibility === 'Private') {
                $query->where('is_public', false);
            }
        }

        // Apply date filter
        if ($request->filled('date_filter')) {
            $dateFilter = $request->input('date_filter');
            
            switch ($dateFilter) {
                case 'last_7_days':
                    $query->where('published_at', '>=', now()->subDays(7));
                    break;
                case 'last_30_days':
                    $query->where('published_at', '>=', now()->subDays(30));
                    break;
                case 'last_6_months':
                    $query->where('published_at', '>=', now()->subMonths(6));
                    break;
                case 'custom':
                    if ($request->filled('date_from')) {
                        $query->where('published_at', '>=', $request->input('date_from'));
                    }
                    if ($request->filled('date_to')) {
                        $query->where('published_at', '<=', $request->input('date_to'));
                    }
                    break;
            }
        }

        // Apply sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;
            case 'a_z':
                $query->orderBy('title', 'asc');
                break;
            case 'z_a':
                $query->orderBy('title', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('published_at', 'desc');
                break;
        }

        // Paginate results
        $books = $query->paginate(12)->appends($request->except('page'));
        
        // Get deleted books
        $deletedBooks = Book::onlyTrashed()->orderByDesc('deleted_at')->paginate(12);
        
        // Load others so tabs render without errors
        $research = \App\Models\Research::orderByDesc('id')->paginate(12);
        $deletedResearch = \App\Models\Research::onlyTrashed()->orderByDesc('deleted_at')->paginate(12);
        $conferencePapers = \App\Models\ConferencePaper::orderByDesc('id')->paginate(12);
        $deletedConferencePapers = \App\Models\ConferencePaper::onlyTrashed()->orderByDesc('deleted_at')->paginate(12);
        $preprints = \App\Models\Preprint::orderByDesc('id')->paginate(12);
        $deletedPreprints = \App\Models\Preprint::onlyTrashed()->orderByDesc('deleted_at')->paginate(12);

        // Handle AJAX requests
        if ($request->wantsJson() || $request->ajax()) {
            $html = view('admin.research.partials.book.book-table', compact('books'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'from' => $books->firstItem(),
                'to' => $books->lastItem(),
                'totalCount' => $books->total(),
                'currentPage' => $books->currentPage(),
                'lastPage' => $books->lastPage(),
            ]);
        }

        return view('admin.research.index', compact('books', 'deletedBooks', 'research', 'deletedResearch', 'conferencePapers', 'deletedConferencePapers', 'preprints', 'deletedPreprints'));
    }

    public function show(Book $book)
    {
        return view('admin.research.partials.book.show', compact('book'));
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

        return redirect()->route('admin.research.index', ['type' => 'book'])->with('success', 'Book created successfully.');
    }

    public function edit(Book $book)
    {
        return view('admin.research.partials.book.edit', compact('book'));
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

        return redirect()->route('admin.research.index', ['type' => 'book'])->with('success', 'Book updated successfully.');
    }

    public function updateVisibility(Request $request, Book $book)
    {
        $request->validate(['is_public' => ['required', 'boolean']]);
        $book->update(['is_public' => (bool) $request->boolean('is_public')]);
        return redirect()->route('admin.research.index', ['type' => 'book'])->with('success', 'Visibility updated.');
    }

    public function destroy(Book $book)
    {
        // Keep file; just soft-delete
        $book->delete();
        return redirect()->route('admin.research.index', ['type' => 'book'])->with('success', 'Book moved to trash successfully.');
    }

    public function restore($id)
    {
        $book = Book::onlyTrashed()->findOrFail($id);
        $book->restore();
        return redirect()->route('admin.research.index', ['type' => 'book'])->with('success', 'Book restored successfully.');
    }

    public function forceDelete($id)
    {
        $book = Book::onlyTrashed()->findOrFail($id);
        if ($book->file_path && Storage::disk('public')->exists($book->file_path)) {
            Storage::disk('public')->delete($book->file_path);
        }
        $book->forceDelete();
        return redirect()->route('admin.research.index', ['type' => 'book'])->with('success', 'Book permanently deleted.');
    }
}


