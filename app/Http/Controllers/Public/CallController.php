<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CallForProposal;
use Illuminate\Http\Request;

class CallController extends Controller
{
    /**
     * Display a listing of public calls.
     */
    public function index()
    {
        $calls = CallForProposal::where('visibility_status', 'Public')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Update status for calls that have passed their end date
        foreach ($calls as $call) {
            $call->updateStatusBasedOnDates();
        }

        return view('public.calls.index', compact('calls'));
    }

    /**
     * Display the specified call.
     */
    public function show($id)
    {
        $call = CallForProposal::where('id', $id)
            ->where('visibility_status', 'Public')
            ->firstOrFail();

        // Update status based on dates if needed
        $call->updateStatusBasedOnDates();

        return view('public.calls.show', compact('call'));
    }

    /**
     * Download the proposal format file for the specified call.
     */
    public function downloadFormat($id)
    {
        $call = CallForProposal::where('id', $id)
            ->where('visibility_status', 'Public')
            ->firstOrFail();

        // Only allow download for calls that are accepting submissions or closed (for reference)
        if (!$call->isAcceptingSubmissions() && $call->display_status !== 'Closed') {
            abort(403, 'Format file download not available for this call status');
        }

        if (!$call->proposal_format_file) {
            abort(404, 'Format file not found');
        }

        $filePath = storage_path('app/public/' . $call->proposal_format_file);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->download($filePath);
    }

    /**
     * Get public calls data for AJAX updates.
     */
    public function getPublicCalls()
    {
        $calls = CallForProposal::where('visibility_status', 'Public')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Update status for calls that have passed their end date
        foreach ($calls as $call) {
            $call->updateStatusBasedOnDates();
        }

        return response()->json([
            'calls' => $calls->map(function ($call) {
                return [
                    'id' => $call->id,
                    'title' => $call->title,
                    'description' => Str::limit(strip_tags($call->description), 100),
                    'funding_limit' => $call->funding_limit,
                    'start_date' => $call->start_date->format('M d, Y'),
                    'end_date' => $call->end_date->format('M d, Y'),
                    'eligibility' => Str::limit($call->eligibility, 25),
                    'status' => $call->display_status,
                    'status_label' => $call->getStatusLabel(),
                    'status_color' => $call->getStatusBadgeColor(),
                    'created_at' => $call->created_at->diffForHumans(),
                    'url' => route('public.calls.show', $call->id)
                ];
            }),
            'count' => $calls->count(),
            'timestamp' => now()->toISOString()
        ]);
    }
}
