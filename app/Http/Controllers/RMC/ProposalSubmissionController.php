<?php

namespace App\Http\Controllers\RMC;

use App\Http\Controllers\Controller;
use App\Models\CallForProposal;
use App\Models\ProposalSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProposalSubmissionController extends Controller
{
    /**
     * Display all submissions for calls created by the current user.
     */
    public function index()
    {
        // Get all calls created by the current user
        $calls = CallForProposal::where('created_by', Auth::id())->pluck('id');

        if ($calls->isEmpty()) {
            // If user hasn't created any calls, show empty state
            return view('rmc.submissions.index', [
                'calls' => collect(),
                'submissions' => collect(),
                'totalSubmissions' => 0
            ]);
        }

        // Get all submissions for the user's calls
        $submissions = ProposalSubmission::whereIn('call_id', $calls)
            ->with('callForProposal')
            ->orderBy('date_submitted', 'desc')
            ->paginate(12);

        return view('rmc.submissions.index', compact('calls', 'submissions'));
    }

    /**
     * Display submissions for a specific call.
     */
    public function showCallSubmissions($callId)
    {
        $call = CallForProposal::with('proposalSubmissions')->findOrFail($callId);

        // Ensure only the creator can view submissions for their calls
        if ($call->created_by !== Auth::id()) {
            abort(403, 'Unauthorized. You can only view submissions for calls you created.');
        }

        $submissions = $call->proposalSubmissions()->orderBy('date_submitted', 'desc')->paginate(12);

        return view('rmc.submissions.call_submissions', compact('call', 'submissions'));
    }

    /**
     * Display the specified submission.
     */
    public function show($callId, $submissionId)
    {
        $call = CallForProposal::findOrFail($callId);

        // Ensure only the creator can view submissions for their calls
        if ($call->created_by !== Auth::id()) {
            abort(403, 'Unauthorized. You can only view submissions for calls you created.');
        }

        $submission = $call->proposalSubmissions()->findOrFail($submissionId);

        return view('rmc.submissions.show', compact('call', 'submission'));
    }

    /**
     * Update the status of a submission.
     */
    public function updateStatus(Request $request, $callId, $submissionId)
    {
        $call = CallForProposal::findOrFail($callId);

        // Ensure only the creator can update submission status for their calls
        if ($call->created_by !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You can only update submissions for calls you created.'
            ], 403);
        }

        $submission = $call->proposalSubmissions()->findOrFail($submissionId);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Submitted,Under Review,Accepted,Rejected',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors()
            ], 422);
        }

        $submission->update([
            'status' => $request->status,
            'remarks' => $request->remarks,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Submission status updated successfully.',
            'data' => $submission
        ]);
    }

    /**
     * Download the proposal document.
     */
    public function downloadDocument($callId, $submissionId)
    {
        $call = CallForProposal::findOrFail($callId);

        // Ensure only the creator can download submissions for their calls
        if ($call->created_by !== Auth::id()) {
            abort(403, 'Unauthorized. You can only download submissions for calls you created.');
        }

        $submission = $call->proposalSubmissions()->findOrFail($submissionId);

        if (!$submission->proposal_document) {
            abort(404, 'Proposal document not found.');
        }

        return Storage::disk('public')->download($submission->proposal_document);
    }
}

