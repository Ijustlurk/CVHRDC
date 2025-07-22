<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ResearcherController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\PublicationController;
use App\Http\Controllers\Admin\PartnerController;



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
   // return view('auth.login');
    return view('welcome');
});






// redirects to specific dashboard based on the role of the user
Route::get('/dashboard', function () {
    if(Auth::user()->roles[0]->name == "superadmin")
    {



       // return Auth::user()->roles[0]->name;
        return view('superadmin.dashboard');
    }
    else if(Auth::user()->roles[0]->name == "admin")
    {
       // return Auth::user()->roles[0]->name;
        return view('admin.dashboard');
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



// superadmin routes here
Route::namespace('App\Http\Controllers\SuperAdmin')->prefix('superadmin')->name('superadmin.')->middleware('can:superadmin-access')->group(function(){




});






// admin routes here
Route::prefix('admin')->name('admin.')->middleware('can:admin-access')->group(function(){
    Route::post('news/{id}/visibility', [NewsController::class, 'updateVisibility'])->name('news.visibility');
    Route::post('projects/{id}/visibility', [ProjectController::class, 'updateVisibility'])->name('projects.visibility');



    Route::resource('news', NewsController::class);
    Route::resource('projects', ProjectController::class);
    Route::resource('researchers', ResearcherController::class);
    Route::resource('events', EventController::class);
    Route::resource('publications', PublicationController::class);
    Route::resource('partners', PartnerController::class);


    Route::resource('cms', App\Http\Controllers\Admin\CMSController::class);

    Route::get('tabs/{tab?}', [App\Http\Controllers\Admin\AdminTabsController::class, 'index'])->name('tabs.index');
    Route::post('/admin/news/{id}/visibility', [NewsController::class, 'updateVisibility']);


});




// users routes here
Route::namespace('App\Http\Controllers\Users')->prefix('users')->name('users.')->middleware('can:user-access')->group(function(){

    // add routes here for users
    Route::resource('/feedback','CTRLFeedbacks',['except' => ['update','edit','destroy']]);
    Route::get('/myfeedbacks','CTRLFeedbacks@myfeedback')->name('myfeedback');


});







require __DIR__.'/auth.php';
