<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Header;

class HeaderController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'logo' => 'nullable|string|max:255',
        ]);
        $header = Header::first();
        if (!$header) {
            $header = new Header();
        }
        $header->title = $request->title;
        $header->subtitle = $request->subtitle;
        $header->logo = $request->logo;
        $header->save();
        return redirect()->back()->with('success', 'Header updated successfully.');
    }
}
