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
    public function index(Request $request)
    {
        $query = Event::query();

        // Search by title, description, location, or organizer
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%')
                    ->orWhere('organizer', 'like', '%' . $search . '%');
            });
        }

        // Time-based filters
        $timeFilter = $request->input('time_filter', 'upcoming'); // Default to upcoming
        $today = now()->startOfDay();

        switch ($timeFilter) {
            case 'upcoming':
                // Events where start date is today or later
                $query->where('start_date', '>=', $today);
                break;
            case 'past':
                // Events where end date is earlier than today (or start date if no end date)
                $query->where(function ($q) use ($today) {
                    $q->where('end_date', '<', $today)
                        ->orWhere(function ($subQ) use ($today) {
                            $subQ->whereNull('end_date')
                                ->where('start_date', '<', $today);
                        });
                });
                break;
            case 'all':
                // No time restriction
                break;
        }

        // Custom date range (overrides time filter)
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $dateFrom = $request->input('date_from');
            $dateTo = $request->input('date_to');
            
            $query->where(function ($q) use ($dateFrom, $dateTo) {
                // Event starts within the range OR event spans across the range
                $q->whereBetween('start_date', [$dateFrom, $dateTo])
                    ->orWhere(function ($subQ) use ($dateFrom, $dateTo) {
                        $subQ->where('start_date', '<=', $dateTo)
                            ->where(function ($endQ) use ($dateFrom) {
                                $endQ->where('end_date', '>=', $dateFrom)
                                    ->orWhereNull('end_date');
                            });
                    });
            });
        } elseif ($request->filled('date_from')) {
            $query->where('start_date', '>=', $request->input('date_from'));
        } elseif ($request->filled('date_to')) {
            $query->where('start_date', '<=', $request->input('date_to'));
        }

        // Visibility filter
        if ($request->filled('visibility')) {
            $visibility = $request->input('visibility');
            if ($visibility === 'visible') {
                $query->where('is_visible', 1);
            } elseif ($visibility === 'hidden') {
                $query->where('is_visible', 0);
            }
        }

        // Sorting logic
        $sort = $request->input('sort', 'auto');
        
        if ($sort === 'auto') {
            // Automatic sorting based on time filter
            switch ($timeFilter) {
                case 'upcoming':
                    $query->orderBy('start_date', 'asc'); // Soonest first
                    break;
                case 'past':
                    $query->orderBy('start_date', 'desc'); // Most recent first
                    break;
                case 'all':
                default:
                    $query->orderBy('start_date', 'desc'); // Most recent first
                    break;
            }
        } else {
            // Manual sorting
            switch ($sort) {
                case 'date_asc':
                    $query->orderBy('start_date', 'asc');
                    break;
                case 'date_desc':
                    $query->orderBy('start_date', 'desc');
                    break;
                case 'title_asc':
                    $query->orderBy('title', 'asc');
                    break;
                case 'title_desc':
                    $query->orderBy('title', 'desc');
                    break;
                default:
                    $query->orderBy('start_date', 'desc');
                    break;
            }
        }

        // Handle AJAX requests
        if ($request->expectsJson()) {
            $events = $query->paginate(8)->appends($request->except('page'));
            $totalCount = $events->total();

            return response()->json([
                'html' => view('admin.tabs.partials.event-cards', compact('events'))->render(),
                'pagination' => $events->links()->render(),
                'totalCount' => $totalCount,
                'currentCount' => $events->count(),
                'from' => $events->firstItem() ?? 0,
                'to' => $events->lastItem() ?? 0,
            ]);
        }

        // For regular page requests (including refreshes with query params), redirect to AdminTabsController
        return redirect()->route('admin.tabs.index', array_merge(['tab' => 'events'], $request->query()));
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
