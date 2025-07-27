<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Maintenance;

class SettingsController extends Controller
{

    public function index(Request $request)
    {
        $sections = ['header', 'banner', 'announcement', 'footer', 'maintenance'];
        $maintenance = \App\Models\Maintenance::first();
        $header = \App\Models\Header::first();
        $banner = \App\Models\Banner::first();
        $activeTab = $request->get('tab', 'header');
        $settings = [
            'header' => $header,
            'banner' => $banner,
        ];
        return view('admin.settings.index', compact('sections', 'activeTab', 'maintenance', 'settings'));
    }

    public function update(Request $request, $section)
    {
        $request->validate([
            'maintenance_mode' => 'required|in:on,off',
            'maintenance_message' => 'required|string',
        ]);

        $maintenance = Maintenance::first();
        if (!$maintenance) {
            $maintenance = new Maintenance();
        }
        $maintenance->active = $request->maintenance_mode === 'on';
        $maintenance->message = $request->maintenance_message;
        $maintenance->save();

        return redirect()->route('admin.settings.index', ['tab' => $section])
                        ->with('success', ucfirst($section) . ' settings updated!');
    }

    public function updateMaintenance(Request $request)
    {
        $maintenance = Maintenance::firstOrNew();
        $maintenance->active = $request->has('active');
        $maintenance->message = $request->input('message');
        $maintenance->save();

        return redirect()->back()->with('success', 'Maintenance settings updated successfully.');
    }

}
