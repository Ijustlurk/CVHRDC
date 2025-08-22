<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewsController extends Controller
{
    /**
     * Update the visibility of a news item.
     */
    public function updateVisibility(Request $request, $id)
    {
        try {
            $request->validate([
                'is_visible' => 'required|boolean',
            ]);

            $news = News::findOrFail($id);
            $news->is_visible = $request->input('is_visible') ? 1 : 0;
            $news->save();
            
            $status = $news->is_visible ? 'visible' : 'hidden';
            return redirect()->back()->with('success', "News item is now {$status}!");
            
        } catch (\Exception $e) {
            Log::error('Error updating news visibility: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update news visibility. Please try again.');
        }
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
        
        // Convert Google Drive URL to direct image URL
        $validated['image'] = $this->convertGoogleDriveUrl($validated['image']);
        
        $validated['is_visible'] = $request->has('is_visible') ? 1 : 0;
        $validated['added_by'] = auth()->user() ? auth()->user()->name : 'Unknown';
        
        $news = \App\Models\News::create($validated);
        
        return redirect()->back()->with('success', 'News added successfully!');
    }
    
    /**
     * Convert Google Drive sharing URL to direct image URL
     */
    private function convertGoogleDriveUrl($url)
    {
        if (!$url || !str_contains($url, 'drive.google.com')) {
            return $url;
        }
        
        // Check if it's already a direct image URL or thumbnail URL
        if (str_contains($url, 'drive.google.com/thumbnail') ||
            str_contains($url, 'drive.usercontent.google.com')) {
            return $url;
        }
        
        // Extract file ID from various Google Drive URL formats
        $fileId = null;
        
        // Format: https://drive.google.com/file/d/FILE_ID/view
        if (preg_match('/\/file\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $fileId = $matches[1];
        }
        
        // Format: https://drive.google.com/open?id=FILE_ID
        if (!$fileId && preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $fileId = $matches[1];
        }
        
        // If file ID found, convert to thumbnail URL
        if ($fileId) {
            return "https://drive.google.com/thumbnail?id={$fileId}&sz=s4000";
        }
        
        // If no match found, return original URL
        return $url;
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
        return response()->json($news);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|min:3|max:255',
                'content' => 'required|string|min:10',
                'image' => 'required|url',
                'published_at' => 'required|date',
                'is_visible' => 'nullable|boolean',
            ]);
            
            // Convert Google Drive URL to direct image URL
            $validated['image'] = $this->convertGoogleDriveUrl($validated['image']);
            
            $validated['is_visible'] = $request->has('is_visible') ? 1 : 0;
            
            $news->update($validated);
            
            // Check if it's an AJAX request from the modal
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'News updated successfully!']);
            }
            
            // For regular form submissions, redirect with flash message
            return redirect()->back()->with('success', 'News updated successfully!');
            
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error updating news: ' . $e->getMessage());
            
            // Check if it's an AJAX request from the modal
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to update news. Please try again.'], 500);
            }
            
            // For regular form submissions, redirect with error message
            return redirect()->back()->with('error', 'Failed to update news. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        try {
            $news->delete();
            return redirect()->back()->with('success', 'News deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting news: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete news. Please try again.');
        }
    }
}
