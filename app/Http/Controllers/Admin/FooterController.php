<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Footer;

class FooterController extends Controller
{
    public function edit()
    {
        $footer = Footer::first();
        return view('admin.footer.edit', compact('footer'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'org_name' => 'required|string',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'website' => 'nullable|string',
            'copyright' => 'nullable|string',
        ]);
        $footer = Footer::first();
        if (!$footer) {
            $footer = new Footer();
        }
        $footer->org_name = $request->org_name;
        $footer->description = $request->description;
        $footer->address = $request->address;
        $footer->email = $request->email;
        $footer->phone = $request->phone;
        $footer->website = $request->website;
        $footer->copyright = $request->copyright;
        $footer->save();
        return redirect()->back()->with('success', 'Footer updated successfully.');
    }
}
