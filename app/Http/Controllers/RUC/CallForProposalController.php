<?php

namespace App\Http\Controllers\RUC;

use App\Http\Controllers\Controller;
use App\Models\CallForProposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CallForProposalController extends Controller
{
    /**
     * Display a listing of calls for review.
     */
    public function index(Request $request)
    {
        $query = CallForProposal::with(['creator', 'approver']);

        // Filter based on tab
        switch ($request->tab) {
            case 'for-review':
                $query->whereIn('status', ['For Review', 'Draft']);
                break;
            case 'published':
                $query->where('visibility_status', 'Public');
                break;
            default:
                // Show all calls for 'all-calls' tab or default
                break;
        }

        // Set default tab if not specified
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
                'html' => view('ruc.calls.partials.calls-table', compact('calls'))->render(),
                'pagination' => $calls->links()->render(),
                'totalCount' => $totalCount,
                'currentCount' => $calls->count(),
                'from' => $calls->firstItem() ?? 0,
                'to' => $calls->lastItem() ?? 0,
            ]);
        }

        return view('ruc.calls.index', compact('calls', 'totalCount'));
    }

    /**
     * Display the specified call for review.
     */
    public function show($id)
    {
        $call = CallForProposal::with(['creator', 'approver', 'proposalSubmissions'])->findOrFail($id);

        return view('ruc.calls.show', compact('call'));
    }

    /**
     * Update the visibility status of a call for proposal.
     * This is the main function RUC users can perform - publish/unpublish calls.
     */
    public function updateVisibility(Request $request, $id)
    {
        $call = CallForProposal::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'visibility_status' => 'required|in:Private,Public',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Invalid visibility status. Please try again.');
        }

        $call->update([
            'visibility_status' => $request->visibility_status,
            'approved_by' => $request->visibility_status === 'Public' ? Auth::id() : null,
        ]);

        $action = $request->visibility_status === 'Public' ? 'posted publicly' : 'removed from public view';
        $message = "Call for proposal has been {$action} successfully.";

        return redirect()->back()->with('success', $message);
    }

    /**
     * Update the status of a call for proposal (for review workflow).
     */
    public function updateStatus(Request $request, $id)
    {
        $call = CallForProposal::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Draft,For Review,Open,Closed',
            'action' => 'nullable|in:approve,withdraw,close',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Invalid status or action. Please try again.');
        }

        $status = $request->status;
        $action = $request->action;

        // Handle workflow actions
        if ($action === 'approve' && $status === 'Open') {
            // Approve call: Set to Open and Public
            $call->update([
                'status' => 'Open',
                'visibility_status' => 'Public',
                'approved_by' => Auth::id(),
            ]);

            $message = 'Call approved and published successfully.';
        } elseif ($action === 'withdraw' && $status === 'Closed') {
            // Withdraw/Close call: Set to Closed and Private
            $call->update([
                'status' => 'Closed',
                'visibility_status' => 'Private',
                'approved_by' => Auth::id(),
            ]);

            $message = 'Call withdrawn and removed from public view.';
        } else {
            // Standard status update
            $call->update([
                'status' => $status,
                'approved_by' => Auth::id(),
            ]);

            $message = 'Call status updated successfully.';
        }

        return redirect()->back()->with('success', $message);
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

    /**
     * Get calls that need review.
     */
    public function getForReview()
    {
        $calls = CallForProposal::with(['creator'])
            ->where('status', 'For Review')
            ->orWhere('status', 'Draft')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('ruc.calls.review', compact('calls'));
    }

    /**
     * Get published calls.
     */
    public function getPublished()
    {
        $calls = CallForProposal::with(['creator'])
            ->where('visibility_status', 'Public')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('ruc.calls.published', compact('calls'));
    }
}