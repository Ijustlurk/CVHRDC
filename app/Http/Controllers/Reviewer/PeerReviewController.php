<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\PeerReview;
use App\Models\ProposalSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeerReviewController extends Controller
{
    /**
     * Reviewer Dashboard
     */
    public function dashboard()
    {
        $reviewerId = Auth::id();

        $stats = [
            'total'       => PeerReview::where('reviewer_id', $reviewerId)->count(),
            'pending'     => PeerReview::where('reviewer_id', $reviewerId)->where('status', 'Pending')->count(),
            'under_review'=> PeerReview::where('reviewer_id', $reviewerId)->where('status', 'Under Review')->count(),
            'completed'   => PeerReview::where('reviewer_id', $reviewerId)->where('status', 'Completed')->count(),
            'cancelled'   => PeerReview::where('reviewer_id', $reviewerId)->where('status', 'Cancelled')->count(),
        ];

        $recentInvitations = PeerReview::where('reviewer_id', $reviewerId)
            ->where('status', 'Pending')
            ->with(['submission.callForProposal'])
            ->latest()
            ->take(5)
            ->get();

        $recentActive = PeerReview::where('reviewer_id', $reviewerId)
            ->where('status', 'Under Review')
            ->with(['submission.callForProposal'])
            ->latest()
            ->take(5)
            ->get();

        return view('reviewer.dashboard', compact('stats', 'recentInvitations', 'recentActive'));
    }

    /**
     * List all review assignments with filtering
     */
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'invitations');

        $query = PeerReview::where('reviewer_id', Auth::id())
            ->with(['submission.callForProposal', 'submission.user']);

        // Filter by tab (invitations = Pending, assignments = Under Review/Completed/Cancelled)
        if ($tab === 'invitations') {
            $query->where('status', 'Pending');
        } else {
            $query->whereIn('status', ['Under Review', 'Completed', 'Cancelled']);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('submission', function($q) use ($search) {
                $q->where('research_title', 'like', "%{$search}%")
                  ->orWhere('proponent_name', 'like', "%{$search}%");
            });
        }

        // Status filter (for assignments tab)
        if ($request->filled('status') && $tab === 'assignments') {
            $query->where('status', $request->input('status'));
        }

        // Date filter (by when it was assigned)
        if ($request->filled('date_filter')) {
            $now = now();
            switch ($request->input('date_filter')) {
                case 'last_7_days':
                    $query->where('created_at', '>=', $now->copy()->subDays(7));
                    break;
                case 'last_30_days':
                    $query->where('created_at', '>=', $now->copy()->subDays(30));
                    break;
                case 'last_6_months':
                    $query->where('created_at', '>=', $now->copy()->subMonths(6));
                    break;
                case 'custom':
                    if ($request->filled('date_from')) {
                        $query->where('created_at', '>=', $request->input('date_from'));
                    }
                    if ($request->filled('date_to')) {
                        $query->where('created_at', '<=', $request->input('date_to'));
                    }
                    break;
            }
        }

        // Sort
        switch ($request->input('sort', 'newest')) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'a_z':
                $query->join('proposal_submissions', 'peer_reviews.proposal_submission_id', '=', 'proposal_submissions.id')
                      ->orderBy('proposal_submissions.research_title', 'asc')
                      ->select('peer_reviews.*');
                break;
            case 'z_a':
                $query->join('proposal_submissions', 'peer_reviews.proposal_submission_id', '=', 'proposal_submissions.id')
                      ->orderBy('proposal_submissions.research_title', 'desc')
                      ->select('peer_reviews.*');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $reviews = $query->paginate(10)->withQueryString();
        $totalCount = $reviews->total();

        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'html'       => view('reviewer.reviews.partials.reviews-table', compact('reviews', 'tab'))->render(),
                'from'       => $reviews->firstItem() ?? 0,
                'to'         => $reviews->lastItem() ?? 0,
                'totalCount' => $totalCount,
            ]);
        }

        return view('reviewer.reviews.index', compact('reviews', 'tab', 'totalCount'));
    }

    /**
     * Accept a review invitation
     */
    public function accept($id)
    {
        $review = PeerReview::where('reviewer_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if ($review->status !== 'Pending') {
            return back()->with('error', 'This assignment has already been processed.');
        }

        DB::beginTransaction();
        try {
            $review->update(['status' => 'Under Review']);

            $submission = $review->submission;
            if ($submission && $submission->status === 'Submitted') {
                $submission->update(['status' => 'Under Review']);
            }

            DB::commit();
            return back()->with('success', 'You have accepted the review invitation. The submission is now marked as "Under Review".');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Decline a review invitation
     */
    public function decline($id)
    {
        $review = PeerReview::where('reviewer_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if ($review->status !== 'Pending') {
            return back()->with('error', 'This assignment has already been processed.');
        }

        $review->update(['status' => 'Cancelled']);
        return back()->with('success', 'Review invitation declined.');
    }
}
