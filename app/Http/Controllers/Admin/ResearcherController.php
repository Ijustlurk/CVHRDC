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
            /**
     * Show the form for creating a new resource.
     */
        if (request()->expectsJson()) {
            return response()->json(\App\Models\Researcher::all());
        }
        return view('admin.tabs.partials.researchers');
        {
            //
        }
    }



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
