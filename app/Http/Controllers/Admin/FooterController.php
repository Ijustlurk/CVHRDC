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
            'content' => 'required|string',
        ]);
        $footer = Footer::first();
        if (!$footer) {
            $footer = new Footer();
        }
        $footer->content = $request->content;
        $footer->save();
        return redirect()->back()->with('success', 'Footer updated successfully.');
    }
}
