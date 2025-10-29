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
    public function index(Request $request)
    {
        $query = CallForProposal::where('visibility_status', 'Public');

        // Update status for calls that have passed their end date
        $query->get()->each(function ($call) {
            $call->updateStatusBasedOnDates();
        });

        // Search by title, research priority area, or description
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('research_priority_area', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('eligibility', 'like', '%' . $search . '%');
            });
        }

        // Filter by display status (Open, Closed, Upcoming)
        if ($request->filled('status')) {
            $status = $request->input('status');
            $query->where(function ($q) use ($status) {
                $now = now();
                switch ($status) {
                    case 'Open':
                        $q->where('status', 'Open')
                          ->where('start_date', '<=', $now)
                          ->where('end_date', '>=', $now);
                        break;
                    case 'Closed':
                        $q->where(function ($subQ) use ($now) {
                            $subQ->where('end_date', '<', $now)
                                 ->orWhere('status', 'Closed');
                        });
                        break;
                    case 'Upcoming':
                        $q->where('start_date', '>', $now)
                          ->where('status', '!=', 'Closed');
                        break;
                }
            });
        }

        // Filter by date range
        if ($request->filled('date_filter')) {
            $dateFilter = $request->input('date_filter');
            $now = now();

            switch ($dateFilter) {
                case 'last_7_days':
                    $query->where('created_at', '>=', $now->subDays(7));
                    break;
                case 'last_30_days':
                    $query->where('created_at', '>=', $now->subDays(30));
                    break;
                case 'last_6_months':
                    $query->where('created_at', '>=', $now->subMonths(6));
                    break;
                case 'custom':
                    if ($request->filled('date_from')) {
                        $query->where('created_at', '>=', $request->input('date_from'));
                    }
                    if ($request->filled('date_to')) {
                        $query->where('created_at', '<=', $request->input('date_to') . ' 23:59:59');
                    }
                    break;
            }
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'a_z':
                $query->orderBy('title', 'asc');
                break;
            case 'z_a':
                $query->orderBy('title', 'desc');
                break;
            case 'funding_high':
                $query->orderBy('funding_limit', 'desc');
                break;
            case 'funding_low':
                $query->orderBy('funding_limit', 'asc');
                break;
            case 'deadline_soon':
                $query->orderBy('end_date', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Paginate results (get total count for display)
        $calls = $query->paginate(12)->appends($request->except('page'));
        $totalCount = $calls->total();

        // If AJAX request (for real-time search), return JSON
        if ($request->ajax()) {
            return response()->json([
                'html' => view('public.calls.partials.calls-list', compact('calls'))->render(),
                'pagination' => $calls->links()->render(),
                'totalCount' => $totalCount,
                'currentCount' => $calls->count(),
                'from' => $calls->firstItem() ?? 0,
                'to' => $calls->lastItem() ?? 0,
            ]);
        }

        return view('public.calls.index', compact('calls', 'totalCount'));
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
