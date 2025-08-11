<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Update the visibility of a news item.
     */
    public function updateVisibility(Request $request, $id)
    {
        $request->validate([
            'is_visible' => 'required|boolean',
        ]);

        $news = News::findOrFail($id);
        $news->is_visible = $request->input('is_visible') ? 1 : 0;
        $news->save();
        return response()->json(['success' => true, 'is_visible' => $news->is_visible]);
    }
    /**
     * Display a listing of the resource.
     */



    public function index(Request $request)
    {
        $news = \App\Models\News::orderBy('id', 'desc')->paginate(8);
        return view('admin.tabs.partials.news', compact('news'));
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
            'title' => 'required|string|min:3|max:255',
            'content' => 'required|string|min:10',
            'image' => 'required|url',
            'published_at' => 'required|date',
            'is_visible' => 'nullable|boolean',
        ]);
        $validated['is_visible'] = $request->has('is_visible') ? 1 : 0;
        $validated['added_by'] = auth()->user() ? auth()->user()->name : 'Unknown';
        $news = \App\Models\News::create($validated);
        return redirect()->back()->with('success', 'News added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        //
    }
}
