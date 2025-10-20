<?php

namespace App\Http\Controllers\RMC;

use App\Http\Controllers\Controller;
use App\Models\CallForProposal;
use App\Models\ProposalSubmission;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCalls = CallForProposal::count();
        $activeCalls = CallForProposal::where('status', 'Open')->count();
        $draftCalls = CallForProposal::where('status', 'Draft')->count();
        $closedCalls = CallForProposal::where('status', 'Closed')->count();
        
        $totalSubmissions = ProposalSubmission::count();
        $submittedProposals = ProposalSubmission::where('status', 'Submitted')->count();
        $underReviewProposals = ProposalSubmission::where('status', 'Under Review')->count();
        $acceptedProposals = ProposalSubmission::where('status', 'Accepted')->count();
        
        // Recent calls created by this user
        $recentCalls = CallForProposal::where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Recent submissions (last 7 days)
        $recentSubmissions = ProposalSubmission::where('date_submitted', '>=', now()->subDays(7))->count();
        
        return view('rmc.dashboard', compact(
            'totalCalls',
            'activeCalls',
            'draftCalls',
            'closedCalls',
            'totalSubmissions',
            'submittedProposals',
            'underReviewProposals',
            'acceptedProposals',
            'recentCalls',
            'recentSubmissions'
        ));
    }
}

