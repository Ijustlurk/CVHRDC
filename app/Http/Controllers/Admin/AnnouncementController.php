<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcements = Announcement::orderBy('id', 'desc')->paginate(8);
        return view('admin.tabs.partials.announcement', compact('announcements'));
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
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'active' => 'nullable|boolean',
        ]);
        
        $validated['active'] = $request->has('active') ? 1 : 0;
        
        $announcement = Announcement::create($validated);
        return redirect()->back()->with('success', 'Announcement added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Announcement $announcement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        return response()->json($announcement);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|min:3|max:255',
                'content' => 'required|string|min:10',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'active' => 'nullable|boolean',
            ]);
            
            $validated['active'] = $request->has('active') ? 1 : 0;
            
            $announcement->update($validated);
            
            // Check if it's an AJAX request from the modal
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Announcement updated successfully!']);
            }
            
            // For regular form submissions, redirect with flash message
            return redirect()->back()->with('success', 'Announcement updated successfully!');
            
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error updating announcement: ' . $e->getMessage());
            
            // Check if it's an AJAX request from the modal
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to update announcement. Please try again.'], 500);
            }
            
            // For regular form submissions, redirect with error message
            return redirect()->back()->with('error', 'Failed to update announcement. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        try {
            $announcement->delete();
            return redirect()->back()->with('success', 'Announcement deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting announcement: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete announcement. Please try again.');
        }
    }

    /**
     * Update the visibility of an announcement.
     */
    public function updateVisibility(Request $request, $id)
    {
        $request->validate([
            'active' => 'required|boolean',
        ]);

        $announcement = Announcement::findOrFail($id);
        $announcement->active = $request->input('active') ? 1 : 0;
        $announcement->save();
        return response()->json(['success' => true, 'active' => $announcement->active]);
    }
}
