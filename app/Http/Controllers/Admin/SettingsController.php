<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{

    public function index(Request $request)
    {
        $sections = ['header', 'banner', 'announcement', 'footer', 'maintenance'];
        $settings = Setting::all()->groupBy('section');
        $activeTab = $request->get('tab', 'header'); // Keep the same default value
        return view('admin.settings.index', compact('sections', 'activeTab', 'settings'));
    }

    public function update(Request $request, $section)
    {
        $data = $request->except(['_token', '_method']);
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['section' => $section, 'key' => $key],
                ['value' => $value]
            );
        }
        return redirect()->route('admin.settings.index', ['tab' => $section])->with('success', ucfirst($section).' settings updated!');
    }
}
