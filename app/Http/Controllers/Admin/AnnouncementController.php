<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcement = Announcement::first();
        return view('admin.settings.partials.announcement', compact('announcement'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'active' => 'required|boolean',
        ]);
        $announcement = Announcement::first();
        if (!$announcement) {
            $announcement = new Announcement();
        }
        $announcement->fill($request->all());
        $announcement->save();
        return redirect()->back()->with('success', 'Announcement updated successfully.');
    }
}
