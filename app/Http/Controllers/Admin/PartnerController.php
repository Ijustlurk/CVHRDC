<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
     * Show the form for creating a new resource.
     */
    public function index()
    {
        $partners = \App\Models\Partner::orderBy('id', 'desc')->paginate(8);
        return view('admin.tabs.partials.partners', compact('partners'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'organization' => 'required|string|min:3|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'is_active' => 'nullable|boolean',
        ]);
        
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        
        $partner = \App\Models\Partner::create($validated);
        return redirect()->back()->with('success', 'Partner added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Partner $partner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Partner $partner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Partner $partner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partner $partner)
    {
        //
    }
}
