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

                // Return all news in descending order by id
                $allNews = News::orderBy('id', 'desc')->get();
                return response()->json($allNews);
            }
            // Otherwise, return 4 recent news for blade view
            $recentNews = News::orderBy('id', 'desc')->take(4)->get();
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
        try {
            $validated = $request->validate([
                'title' => 'required|string|min:3|max:255',
                'content' => 'required|string|min:10',
                'image' => 'required|url',
                'published_at' => 'required|date',
                'is_visible' => 'required|boolean',
            ]);

            // Add the authenticated user's name
            $validated['added_by'] = auth()->user() ? auth()->user()->name : 'Unknown';

            $news = News::create($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'News added successfully!',
                    'data' => $news
                ], 201);
            }

            return redirect()->back()->with('success', 'News added successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while adding the news: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'An error occurred while adding the news: ' . $e->getMessage());
        }
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
