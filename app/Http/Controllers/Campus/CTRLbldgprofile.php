<?php

namespace App\Http\Controllers\Campus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\building;
use App\Models\errorLogs;
use App\Models\userlogs;

use Illuminate\Support\Facades\DB;

class CTRLbldgprofile extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
$id = 0;

        $bldgrec = building::where('campus',Auth::user()->campus)->paginate(10);

        return view('campus.buildings.index')
        ->with('id',$id)
        ->with('bldgrec',$bldgrec);
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
            $new->campus=Auth::user()->campus;
            $new->address=Auth::user()->address;
            $new->bldgname=$request->input('bldgname');

            $new->desc=$request->input('desc');
            $new->date_completed=$request->input('datecompleted');
            $new->save();

                   // dito ka mag add ng user logs
        $newlog = new userlogs;
        $newlog->user_id = Auth::user()->id;
        $newlog->activity = 'Added new building '.$request->input('bldgname').' record';
        $newlog->save();

            return redirect()->back()->with('success', 'Data added successfully!');

        } catch (Exception $e) {

            $newerrlog = new errorLogs;
            $newerrlog->task = 'adding new record for building';
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


        $bldgrec = building::where('id',$id)->get();

        // get maps lat ang long
        $locs = DB::table('buildings')
        ->select('bldgname','long','lat')
        ->where('id',$id)
        ->get();

        return view('campus.buildings.show')
        ->with('id',$id)
        ->with('locs',$locs)
        ->with('bldgrec',$bldgrec);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        // $bldgrec = building::paginate(10);
        // $editbldg = building::findOrFail($id);

        // return view('campus.bldg')
        // ->with('id',$id)
        // ->with('bldgrec',$bldgrec)
        // ->with('editbldg',$editbldg);
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


        try {

            $update = DB::table('buildings')
            ->where('id', $request->bldgid)
            ->update([
              'bldgname' => $request->bldgname,
              'desc' => $request->desc,
              'date_completed' => $request->date_completed,
              'bldgfloorarea' => $request->bldgfloorarea,
              'address' => Auth::user()->address,
              'bldgtype' => $request->bldgtype,
              'bldgnumberoffloors' => $request->bldgnumberoffloors,
              'bldgnumberofrooms' => $request->bldgnumberofrooms,
              'bldgnumberofcrs' => $request->bldgnumberofcrs,

              'lat' => $request->lat,
              'long' => $request->long,

              'college_office_assigned' => $request->college_office_assigned
              ]);

            return redirect()->back()->with('success', 'Data added successfully!');

        } catch (Exception $e) {

            $newerrlog = new errorLogs;
            $newerrlog->task = 'edit existing building '.$request->bldgname;
            $newerrlog->error = $e->getMessage();
            $newerrlog->save();

            return redirect()->back();

        }



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



    public function LGUbldgpermitUploadfile(Request $request, $id)
    {
        try {
            $file = $request->file('LGUcert');
            $fileName = $file->getClientOriginalName();
            $destinationPath = $id;
            $file->move($destinationPath, $fileName);

       $brgyProfile = building::where('id', $id)->first();
       $brgyProfile->bldgLGUcertfilename = $fileName;
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





    public function FireldgpermitUploadfile(Request $request, $id)
    {
        try {
            $file = $request->file('BFPcert');
            $fileName = $file->getClientOriginalName();
            $destinationPath = $id;
            $file->move($destinationPath, $fileName);

       $brgyProfile = building::where('id', $id)->first();
       $brgyProfile->bldgFIREcertfilename = $fileName;
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



    public function OccupancyUploadfile(Request $request, $id)
    {
        try {
            $file = $request->file('occupancy');
            $fileName = $file->getClientOriginalName();
            $destinationPath = $id;
            $file->move($destinationPath, $fileName);

       $brgyProfile = building::where('id', $id)->first();
       $brgyProfile->bldgOCCUPANCYcertfilename = $fileName;
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

}
