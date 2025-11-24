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
    public function index(Request $request)
    {
        $query = ProposalSubmission::where('user_id', Auth::id())
            ->with('callForProposal');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('research_title', 'like', "%{$search}%")
                  ->orWhere('proponent_name', 'like', "%{$search}%")
                  ->orWhere('affiliation', 'like', "%{$search}%")
                  ->orWhereHas('callForProposal', function($q) use ($search) {
                      $q->where('title', 'like', "%{$search}%");
                  });
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Apply date filter
        if ($request->filled('date_filter')) {
            $dateFilter = $request->input('date_filter');
            $now = now();

            switch ($dateFilter) {
                case 'last_7_days':
                    $query->where('date_submitted', '>=', $now->copy()->subDays(7));
                    break;
                case 'last_30_days':
                    $query->where('date_submitted', '>=', $now->copy()->subDays(30));
                    break;
                case 'last_6_months':
                    $query->where('date_submitted', '>=', $now->copy()->subMonths(6));
                    break;
                case 'custom':
                    if ($request->filled('date_from')) {
                        $query->where('date_submitted', '>=', $request->input('date_from'));
                    }
                    if ($request->filled('date_to')) {
                        $query->where('date_submitted', '<=', $request->input('date_to'));
                    }
                    break;
            }
        }

        // Apply sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('date_submitted', 'asc');
                break;
            case 'a_z':
                $query->orderBy('research_title', 'asc');
                break;
            case 'z_a':
                $query->orderBy('research_title', 'desc');
                break;
            case 'budget_high':
                $query->orderBy('budget_requested', 'desc');
                break;
            case 'budget_low':
                $query->orderBy('budget_requested', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('date_submitted', 'desc');
                break;
        }

        $submissions = $query->paginate(12)->appends($request->except('page'));
        $totalCount = $submissions->total();

        // Handle AJAX requests
        if ($request->expectsJson()) {
            $html = view('users.proposal-submissions.submissions-table', compact('submissions'))->render();
            $pagination = $submissions->links()->render();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
                'totalCount' => $totalCount,
                'currentCount' => $submissions->count(),
                'from' => $submissions->firstItem() ?? 0,
                'to' => $submissions->lastItem() ?? 0,
            ]);
        }

        return view('users.proposal-submissions.index', compact('submissions', 'totalCount'));
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
