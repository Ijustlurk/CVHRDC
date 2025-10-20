<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminTabsController extends Controller
{
    public function index($tab = 'news')
    {
        $validTabs = ['news', 'research', 'projects', 'events', 'publications', 'researchers', 'partners', 'announcements'];
        if (!in_array($tab, $validTabs)) {
            $tab = 'news';
        }

        $data = ['tab' => $tab];
        switch ($tab) {
            case 'news':
                $data['news'] = \App\Models\News::query();

                // Apply the same filtering logic as NewsController
                if (request()->filled('search')) {
                    $search = request()->input('search');
                    $data['news']->where(function ($q) use ($search) {
                        $q->where('title', 'like', '%' . $search . '%')
                            ->orWhere('content', 'like', '%' . $search . '%');
                    });
                }

                // Filter by visibility status
                if (request()->filled('status')) {
                    $status = request()->input('status');
                    if ($status === 'public') {
                        $data['news']->where('is_visible', 1);
                    } elseif ($status === 'private') {
                        $data['news']->where('is_visible', 0);
                    }
                }

                // Filter by date range
                if (request()->filled('date_filter')) {
                    $dateFilter = request()->input('date_filter');
                    $now = now();

                    switch ($dateFilter) {
                        case 'last_7_days':
                            $data['news']->where('published_at', '>=', $now->subDays(7));
                            break;
                        case 'last_30_days':
                            $data['news']->where('published_at', '>=', $now->subDays(30));
                            break;
                        case 'last_6_months':
                            $data['news']->where('published_at', '>=', $now->subMonths(6));
                            break;
                        case 'custom':
                            if (request()->filled('date_from')) {
                                $data['news']->where('published_at', '>=', request()->input('date_from'));
                            }
                            if (request()->filled('date_to')) {
                                $data['news']->where('published_at', '<=', request()->input('date_to'));
                            }
                            break;
                    }
                }

                // Sorting
                $sort = request()->input('sort', 'newest');
                switch ($sort) {
                    case 'oldest':
                        $data['news']->orderBy('published_at', 'asc');
                        break;
                    case 'a_z':
                        $data['news']->orderBy('title', 'asc');
                        break;
                    case 'z_a':
                        $data['news']->orderBy('title', 'desc');
                        break;
                    case 'newest':
                    default:
                        $data['news']->orderBy('published_at', 'desc');
                        break;
                }

                $data['news'] = $data['news']->paginate(8)->appends(request()->except('page'));
                $data['totalCount'] = $data['news']->total();
                break;
            case 'research':
                // If Research model exists, load; otherwise provide empty collection
                $researchClass = 'App\\Models\\Research';
                if (class_exists($researchClass)) {
                    $data['research'] = $researchClass::orderBy('id', 'desc')->paginate(8);
                } else {
                    $data['research'] = collect([]);
                }
                break;
            case 'projects':
                $data['projects'] = \App\Models\Project::query();

                // Apply the same filtering logic as ProjectController
                if (request()->filled('search')) {
                    $search = request()->input('search');
                    $data['projects']->where(function ($q) use ($search) {
                        $q->where('title', 'like', '%' . $search . '%')
                            ->orWhere('description', 'like', '%' . $search . '%')
                            ->orWhere('lead_researcher', 'like', '%' . $search . '%')
                            ->orWhere('leader', 'like', '%' . $search . '%')
                            ->orWhere('funding_agency', 'like', '%' . $search . '%');
                    });
                }

                // Status filter
                if (request()->filled('status_filter')) {
                    $data['projects']->where('status', 'like', '%' . request()->input('status_filter') . '%');
                }

                // Lead researcher filter
                if (request()->filled('lead_researcher_filter')) {
                    $data['projects']->where('lead_researcher', 'like', '%' . request()->input('lead_researcher_filter') . '%');
                }

                // Date filter
                $dateFilter = request()->input('date_filter');
                if ($dateFilter) {
                    switch ($dateFilter) {
                        case 'last_7_days':
                            $data['projects']->where('start_date', '>=', now()->subDays(7));
                            break;
                        case 'last_30_days':
                            $data['projects']->where('start_date', '>=', now()->subDays(30));
                            break;
                        case 'last_6_months':
                            $data['projects']->where('start_date', '>=', now()->subMonths(6));
                            break;
                        case 'custom':
                            if (request()->filled('date_from') && request()->filled('date_to')) {
                                $data['projects']->whereBetween('start_date', [request()->input('date_from'), request()->input('date_to')]);
                            } elseif (request()->filled('date_from')) {
                                $data['projects']->where('start_date', '>=', request()->input('date_from'));
                            } elseif (request()->filled('date_to')) {
                                $data['projects']->where('start_date', '<=', request()->input('date_to'));
                            }
                            break;
                    }
                }

                // Budget range filter
                if (request()->filled('budget_min')) {
                    $data['projects']->where('budget', '>=', request()->input('budget_min'));
                }
                if (request()->filled('budget_max')) {
                    $data['projects']->where('budget', '<=', request()->input('budget_max'));
                }

                // Visibility filter
                if (request()->filled('visibility')) {
                    $visibility = request()->input('visibility');
                    if ($visibility === 'visible') {
                        $data['projects']->where('is_visible', 1);
                    } elseif ($visibility === 'hidden') {
                        $data['projects']->where('is_visible', 0);
                    }
                }

                // Sorting logic
                $sort = request()->input('sort', 'newest');
                switch ($sort) {
                    case 'oldest':
                        $data['projects']->orderBy('start_date', 'asc');
                        break;
                    case 'a_z':
                        $data['projects']->orderBy('title', 'asc');
                        break;
                    case 'z_a':
                        $data['projects']->orderBy('title', 'desc');
                        break;
                    case 'budget_high':
                        $data['projects']->orderBy('budget', 'desc');
                        break;
                    case 'budget_low':
                        $data['projects']->orderBy('budget', 'asc');
                        break;
                    case 'newest':
                    default:
                        $data['projects']->orderBy('start_date', 'desc');
                        break;
                }

                $data['projects'] = $data['projects']->paginate(8)->appends(request()->except('page'));
                $data['totalCount'] = $data['projects']->total();
                break;
            case 'events':
                $data['events'] = \App\Models\Event::query();

                // Apply the same filtering logic as EventController
                if (request()->filled('search')) {
                    $search = request()->input('search');
                    $data['events']->where(function ($q) use ($search) {
                        $q->where('title', 'like', '%' . $search . '%')
                            ->orWhere('description', 'like', '%' . $search . '%')
                            ->orWhere('location', 'like', '%' . $search . '%')
                            ->orWhere('organizer', 'like', '%' . $search . '%');
                    });
                }

                // Time-based filters
                $timeFilter = request()->input('time_filter', 'upcoming');
                $today = now()->startOfDay();

                switch ($timeFilter) {
                    case 'upcoming':
                        $data['events']->where('start_date', '>=', $today);
                        break;
                    case 'past':
                        $data['events']->where(function ($q) use ($today) {
                            $q->where('end_date', '<', $today)
                                ->orWhere(function ($subQ) use ($today) {
                                    $subQ->whereNull('end_date')
                                        ->where('start_date', '<', $today);
                                });
                        });
                        break;
                    case 'all':
                        // No time restriction
                        break;
                }

                // Custom date range (overrides time filter)
                if (request()->filled('date_from') && request()->filled('date_to')) {
                    $dateFrom = request()->input('date_from');
                    $dateTo = request()->input('date_to');

                    $data['events']->where(function ($q) use ($dateFrom, $dateTo) {
                        $q->whereBetween('start_date', [$dateFrom, $dateTo])
                            ->orWhere(function ($subQ) use ($dateFrom, $dateTo) {
                                $subQ->where('start_date', '<=', $dateTo)
                                    ->where(function ($endQ) use ($dateFrom) {
                                        $endQ->where('end_date', '>=', $dateFrom)
                                            ->orWhereNull('end_date');
                                    });
                            });
                    });
                } elseif (request()->filled('date_from')) {
                    $data['events']->where('start_date', '>=', request()->input('date_from'));
                } elseif (request()->filled('date_to')) {
                    $data['events']->where('start_date', '<=', request()->input('date_to'));
                }

                // Visibility filter
                if (request()->filled('visibility')) {
                    $visibility = request()->input('visibility');
                    if ($visibility === 'visible') {
                        $data['events']->where('is_visible', 1);
                    } elseif ($visibility === 'hidden') {
                        $data['events']->where('is_visible', 0);
                    }
                }

                // Sorting logic
                $sort = request()->input('sort', 'auto');

                if ($sort === 'auto') {
                    switch ($timeFilter) {
                        case 'upcoming':
                            $data['events']->orderBy('start_date', 'asc');
                            break;
                        case 'past':
                            $data['events']->orderBy('start_date', 'desc');
                            break;
                        case 'all':
                        default:
                            $data['events']->orderBy('start_date', 'desc');
                            break;
                    }
                } else {
                    switch ($sort) {
                        case 'date_asc':
                            $data['events']->orderBy('start_date', 'asc');
                            break;
                        case 'date_desc':
                            $data['events']->orderBy('start_date', 'desc');
                            break;
                        case 'title_asc':
                            $data['events']->orderBy('title', 'asc');
                            break;
                        case 'title_desc':
                            $data['events']->orderBy('title', 'desc');
                            break;
                        default:
                            $data['events']->orderBy('start_date', 'desc');
                            break;
                    }
                }

                $data['events'] = $data['events']->paginate(8)->appends(request()->except('page'));
                $data['totalCount'] = $data['events']->total();
                break;
            case 'publications':
                $data['publications'] = \App\Models\Publication::orderBy('id', 'desc')->paginate(8);
                break;
            case 'researchers':
                $data['researchers'] = \App\Models\Researcher::orderBy('id', 'desc')->paginate(8);
                break;
            case 'partners':
                $data['partners'] = \App\Models\Partner::orderBy('id', 'desc')->paginate(8);
                break;
            case 'announcements':
                $data['announcements'] = \App\Models\Announcement::orderBy('id', 'desc')->paginate(8);
                break;
        }
        return view('admin.tabs.index', $data);
    }
}
