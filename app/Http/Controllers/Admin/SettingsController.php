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
        $settings = Maintenance::all();
        $activeTab = $request->get('tab', 'header'); // Keep the same default value
        return view('admin.settings.index', compact('sections', 'activeTab', 'settings'));
    }

        public function update(Request $request, $section)
        {
            $request->validate([
                'maintenance_mode' => 'required|in:on,off',
                'maintenance_message' => 'required|string',
            ]);

            $data = $request->only(['maintenance_mode', 'maintenance_message']);

            foreach ($data as $key => $value) {
                Maintenance::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }

            return redirect()->route('admin.settings.index', ['tab' => $section])
                            ->with('success', ucfirst($section) . ' settings updated!');
        }

}
