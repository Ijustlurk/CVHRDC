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
        $request->validate([
            'is_visible' => 'required|boolean',
        ]);
        $event = Event::where('id', $id)->firstOrFail();
        $event->is_visible = $request->input('is_visible') ? 1 : 0;
        $event->save();
        return response()->json(['success' => true, 'is_visible' => $event->is_visible]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $request = request();
        if ($request->expectsJson()) {
            if ($request->has('all') && $request->input('all')) {
                $allEvents = Event::orderBy('id', 'desc')->get();
                return response()->json($allEvents);
            }
            $recentEvents = Event::orderBy('id', 'desc')->take(4)->get();
            return response()->json($recentEvents);
        }
        return view('admin.tabs.partials.events');
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
        //
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
