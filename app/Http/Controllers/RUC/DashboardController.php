<?php

namespace App\Http\Controllers\RUC;

use App\Http\Controllers\Controller;
use App\Models\CallForProposal;
use App\Models\ProposalSubmission;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCalls = CallForProposal::count();
        $forReviewCalls = CallForProposal::where('status', 'For Review')->count();
        $publicCalls = CallForProposal::where('visibility_status', 'Public')->count();
        $privateCalls = CallForProposal::where('visibility_status', 'Private')->count();
        
        $totalSubmissions = ProposalSubmission::count();
        $submittedProposals = ProposalSubmission::where('status', 'Submitted')->count();
        $underReviewProposals = ProposalSubmission::where('status', 'Under Review')->count();
        $acceptedProposals = ProposalSubmission::where('status', 'Accepted')->count();
        
        // Calls pending review
        $callsForReview = CallForProposal::where('status', 'For Review')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Recent approvals (last 7 days)
        $recentApprovals = CallForProposal::where('updated_at', '>=', now()->subDays(7))
            ->whereNotNull('approved_by')
            ->count();
        
        return view('ruc.dashboard', compact(
            'totalCalls',
            'forReviewCalls',
            'publicCalls',
            'privateCalls',
            'totalSubmissions',
            'submittedProposals',
            'underReviewProposals',
            'acceptedProposals',
            'callsForReview',
            'recentApprovals'
        ));
    }
}

