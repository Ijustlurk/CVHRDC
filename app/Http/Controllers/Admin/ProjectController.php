<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    /**
     * Update the visibility of a project item.
     */
    public function updateVisibility(Request $request, $id)
    {
        try {
            $request->validate([
                'is_visible' => 'required|boolean',
            ]);

            $project = Project::findOrFail($id);
            $project->is_visible = $request->input('is_visible') ? 1 : 0;
            $project->save();
            
            $status = $project->is_visible ? 'visible' : 'hidden';
            return redirect()->back()->with('success', "Project is now {$status}!");
            
        } catch (\Exception $e) {
            Log::error('Error updating project visibility: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update project visibility. Please try again.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            if ($request->has('all') && $request->input('all')) {
                // Return all projects in descending order by id
                $allProjects = Project::orderBy('id', 'desc')->get();
                return response()->json($allProjects);
            }
            // Otherwise, return 4 recent projects for blade view
            $recentProjects = Project::orderBy('id', 'desc')->take(4)->get();
            return response()->json($recentProjects);
        }
        return view('admin.tabs.partials.projects');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|min:3|max:255',
                'description' => 'required|string|min:10',
                'image' => 'required|url',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'status' => 'required|string|max:100',
                'budget' => 'nullable|numeric|min:0',
                'lead_researcher' => 'required|string|max:255',
                'leader' => 'required|string|max:255',
                'funding_agency' => 'nullable|string|max:255',
                'is_active' => 'required|boolean',
                'is_visible' => 'required|boolean',
            ]);

            // Add the authenticated user's name
            $validated['added_by'] = auth()->user() ? auth()->user()->name : 'Unknown';
            $validated['is_visible'] = $request->has('is_visible') ? 1 : 0;
            // Convert Google Drive URL to direct image URL
            $validated['image'] = $this->convertGoogleDriveUrl($validated['image']);
            $project = Project::create($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Project added successfully!',
                    'data' => $project
                ], 201);
            }

            return redirect()->back()->with('success', 'Project added successfully!');

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
                    'message' => 'An error occurred while adding the project: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'An error occurred while adding the project: ' . $e->getMessage());
        }
    }
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
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return response()->json($project);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|min:3|max:255',
                'description' => 'required|string|min:10',
                'image' => 'required|url',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'status' => 'required|string|max:100',
                'budget' => 'nullable|numeric|min:0',
                'lead_researcher' => 'required|string|max:255',
                'leader' => 'required|string|max:255',
                'funding_agency' => 'nullable|string|max:255',
                'is_active' => 'nullable|boolean',
                'is_visible' => 'nullable|boolean',
            ]);
            
            // Convert Google Drive URL to direct image URL
            $validated['image'] = $this->convertGoogleDriveUrl($validated['image']);
            
            $validated['is_active'] = $request->has('is_active') ? 1 : 0;
            $validated['is_visible'] = $request->has('is_visible') ? 1 : 0;
            
            $project->update($validated);
            
            // Check if it's an AJAX request from the modal
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Project updated successfully!']);
            }
            
            // For regular form submissions, redirect with flash message
            return redirect()->back()->with('success', 'Project updated successfully!');
            
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error updating project: ' . $e->getMessage());
            
            // Check if it's an AJAX request from the modal
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to update project. Please try again.'], 500);
            }
            
            // For regular form submissions, redirect with error message
            return redirect()->back()->with('error', 'Failed to update project. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        try {
            $project->delete();
            return redirect()->back()->with('success', 'Project deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting project: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete project. Please try again.');
        }
    }
}
