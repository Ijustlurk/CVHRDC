<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\CallForProposal;
use App\Models\ProposalSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserProposalSubmissionController extends Controller
{
    /**
     * Display a listing of the user's proposal submissions.
     */
    public function index()
    {
        $submissions = ProposalSubmission::where('user_id', Auth::id())
            ->with('callForProposal')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('users.proposal-submissions.index', compact('submissions'));
    }

    /**
     * Show the form for creating a new proposal submission.
     */
    public function create()
    {
        // Get all calls that are currently accepting submissions
        $availableCalls = CallForProposal::where('visibility_status', 'Public')
            ->where('status', 'Open')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('end_date')
            ->get();

        return view('users.proposal-submissions.create', compact('availableCalls'));
    }

    /**
     * Store a newly created proposal submission.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'call_id' => ['required', 'exists:call_for_proposals,id'],
            'research_title' => ['required', 'string', 'max:255'],
            'proponent_name' => ['required', 'string', 'max:255'],
            'affiliation' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:100'],
            'contact_number' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\(\)\s]+$/'],
            'proposal_document' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'], // 10MB max
            'budget_requested' => ['required', 'numeric', 'min:0', 'max:999999999.99'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please correct the errors below.');
        }

        // Verify the call is still accepting submissions
        $call = CallForProposal::findOrFail($request->call_id);
        if (!$call->isAcceptingSubmissions()) {
            return back()->with('error', 'This call is no longer accepting submissions.');
        }

        try {
            // Store the uploaded file
            $file = $request->file('proposal_document');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\-_.]/', '', $file->getClientOriginalName());
            $path = $file->storeAs('proposal_submissions', $filename, 'public');

            // Create the proposal submission
            $submission = ProposalSubmission::create([
                'call_id' => $request->call_id,
                'user_id' => Auth::id(),
                'research_title' => $request->research_title,
                'proponent_name' => $request->proponent_name,
                'affiliation' => $request->affiliation,
                'email' => $request->email,
                'contact_number' => $request->contact_number,
                'proposal_document' => $path,
                'budget_requested' => $request->budget_requested,
                'date_submitted' => now(),
                'status' => 'Submitted',
            ]);

            return redirect()
                ->route('users.proposal-submissions.show', $submission->id)
                ->with('success', 'Your proposal has been submitted successfully!');

        } catch (\Exception $e) {
            // If file upload fails, return error
            return back()
                ->with('error', 'Failed to upload proposal document. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified proposal submission.
     */
    public function show(ProposalSubmission $proposalSubmission)
    {
        // Ensure user can only view their own submissions
        if ($proposalSubmission->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to proposal submission.');
        }

        $proposalSubmission->load('callForProposal');

        return view('users.proposal-submissions.show', compact('proposalSubmission'));
    }

    /**
     * Download the proposal document.
     */
    public function download(ProposalSubmission $proposalSubmission)
    {
        // Ensure user can only download their own submission documents
        if ($proposalSubmission->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to proposal document.');
        }

        if (!$proposalSubmission->proposal_document || !Storage::disk('public')->exists($proposalSubmission->proposal_document)) {
            return back()->with('error', 'Proposal document not found.');
        }

        // Get the original filename (remove timestamp prefix)
        $originalName = preg_replace('/^[0-9]+_/', '', basename($proposalSubmission->proposal_document));

        return Storage::disk('public')->download(
            $proposalSubmission->proposal_document,
            $originalName
        );
    }
}
