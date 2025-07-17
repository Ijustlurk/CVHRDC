<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\rooms;
use Illuminate\Http\Request;
use DB;

class CTRLrooms extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roomsrec = rooms::paginate(10);

        return view('admin.rooms')
        ->with('roomsrec',$roomsrec);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       // dd($request);
       // $request->input('roomarea')

                $new= new rooms;
                $new->roomarea = $request->input('roomarea');
                $new->roomcapacity=$request->input('roomcapacity');
                $new->save();

                return redirect()->back()->with('success', 'Data added successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $affected = DB::table('rooms')
              ->where('id', $id)
              ->update([
                'roomcapacity' => $request->input('roomcapacity'),
                'bldgid' => $request->input('bldgid'),
            ]);

            return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
