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
            return redirect()->back()->with('successvisibility', "Project is now {$status}!");

        } catch (\Exception $e) {
            Log::error('Error updating project visibility: ' . $e->getMessage());
            return redirect()->back()->with('errorvisibility', 'Failed to update project visibility. Please try again.');
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
        $query = Project::query();

        // Search by title, description, lead_researcher, leader, or funding_agency
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('lead_researcher', 'like', '%' . $search . '%')
                    ->orWhere('leader', 'like', '%' . $search . '%')
                    ->orWhere('funding_agency', 'like', '%' . $search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status_filter')) {
            $status = $request->input('status_filter');
            $query->where('status', 'like', '%' . $status . '%');
        }

        // Filter by visibility
        if ($request->filled('visibility')) {
            $visibility = $request->input('visibility');
            if ($visibility === 'visible') {
                $query->where('is_visible', 1);
            } elseif ($visibility === 'hidden') {
                $query->where('is_visible', 0);
            }
        }

        // Filter by active status
        if ($request->filled('active_status')) {
            $activeStatus = $request->input('active_status');
            if ($activeStatus === 'active') {
                $query->where('is_active', 1);
            } elseif ($activeStatus === 'inactive') {
                $query->where('is_active', 0);
            }
        }

        // Filter by lead researcher
        if ($request->filled('lead_researcher_filter')) {
            $leadResearcher = $request->input('lead_researcher_filter');
            $query->where('lead_researcher', 'like', '%' . $leadResearcher . '%');
        }

        // Filter by date range
        if ($request->filled('date_filter')) {
            $dateFilter = $request->input('date_filter');
            $now = now();

            switch ($dateFilter) {
                case 'last_7_days':
                    $query->where('start_date', '>=', $now->subDays(7));
                    break;
                case 'last_30_days':
                    $query->where('start_date', '>=', $now->subDays(30));
                    break;
                case 'last_6_months':
                    $query->where('start_date', '>=', $now->subMonths(6));
                    break;
                case 'custom':
                    if ($request->filled('date_from')) {
                        $query->where('start_date', '>=', $request->input('date_from'));
                    }
                    if ($request->filled('date_to')) {
                        $query->where('start_date', '<=', $request->input('date_to'));
                    }
                    break;
            }
        }

        // Filter by budget range
        if ($request->filled('budget_min')) {
            $query->where('budget', '>=', $request->input('budget_min'));
        }
        if ($request->filled('budget_max')) {
            $query->where('budget', '<=', $request->input('budget_max'));
        }

        // Filter by funding agency
        if ($request->filled('funding_agency_filter')) {
            $fundingAgency = $request->input('funding_agency_filter');
            $query->where('funding_agency', 'like', '%' . $fundingAgency . '%');
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('start_date', 'asc');
                break;
            case 'a_z':
                $query->orderBy('title', 'asc');
                break;
            case 'z_a':
                $query->orderBy('title', 'desc');
                break;
            case 'budget_high':
                $query->orderBy('budget', 'desc');
                break;
            case 'budget_low':
                $query->orderBy('budget', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('start_date', 'desc');
                break;
        }

        // Handle different request types
        if ($request->expectsJson()) {
            if ($request->has('all') && $request->input('all')) {
                // Return all projects with filters applied
                $allProjects = $query->get();
                return response()->json($allProjects);
            }
            
            // For AJAX search requests, paginate and return JSON
            $projects = $query->paginate(8)->appends($request->except('page'));
            $totalCount = $projects->total();

            return response()->json([
                'html' => view('admin.tabs.partials.project-cards', compact('projects'))->render(),
                'pagination' => $projects->links()->render(),
                'totalCount' => $totalCount,
                'currentCount' => $projects->count(),
                'from' => $projects->firstItem() ?? 0,
                'to' => $projects->lastItem() ?? 0,
            ]);
        }

        // For regular page requests (including refreshes with query params), redirect to AdminTabsController
        return redirect()->route('admin.tabs.index', array_merge(['tab' => 'projects'], $request->query()));
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
            $validated['is_active'] = $request->boolean('is_active') ? 1 : 0;
            $validated['is_visible'] = $request->boolean('is_visible') ? 1 : 0;
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

            $validated['is_active'] = $request->boolean('is_active') ? 1 : 0;
            $validated['is_visible'] = $request->boolean('is_visible') ? 1 : 0;

            $project->update($validated);

            // Check if it's an AJAX request from the modal
            if ($request->ajax()) {
                return response()->json(['successupdate' => true, 'message' => 'Project updated successfully!']);
            }

            // For regular form submissions, redirect with flash message
            return redirect()->back()->with('successupdate', 'Project updated successfully!');

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error updating project: ' . $e->getMessage());

            // Check if it's an AJAX request from the modal
            if ($request->ajax()) {
                return response()->json(['errorupdate' => false, 'message' => 'Failed to update project. Please try again.'], 500);
            }

            // For regular form submissions, redirect with error message
            return redirect()->back()->with('errorupdate', 'Failed to update project. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        try {
            $project->delete();
            return redirect()->back()->with('successdelete', 'Project deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting project: ' . $e->getMessage());
            return redirect()->back()->with('errordelete', 'Failed to delete project. Please try again.');
        }
    }
}
