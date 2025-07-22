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
                $allProjects = Project::orderBy('created_at', 'desc')->get();
                return response()->json($allProjects);
            }
            // Otherwise, return 3 recent
            $recentProjects = Project::orderBy('created_at', 'desc')->take(3)->get();
            return response()->json($recentProjects);
        }
        return view('admin.tabs.partials.projects');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|string',
            'started_at' => 'nullable|date',
            'is_visible' => 'nullable|boolean',
        ]);

        $project = Project::create($validated);
        return response()->json($project, 201);
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
