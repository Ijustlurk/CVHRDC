<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;

class BannerController extends Controller
{
    public function index()
    {
        $banner = Banner::first();
        dd($banner);
        return view('admin.settings.partials.banner', compact('banner'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'subtitle' => 'nullable|string',
            'image' => 'nullable|string',
            'link' => 'nullable|string',
        ]);
        $banner = Banner::first();
        if (!$banner) {
            $banner = new Banner();
        }
    $banner->fill($request->except('active'));
        $banner->save();
        return redirect()->back()->with('success', 'Banner updated successfully.');
    }
}
