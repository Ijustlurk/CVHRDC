<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use App\Models\Researcher;
use App\Models\Partner;
use App\Models\Project;
use App\Models\User;
use App\Models\News;
use App\Models\Users\Feedbacks;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPublications = Publication::count();
        $totalResearchers = Researcher::count();
        $totalCollaborations = Partner::count() + Project::count();
        $totalUsers = User::count();
        $totalNews = News::count();
        $totalFeedbacks = Feedbacks::count();
        // Example: Popular articles by most recent news
        $popularArticles = News::orderBy('created_at', 'desc')->take(5)->get();
        // Example: User engagement (dummy data)
        $engagement = [
            'views' => rand(100, 500),
            'downloads' => rand(50, 200),
        ];
        // Example: New user registrations (last 7 days)
        $newUsers = User::where('created_at', '>=', now()->subDays(7))->count();
        // Example: Feedbacks as communication
        $newFeedbacks = Feedbacks::where('created_at', '>=', now()->subDays(7))->count();
        return view('admin.dashboard', compact(
            'totalPublications',
            'totalResearchers',
            'totalCollaborations',
            'totalUsers',
            'totalNews',
            'totalFeedbacks',
            'popularArticles',
            'engagement',
            'newUsers',
            'newFeedbacks'
        ));
    }
}
