<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Publication;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    /**
     * Update the visibility of a publication item.
     */
    public function updateVisibility(Request $request, $id)
    {
        $request->validate([
            'is_visible' => 'required|boolean',
        ]);

        $publication = Publication::findOrFail($id);
        $publication->is_visible = $request->boolean('is_visible') ? 1 : 0;
        $publication->save();

        return response()->json(['success' => true, 'is_visible' => $publication->is_visible]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            if ($request->has('all') && $request->input('all')) {
                $allPublications = Publication::orderBy('id', 'desc')->get();
                return response()->json($allPublications);
            }
            $recentPublications = Publication::orderBy('id', 'desc')->take(4)->get();
            return response()->json($recentPublications);
        }

        return view('admin.tabs.partials.publications');
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
        try {
            $validated = $request->validate([
                'title' => 'required|string|min:3|max:255',
                'description' => 'required|string|min:10',
                'author' => 'nullable|string|max:255',
                'published_at' => 'nullable|date',
                'type' => 'nullable|string|max:100',
                'file_url' => [
                    'required',
                    'url',
                    // Ensure it's some form of Google link
                    'regex:/^(https?:\\/\\/)?(drive\\.google\\.com|docs\\.google\\.com|drive\\.usercontent\\.google\\.com)\//i',
                ],
                'tags' => 'nullable|string|max:255',
                'is_visible' => 'required|boolean',
            ]);

            // Associate with the authenticated user if available
            if (auth()->check()) {
                $validated['user_id'] = auth()->id();
            }

            $publication = Publication::create($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Publication added successfully!',
                    'data' => $publication,
                ], 201);
            }

            return redirect()->back()->with('success', 'Publication added successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }

            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while adding the publication: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->back()->with('error', 'An error occurred while adding the publication: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Publication $publication)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Publication $publication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publication $publication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publication $publication)
    {
        //
    }
}
