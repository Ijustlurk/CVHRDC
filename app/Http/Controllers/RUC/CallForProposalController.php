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

        $calls = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('ruc.calls.index', compact('calls'));
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
            return response()->json([
                'success' => false,
                'message' => 'Invalid visibility status.'
            ], 422);
        }

        $call->update([
            'visibility_status' => $request->visibility_status,
            'approved_by' => Auth::id(),
        ]);

        $action = $request->visibility_status === 'Public' ? 'published' : 'unpublished';

        return response()->json([
            'success' => true,
            'message' => "Call for proposal {$action} successfully.",
            'visibility_status' => $call->visibility_status,
            'approved_by' => $call->approver->name ?? 'N/A'
        ]);
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
            return response()->json([
                'success' => false,
                'message' => 'Invalid status or action.'
            ], 422);
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

        return response()->json([
            'success' => true,
            'message' => $message,
            'status' => $call->status,
            'visibility_status' => $call->visibility_status,
            'approved_by' => $call->approver->name ?? 'N/A'
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

