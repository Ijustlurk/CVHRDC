<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Update the visibility of a project item.
     */
    public function updateVisibility(Request $request, $id)
    {
        $request->validate([
            'is_visible' => 'required|boolean',
        ]);

        $project = Project::findOrFail($id);
        $project->is_visible = $request->input('is_visible') ? 1 : 0;
        $project->save();
        return response()->json(['success' => true, 'is_visible' => $project->is_visible]);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }
}
