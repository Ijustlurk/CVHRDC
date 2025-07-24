<?php
namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Example: get user's projects and publications (replace with real relations if available)
        $projects = \App\Models\Project::where('lead_researcher', $user->id)->orWhere('leader', $user->id)->get();
        $publications = \App\Models\Publication::where('user_id', $user->id)->get();

        // Dummy activities and notifications (replace with real queries)
        $activities = [
            ['type' => 'publication', 'text' => 'Published a new paper: "AI in Healthcare"', 'date' => now()->subDays(1)],
            ['type' => 'project', 'text' => 'Joined project: "COVID-19 Research"', 'date' => now()->subDays(2)],
            ['type' => 'comment', 'text' => 'Commented on "Data Science Group"', 'date' => now()->subDays(3)],
        ];
        $notifications = [
            ['type' => 'collab', 'text' => 'New collaboration request from Dr. Lee', 'date' => now()->subHours(2), 'read' => false],
            ['type' => 'project', 'text' => 'Project "Malaria Study" updated', 'date' => now()->subHours(5), 'read' => true],
            ['type' => 'approval', 'text' => 'Proposal "Genomics" approved', 'date' => now()->subDays(1), 'read' => false],
        ];

        return view('users.dashboard', compact('user', 'projects', 'publications', 'activities', 'notifications'));
    }
}
