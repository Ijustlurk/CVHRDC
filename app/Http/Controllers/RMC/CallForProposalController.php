<?php

namespace App\Http\Controllers\RMC;

use App\Http\Controllers\Controller;
use App\Models\CallForProposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CallForProposalController extends Controller
{
    /**
     * Display a listing of the calls for proposals.
     */
    public function index(Request $request)
    {
        $query = CallForProposal::with(['creator', 'approver']);

        // Filter by user's calls if "my-calls" tab is active
        if ($request->tab === 'my-calls') {
            $query->where('created_by', Auth::id());
        }
        // Default to "all-calls" if no tab specified
        if (!$request->has('tab')) {
            $request->merge(['tab' => 'all-calls']);
        }

        // Search by title, research priority area, or description
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('research_priority_area', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $status = $request->input('status');
            $query->where('status', $status);
        }

        // Filter by visibility status
        if ($request->filled('visibility')) {
            $visibility = $request->input('visibility');
            $query->where('visibility_status', $visibility);
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
                        $query->where('end_date', '<=', $request->input('date_to'));
                    }
                    break;
            }
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
            case 'funding_high':
                $query->orderBy('funding_limit', 'desc');
                break;
            case 'funding_low':
                $query->orderBy('funding_limit', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Paginate results (get total count for display)
        $calls = $query->paginate(12)->appends($request->except('page'));
        $totalCount = $calls->total();

        // If AJAX request (for real-time search), return JSON
        if ($request->ajax()) {
            return response()->json([
                'html' => view('rmc.calls.partials.calls-table', compact('calls'))->render(),
                'pagination' => $calls->links()->render(),
                'totalCount' => $totalCount,
                'currentCount' => $calls->count(),
                'from' => $calls->firstItem() ?? 0,
                'to' => $calls->lastItem() ?? 0,
            ]);
        }

        return view('rmc.calls.index', compact('calls', 'totalCount'));
    }

    /**
     * Show the form for creating a new call for proposal.
     */
    public function create()
    {
        return view('rmc.calls.create');
    }

    /**
     * Store a newly created call for proposal in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'research_priority_area' => 'required|string|max:255',
            'eligibility' => 'required|string',
            'guidelines' => 'required|string',
            'funding_limit' => 'required|numeric|min:0',
            'proposal_format_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'nullable|in:Draft,For Review,Open,Closed',
            'visibility_status' => 'nullable|in:Private,Public',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validation errors occurred. Please check the form and try again.');
        }

        $data = $request->all();
        $data['created_by'] = Auth::id();

        // Set defaults for new calls
        if (!isset($data['status'])) {
            $data['status'] = 'For Review';
        }
        if (!isset($data['visibility_status'])) {
            $data['visibility_status'] = 'Private';
        }

        // Handle file upload
        if ($request->hasFile('proposal_format_file')) {
            $file = $request->file('proposal_format_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('proposal_formats', $filename, 'public');
            $data['proposal_format_file'] = $path;
        }

        $call = CallForProposal::create($data);

        return redirect()->route('rmc.calls.show', $call->id)
            ->with('success', 'Call for proposal created successfully and submitted for review.');
    }

    /**
     * Display the specified call for proposal.
     */
    public function show($id)
    {
        $call = CallForProposal::with(['creator', 'approver', 'proposalSubmissions'])->findOrFail($id);

        return view('rmc.calls.show', compact('call'));
    }

    /**
     * Show the form for editing the specified call for proposal.
     */
    public function edit($id)
    {
        $call = CallForProposal::findOrFail($id);

        // Ensure only the creator can edit
        if ($call->created_by !== Auth::id()) {
            abort(403, 'Unauthorized. You can only edit calls you created.');
        }

        // Prevent editing of publicly posted calls
        if ($call->visibility_status === 'Public') {
            abort(403, 'Cannot edit calls that have been posted publicly. Please contact RUC to make changes.');
        }

        return view('rmc.calls.edit', compact('call'));
    }

    /**
     * Update the specified call for proposal in storage.
     */
    public function update(Request $request, $id)
    {
        $call = CallForProposal::findOrFail($id);

        // Ensure only the creator can edit
        if ($call->created_by !== Auth::id()) {
            return redirect()->back()
                ->with('error', 'Unauthorized. You can only edit calls you created.');
        }

        // Prevent editing of publicly posted calls
        if ($call->visibility_status === 'Public') {
            return redirect()->back()
                ->with('error', 'Cannot edit calls that have been posted publicly. Please contact RUC to make changes.');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'research_priority_area' => 'required|string|max:255',
            'eligibility' => 'required|string',
            'guidelines' => 'required|string',
            'funding_limit' => 'required|numeric|min:0',
            'proposal_format_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'nullable|in:Draft,For Review,Open,Closed',
            'visibility_status' => 'nullable|in:Private,Public',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validation errors occurred. Please check the form and try again.');
        }

        $data = $request->all();

        // Handle file upload
        if ($request->hasFile('proposal_format_file')) {
            // Delete old file if exists
            if ($call->proposal_format_file) {
                Storage::disk('public')->delete($call->proposal_format_file);
            }

            $file = $request->file('proposal_format_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('proposal_formats', $filename, 'public');
            $data['proposal_format_file'] = $path;
        }

        $call->update($data);

        return redirect()->route('rmc.calls.show', $call->id)
            ->with('success', 'Call for proposal updated successfully.');
    }

    /**
     * Remove the specified call for proposal from storage.
     */
    public function destroy($id)
    {
        $call = CallForProposal::findOrFail($id);

        // Ensure only the creator can delete
        if ($call->created_by !== Auth::id()) {
            return redirect()->back()
                ->with('error', 'Unauthorized. You can only delete calls you created.');
        }

        // Prevent deletion of publicly posted calls
        if ($call->visibility_status === 'Public') {
            return redirect()->back()
                ->with('error', 'Cannot delete calls that have been posted publicly. Please contact RUC to remove from public view first.');
        }

        // Delete associated file if exists
        if ($call->proposal_format_file) {
            Storage::disk('public')->delete($call->proposal_format_file);
        }

        $call->delete();

        return redirect()->route('rmc.calls.index')
            ->with('success', 'Call for proposal deleted successfully.');
    }

    /**
     * Update the visibility status of a call for proposal.
     */
    public function updateVisibility(Request $request, $id)
    {
        $call = CallForProposal::findOrFail($id);

        // Ensure only the creator can update visibility
        if ($call->created_by !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You can only update visibility of calls you created.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'visibility_status' => 'required|in:Private,Public',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid visibility status.'
            ], 422);
        }

        $call->update(['visibility_status' => $request->visibility_status]);

        return response()->json([
            'success' => true,
            'message' => 'Visibility status updated successfully.',
            'visibility_status' => $call->visibility_status
        ]);
    }

    /**
     * Download the proposal format file.
     */
    public function downloadFormatFile($id)
    {
        $call = CallForProposal::findOrFail($id);

        if (!$call->proposal_format_file) {
            abort(404, 'Proposal format file not found.');
        }

        return Storage::disk('public')->download($call->proposal_format_file);
    }
}