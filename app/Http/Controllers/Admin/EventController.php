<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    /**
     * Update the visibility of an event item.
     */
    public function updateVisibility(Request $request, $id)
    {
        try {
            $request->validate([
                'is_visible' => 'required|boolean',
            ]);

            $event = Event::where('id', $id)->firstOrFail();
            $event->is_visible = $request->input('is_visible') ? 1 : 0;
            $event->save();

            $status = $event->is_visible ? 'visible' : 'hidden';
            return redirect()->back()->with('successvisibility', "Event is now {$status}!");

        } catch (\Exception $e) {
            Log::error('Error updating event visibility: ' . $e->getMessage());
            return redirect()->back()->with('errorvisibility', 'Failed to update event visibility. Please try again.');
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = \App\Models\Event::orderBy('id', 'desc')->paginate(8);
        return view('admin.tabs.partials.events', compact('events'));
    }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    //     if (request()->expectsJson()) {
    //         return response()->json(\App\Models\Event::all());
    //     }
    //     return view('admin.tabs.partials.events');
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:10',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'organizer' => 'required|string|max:255',
            'image_url' => 'required|url',
            'is_visible' => 'nullable|boolean',
        ]);
        $validated['is_visible'] = $request->has('is_visible') ? 1 : 0;
        $validated['added_by'] = auth()->user() ? auth()->user()->name : 'Unknown';
        $validated['image_url'] = $this->convertGoogleDriveUrl($validated['image_url']);
        $event = \App\Models\Event::create($validated);
        return redirect()->back()->with('success', 'Event added successfully!');
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
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        return response()->json($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|min:3|max:255',
                'description' => 'required|string|min:10',
                'location' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'organizer' => 'required|string|max:255',
                'image_url' => 'required|url',
                'is_visible' => 'nullable|boolean',
            ]);

            // Convert Google Drive URL to direct image URL
            $validated['image_url'] = $this->convertGoogleDriveUrl($validated['image_url']);

            $validated['is_visible'] = $request->has('is_visible') ? 1 : 0;

            $event->update($validated);

            // Check if it's an AJAX request from the modal
            if ($request->ajax()) {
                return response()->json(['successupdate' => true, 'message' => 'Event updated successfully!']);
            }

            // For regular form submissions, redirect with flash message
            return redirect()->back()->with('errorupdate', 'Event updated successfully!');

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error updating event: ' . $e->getMessage());

            // Check if it's an AJAX request from the modal
            if ($request->ajax()) {
                return response()->json(['errorupdate' => false, 'message' => 'Failed to update event. Please try again.'], 500);
            }

            // For regular form submissions, redirect with error message
            return redirect()->back()->with('errorupdate', 'Failed to update event. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        try {
            $event->delete();
            return redirect()->back()->with('successdelete', 'Event deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting event: ' . $e->getMessage());
            return redirect()->back()->with('errordelete', 'Failed to delete event. Please try again.');
        }
    }
}
