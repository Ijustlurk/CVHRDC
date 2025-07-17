<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/




// route for the landing page
Route::get('/', function () {
    return view('auth.login');
    //return view('welcome');
});






// redirects to specific dashboard based on the role of the user
Route::get('/dashboard', function () {
    if(Auth::user()->roles[0]->name == "admin")
    {





            // get all records per campus
            $allcampuses = DB::table('buildings')
            ->select('campus', DB::raw('count(*) as total'),  DB::raw('SUM(bldgfloorarea) as totalfloorareas'))
            ->groupBy('campus')
            ->get();

       // return Auth::user()->roles[0]->name;
        return view('admin.dashboard',compact(['allcampuses']));
    }
    else if(Auth::user()->roles[0]->name == "campus")
    {
       // return Auth::user()->roles[0]->name;
        return view('campus.dashboard');
    }
    else
    {
        // return Auth::user()->roles[0]->name;
        return view('users.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




// admin routes here
Route::namespace('App\Http\Controllers\Admin')->prefix('admin')->name('admin.')->middleware('can:admin-access')->group(function(){

    // add routes here for admin
    Route::resource('/users','UserController',['except' => ['create','store','destroy']]);
    Route::get('/userfeedbacks','UserController@userfeedback')->name('userfeedback');

    Route::resource('/logs','CTRLlogs');

    Route::resource('/bldg','CTRLbldgprofile'); // route for function index will return the blade

    Route::get('/listbldg', 'CTRLbldgprofile@listbldg')->name('admin.building.listbldg'); // will load the data from the function listbldg


    Route::post('/bldgupdate1','CTRLbldgprofile@bldgupdate1')->name('bldgupdate1');


    Route::resource('/inventory','CTRLinventory');
    Route::resource('/rooms','CTRLrooms');

    Route::get('/about', function () {
        return view('admin.about');
    })->name('about');


    // delete, upload,
    Route::get('viewbldg/{id}','CTRLbldgprofile@ViewBldg')->name('ViewBldg');
    Route::get('ulbldgcert/{id}','CTRLbldgprofile@Uploadfile')->name('Uploadfile');

    Route::get('dsplyselcam/{id}','CTRLbldgprofile@dsplyselcam')->name('dsplyselcam');


    //  for the map of selected campus
    Route::get('dsplyselcampusmap/{id}','CTRLbldgprofile@dsplyselcampusmap')->name('dsplyselcampusmap');




});







// campus routes here
Route::namespace('App\Http\Controllers\Campus')->prefix('campus')->name('campus.')->middleware('can:campus-access')->group(function(){

    // add routes here for admin

    Route::resource('/bldg','CTRLbldgprofile');


    Route::resource('/inventory','CTRLinventory');
    Route::resource('/rooms','CTRLrooms');
    Route::post('/bldgupdate1','CTRLbldgprofile@bldgupdate1')->name('bldgupdate1');


    Route::get('ulbldgLGUpermit/{id}','CTRLbldgprofile@LGUbldgpermitUploadfile')->name('LGUbldgpermitUploadfile');
    Route::get('ulbldgFIREpermit/{id}','CTRLbldgprofile@FireldgpermitUploadfile')->name('FireldgpermitUploadfile');
    Route::get('ulbldgOccupancy/{id}','CTRLbldgprofile@OccupancyUploadfile')->name('OccupancyUploadfile');

});




// users routes here
Route::namespace('App\Http\Controllers\Users')->prefix('users')->name('users.')->middleware('can:user-access')->group(function(){

    // add routes here for users
    Route::resource('/feedback','CTRLFeedbacks',['except' => ['update','edit','destroy']]);

    Route::get('/myfeedbacks','CTRLFeedbacks@myfeedback')->name('myfeedback');

    Route::resource('/products','CTRLproducts');

    // eto route ng search products
    Route::get('/searchproducts','CTRLproducts@searchproducts')->name('searchproducts');

});







require __DIR__.'/auth.php';
