<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ProposalSubmission;
use App\Models\PeerReview;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PeerReviewCoordinatorController extends Controller
{
    /**
     * Coordinator Dashboard
     */
    public function index()
    {
        $stats = [
            'total_submissions' => ProposalSubmission::count(),
            'pending_assignments' => ProposalSubmission::whereDoesntHave('peerReviews')->count(),
            'active_reviews' => PeerReview::where('status', 'Under Review')->count(),
            'completed_reviews' => PeerReview::where('status', 'Completed')->count(),
        ];

        return view('coordinator.dashboard', compact('stats'));
    }

    /**
     * List all submissions for management
     */
    public function submissions(Request $request)
    {
        $type = $request->get('type', 'proposal');
        
        // Currently, only 'proposal' type utilizes the ProposalSubmission model
        if ($type === 'proposal') {
            $query = ProposalSubmission::with(['peerReviews.reviewer', 'callForProposal']);

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
                default:
                    $query->orderBy('date_submitted', 'desc');
                    break;
            }

            $submissions = $query->paginate(15);
        } else {
            // For other types, return empty for now
            $submissions = ProposalSubmission::whereRaw('1=0')->paginate(15);
        }

        $submissions->appends($request->all());
        $totalCount = $submissions->total();

        // Fetch all reviewers for the assignment modal
        $reviewerRole = Role::where('name', 'Reviewer')->first();
        $reviewers = $reviewerRole ? $reviewerRole->users : collect();

        // Handle AJAX requests
        if ($request->ajax()) {
            $html = view('coordinator.submissions.partials.submissions-table', compact('submissions', 'type'))->render();
            return response()->json([
                'html' => $html,
                'totalCount' => $totalCount,
                'from' => $submissions->firstItem() ?? 0,
                'to' => $submissions->lastItem() ?? 0,
            ]);
        }

        return view('coordinator.submissions.index', compact('submissions', 'reviewers', 'type', 'totalCount'));
    }

    /**
     * Show submission details
     */
    public function show($id)
    {
        $submission = ProposalSubmission::with(['peerReviews.reviewer', 'callForProposal', 'user'])
            ->findOrFail($id);

        // Fetch all reviewers for the assignment modal
        $reviewerRole = Role::where('name', 'Reviewer')->first();
        $reviewers = $reviewerRole ? $reviewerRole->users : collect();
            
        return view('coordinator.submissions.show', compact('submission', 'reviewers'));
    }

    /**
     * Download the proposal document
     */
    public function download($id)
    {
        $submission = ProposalSubmission::findOrFail($id);

        if (!$submission->proposal_document || !Storage::disk('public')->exists($submission->proposal_document)) {
            return back()->with('error', 'Proposal document not found.');
        }

        // Get the original filename (remove timestamp prefix)
        $originalName = preg_replace('/^[0-9]+_/', '', basename($submission->proposal_document));

        return Storage::disk('public')->download(
            $submission->proposal_document,
            $originalName
        );
    }

    /**
     * Assign a reviewer to a submission
     */
    public function assignReviewer(Request $request)
    {
        $request->validate([
            'submission_id' => 'required|exists:proposal_submissions,id',
            'reviewer_ids' => 'required|array',
            'reviewer_ids.*' => 'exists:users,id',
        ]);

        $submission = ProposalSubmission::findOrFail($request->submission_id);

        // Validation based on submission status
        if ($submission->status === 'Accepted') {
            return back()->with('error', 'This submission has already been reviewed and accepted.');
        }

        if ($submission->status !== 'Submitted') {
            return back()->with('error', 'A reviewer can only be added to a submission if the status of the submission is "Submitted".');
        }

        DB::beginTransaction();
        try {
            $assignedCount = 0;
            $skippedCount = 0;

            foreach ($request->reviewer_ids as $reviewerId) {
                // Check if already assigned
                $exists = PeerReview::where('proposal_submission_id', $request->submission_id)
                    ->where('reviewer_id', $reviewerId)
                    ->exists();

                if ($exists) {
                    $skippedCount++;
                    continue;
                }

                PeerReview::create([
                    'proposal_submission_id' => $request->submission_id,
                    'reviewer_id' => $reviewerId,
                    'assigned_by' => Auth::id(),
                    'status' => 'Pending',
                ]);
                $assignedCount++;
            }

            DB::commit();

            if ($assignedCount > 0) {
                $msg = "$assignedCount reviewer(s) assigned successfully.";
                if ($skippedCount > 0) {
                    $msg .= " Note: $skippedCount reviewer(s) were skipped because they were already assigned.";
                }
                return back()->with('success', $msg);
            }

            return back()->with('error', 'No new reviewers were assigned. They may have already been assigned to this submission.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while assigning reviewers: ' . $e->getMessage());
        }
    }

    /**
     * Remove a reviewer assignment
     */
    public function unassignReviewer($submissionId, $reviewId)
    {
        $review = PeerReview::where('proposal_submission_id', $submissionId)
            ->where('id', $reviewId)
            ->firstOrFail();

        $review->delete();

        // Check if there are any remaining reviewers
        $remainingCount = PeerReview::where('proposal_submission_id', $submissionId)->count();
        
        $submission = ProposalSubmission::find($submissionId);
        if ($remainingCount === 0 && $submission->status === 'Under Review') {
            $submission->update(['status' => 'Submitted']);
        }

        return back()->with('success', 'Reviewer assignment removed successfully.');
    }
}
