<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Researcher;
use Illuminate\Http\Request;

class ResearcherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $researchers = \App\Models\Researcher::orderBy('id', 'desc')->paginate(8);
        return view('admin.tabs.partials.researchers', compact('researchers'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'expertise' => 'required|string|min:3|max:255',
            'email' => 'required|email',
        ]);
        $researcher = \App\Models\Researcher::create($validated);
        return redirect()->back()->with('success', 'Researcher added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Researcher $researcher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Researcher $researcher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Researcher $researcher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Researcher $researcher)
    {
        //
    }
}
