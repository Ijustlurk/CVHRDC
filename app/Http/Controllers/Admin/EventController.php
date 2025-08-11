<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Event;
use Illuminate\Http\Request;

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

            return response()->json([
                'success' => true,
                'message' => 'Event visibility updated successfully!',
                'is_visible' => $event->is_visible
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating visibility: ' . $e->getMessage()
            ], 500);
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
        $event = \App\Models\Event::create($validated);
        return redirect()->back()->with('success', 'Event added successfully!');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //
    }
}
