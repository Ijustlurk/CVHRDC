<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Partner::query();

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('organization', 'like', "%{$search}%")
                  ->orWhere('contact_email', 'like', "%{$search}%")
                  ->orWhere('contact_phone', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('website', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Apply sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'a_z':
                $query->orderBy('name', 'asc');
                break;
            case 'z_a':
                $query->orderBy('name', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Handle different request types
        if ($request->expectsJson()) {
            // For AJAX search requests, paginate and return JSON
            $partners = $query->paginate(8)->appends($request->except('page'));
            $totalCount = $partners->total();

            return response()->json([
                'html' => view('admin.tabs.partials.partners-cards', compact('partners'))->render(),
                'pagination' => $partners->links()->render(),
                'totalCount' => $totalCount,
                'currentCount' => $partners->count(),
                'from' => $partners->firstItem() ?? 0,
                'to' => $partners->lastItem() ?? 0,
            ]);
        }

        // For regular page requests (including refreshes with query params), redirect to AdminTabsController
        return redirect()->route('admin.tabs.index', array_merge(['tab' => 'partners'], $request->query()));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Update the visibility of a partner item.
     */
    public function updateVisibility(Request $request, $id)
    {
        try {
            $request->validate([
                'is_active' => 'required|boolean',
            ]);

            $partner = Partner::findOrFail($id);
            $partner->is_active = $request->input('is_active') ? 1 : 0;
            $partner->save();

            $status = $partner->is_active ? 'active' : 'inactive';
            return redirect()->back()->with('successvisibility', "Partner is now {$status}!");

        } catch (\Exception $e) {
            Log::error('Error updating partner visibility: ' . $e->getMessage());
            return redirect()->back()->with('errorvisibility', 'Failed to update partner visibility. Please try again.');
        }
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
        return response()->json($partner);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Partner $partner)
    {
        try {
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

            $partner->update($validated);

            // Check if it's an AJAX request from the modal
            if ($request->ajax()) {
                return response()->json(['successupdate' => true, 'message' => 'Partner updated successfully!']);
            }

            // For regular form submissions, redirect with flash message
            return redirect()->back()->with('successupdate', 'Partner updated successfully!');

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error updating partner: ' . $e->getMessage());

            // Check if it's an AJAX request from the modal
            if ($request->ajax()) {
                return response()->json(['errorupdate' => false, 'message' => 'Failed to update partner. Please try again.'], 500);
            }

            // For regular form submissions, redirect with error message
            return redirect()->back()->with('errorupdate', 'Failed to update partner. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partner $partner)
    {
        try {
            $partner->delete();
            return redirect()->back()->with('successdelete', 'Partner deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting partner: ' . $e->getMessage());
            return redirect()->back()->with('errordelete', 'Failed to delete partner. Please try again.');
        }
    }
}
