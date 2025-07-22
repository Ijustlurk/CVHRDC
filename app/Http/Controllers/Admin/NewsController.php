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
        if ($request->expectsJson()) {
            if ($request->has('all') && $request->input('all')) {
                $allNews = News::orderBy('created_at', 'desc')->get();
                return response()->json($allNews);
            }
            // Otherwise, return 3 recent
            $recentNews = News::orderBy('created_at', 'desc')->take(3)->get();
            return response()->json($recentNews);
        }
        // ...existing code...

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
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|string',
            'published_at' => 'nullable|date',
            'is_visible' => 'nullable|boolean',
        ]);

        $news = News::create($validated);
        return response()->json($news, 201);
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
