<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\building;
use App\Models\userlogs;
use App\Models\errorLogs;

class CTRLbldgprofile extends Controller
{









    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id = 0;

        // this will load the blade
        return view('admin.buildings.index');
    }

                //this contains the query
                public function listbldg()
                {


                    $campuses = DB::table('buildings')
                        ->distinct()
                        ->select(['id', 'campus', 'address', 'bldgname'])
                        ->get();

                    // Group the buildings by campus
                    $groupedCampuses = [];

                    foreach ($campuses as $campus) {
                        $groupedCampuses[$campus->campus][] = [
                            'id' => $campus->id,
                            'bldgname' => $campus->bldgname,
                            'address' => $campus->address,
                        ];
                    }

                    // Convert the grouped campuses array to the format expected by the response

                    $formattedCampuses = [];

                    foreach ($groupedCampuses as $campus => $buildings) {

                        $formattedCampuses[] = [
                            'campus' => $campus,
                            'buildings' => $buildings,
                        ];
                    }

                    return response()->json(['campuses' => $formattedCampuses]);


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
        try {

                $new= new building;
                $new->bldgname=$request->input('bldgname');
                $new->bldgstructure=$request->input('bldgstructure');
                $new->bldgtype=$request->input('bldgtype');
                $new->bldgarea=$request->input('bldgarea');
                $new->lotarea=$request->input('lotarea');
                $new->bldglocation=$request->input('bldglocation');
                $new->bldgincharge=$request->input('bldgincharge');
                $new->datecompleted=$request->input('datecompleted');
                $new->save();

                return redirect()->back()->with('success', 'Data added successfully!');

            } catch (Exception $e) {

                $newerrlog = new errorLogs;
                $newerrlog->task = 'uploading of building certificate';
                $newerrlog->error = $e->getMessage();
                $newerrlog->save();

                return redirect()->back();
            }
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

        $bldgrec = building::paginate(10);

        $editbldg = building::findOrFail($id);

        return view('admin.bldg')
        ->with('id',$id)
        ->with('bldgrec',$bldgrec)
        ->with('editbldg',$editbldg);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }



    //ETO SA PAG_UPDATE NG BUILDING KANU
    public function bldgupdate1(Request $request) {

        $update = DB::table('buildings')
              ->where('id', $request->bldgid)
              ->update([
                'bldgname' => $request->bldgname,
                'bldgstructure' => $request->bldgstructure,
                'bldgincharge' => $request->bldgincharge

                ]);

                // return response()->json(['status' => '200']);
                return back();
    }


    public function Uploadfile(Request $request, $id)
    {
        try {
            $file = $request->file('logo');
            $fileName = $file->getClientOriginalName();
            $destinationPath = $id;
            $file->move($destinationPath, $fileName);

       $brgyProfile = building::where('id', $id)->first();
       $brgyProfile->bldgCSUcertfilename = $fileName;
       $brgyProfile->save();

            // dito ka mag add ng user logs
        $newlog = new userlogs;
        $newlog->user_id = Auth::user()->id;
        $newlog->activity = 'Uploaded a building certificate for the building id: '.$id;
        $newlog->save();

        return redirect()->back();

        } catch (Exception $e) {

            $newerrlog = new errorLogs;
            $newerrlog->task = 'uploading of building certificate';
            $newerrlog->error = $e->getMessage();
            $newerrlog->save();

            return redirect()->back();
        }

    }




    public function ViewBldg($id)
    {

        try {
            $bldgrec = building::where('id',$id)->get();

                // get maps lat ang long
            $locs = DB::table('buildings')
            ->select('bldgname','long','lat')
            ->where('id',$id)
            ->get();


            return view('admin.buildings.show')
            ->with('id',$id)
            ->with('locs',$locs)
            ->with('bldgrec',$bldgrec);
        } catch (Exception $e) {
            $newerrlog = new errorLogs;
            $newerrlog->task = 'Viewing of building details or information';
            $newerrlog->error = $e->getMessage();
            $newerrlog->save();

            return redirect()->back();
        }
    }




    public function dsplyselcam($id)
    {
        // $id this will the name of the capmus selected
        try {

            $totfloorarea = DB::table('buildings')
            ->select('campus',  DB::raw('SUM(bldgfloorarea) as totalfloorareas'))
            ->where('campus',$id)
            ->groupBy('campus')
            ->get();


            $buildingsofcampus = DB::table('buildings')
            ->select('*')
            ->where('campus',$id)
            ->get();

            return view('admin.campus.show',compact('buildingsofcampus','totfloorarea'));

        } catch (Exception $e) {
            $newerrlog = new errorLogs;
            $newerrlog->task = 'viewing of building of that selected campus';
            $newerrlog->error = $e->getMessage();
            $newerrlog->save();

            return redirect()->back();
        }
    }





    // to display the map of the selected campus
    public function dsplyselcampusmap($id)
    {

        $totfloorarea = DB::table('buildings')
        ->select('campus',  DB::raw('SUM(bldgfloorarea) as totalfloorareas'))
        ->where('campus',$id)
        ->groupBy('campus')
        ->get();



        $locs = DB::table('buildings')
        ->select('*')
        ->where('campus',$id)
        ->get();

        return view('admin.campus.show-all-buildings',
        compact('locs','id','totfloorarea'));

    }




}
