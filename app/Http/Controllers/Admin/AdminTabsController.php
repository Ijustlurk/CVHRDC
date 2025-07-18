<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminTabsController extends Controller
{
    public function index($tab = 'news')
    {
        $validTabs = ['news', 'projects', 'events', 'publications', 'researchers', 'partners'];
        if (!in_array($tab, $validTabs)) {
            $tab = 'news';
        }
        return view('admin.tabs.index', compact('tab'));
    }
}
