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
     * Display a listing of assigned reviews
     */
    public function index()
    {
        $assignments = PeerReview::where('reviewer_id', Auth::id())
            ->with(['submission.callForProposal'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reviewer.reviews.index', compact('assignments'));
    }

    /**
     * Accept a review assignment
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
            // Mark the review as Under Review
            $review->update(['status' => 'Under Review']);

            // Update the submission status to Under Review
            $submission = $review->submission;
            if ($submission->status === 'Submitted') {
                $submission->update(['status' => 'Under Review']);
            }

            DB::commit();
            return back()->with('success', 'You have agreed to review this submission. It is now marked as Under Review.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong while accepting the assignment.');
        }
    }

    /**
     * Decline a review assignment
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

        return back()->with('success', 'Assignment declined.');
    }
}
