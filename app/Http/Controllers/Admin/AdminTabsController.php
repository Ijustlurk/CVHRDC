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
                $data['news'] = \App\Models\News::orderBy('id', 'desc')->paginate(8);
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
                $data['projects'] = \App\Models\Project::orderBy('id', 'desc')->paginate(8);
                break;
            case 'events':
                $data['events'] = \App\Models\Event::orderBy('id', 'desc')->paginate(8);
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
