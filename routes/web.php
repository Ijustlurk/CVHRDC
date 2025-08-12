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
use App\Http\Controllers\Admin\ResearchController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Users\DashboardController as UserDashboardController;
use App\Http\Controllers\Admin\SettingsController;


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

Route::get('/sample', function () {
   // return view('auth.login');
    return view('sample');
});


// route for the landing page
Route::get('/', function () {
   // return view('auth.login');
    return view('welcome');
});






// redirects to specific dashboard based on the role of the user
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user && $user->roles && count($user->roles) > 0) {
        $role = strtolower($user->roles[0]->name);
        if ($role === 'admin' || $role === 'superadmin') {
            return app(AdminDashboardController::class)->index();
        } else {
            return app(UserDashboardController::class)->index();
        }
    }
    abort(403, 'Unauthorized Access');
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
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function(){
    

    Route::post('news/{id}/visibility', [NewsController::class, 'updateVisibility'])->name('news.visibility');
    Route::post('projects/{id}/visibility', [ProjectController::class, 'updateVisibility'])->name('projects.visibility');
    Route::post('events/{id}/visibility', [EventController::class, 'updateVisibility'])->name('events.visibility');
    Route::post('publications/{id}/visibility', [PublicationController::class, 'updateVisibility'])->name('publications.visibility');
    Route::resource('news', NewsController::class);
    Route::resource('projects', ProjectController::class);
    Route::resource('researchers', ResearcherController::class);
    Route::resource('events', EventController::class);
    Route::resource('publications', PublicationController::class);
    // Research Management
    Route::post('research/{research}/visibility', [ResearchController::class, 'updateVisibility'])->name('research.visibility');
    Route::post('research/{id}/restore', [ResearchController::class, 'restore'])->name('research.restore');
    Route::resource('research', ResearchController::class)->except(['create', 'show']);
    Route::resource('partners', PartnerController::class);


    Route::resource('cms', App\Http\Controllers\Admin\CMSController::class);
    
    // User Management Routes
    Route::resource('users', App\Http\Controllers\Admin\UserManagementController::class);
    Route::post('users/{id}/restore', [App\Http\Controllers\Admin\UserManagementController::class, 'restore'])->name('users.restore');
    Route::resource('roles', App\Http\Controllers\Admin\RoleManagementController::class);

    Route::get('tabs/{tab?}', [App\Http\Controllers\Admin\AdminTabsController::class, 'index'])->name('tabs.index');
    Route::post('/admin/news/{id}/visibility', [NewsController::class, 'updateVisibility']);


    // Admin Footer Management
    Route::get('/footer/edit', [App\Http\Controllers\Admin\FooterController::class, 'edit'])->name('admin.footer.edit');
    Route::post('/footer/update', [App\Http\Controllers\Admin\FooterController::class, 'update'])->name('admin.footer.update');

    // Admin Settings Management
    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/{section}', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');

    // Admin Database Backup Settings Management
    Route::get('/settings/database-backup/download/{file}', [App\Http\Controllers\Admin\DatabaseBackupController::class, 'download'])->name('settings.database_backup.download');
    Route::get('/settings/database-backup', [App\Http\Controllers\Admin\DatabaseBackupController::class, 'show'])->name('settings.database_backup');
    Route::post('/settings/database-backup', [App\Http\Controllers\Admin\DatabaseBackupController::class, 'update'])->name('settings.database_backup.update');
    Route::post('/settings/database-backup/perform', [App\Http\Controllers\Admin\DatabaseBackupController::class, 'performBackup'])->name('settings.database_backup.perform');
    
    // Admin Banner Management
    Route::post('/banner/update', [App\Http\Controllers\Admin\BannerController::class, 'update'])->name('banner.update');
    Route::post('/announcement/update', [App\Http\Controllers\Admin\AnnouncementController::class, 'update'])->name('announcement.update');
    Route::post('/header/update', [App\Http\Controllers\Admin\HeaderController::class, 'update'])->name('header.update');
    
    // Admin Settings Update Maintenance
    Route::prefix('settings')->group(function () {
        Route::post('/update-maintenance', [SettingsController::class, 'updateMaintenance'])->name('settings.updateMaintenance');
    });

});




// users routes here

Route::namespace('App\Http\Controllers\Users')->prefix('users')->name('users.')->middleware('can:user-access')->group(function(){
    // add routes here for users
    Route::resource('/feedback','CTRLFeedbacks',['except' => ['update','edit','destroy']]);
    Route::get('/myfeedbacks','CTRLFeedbacks@myfeedback')->name('myfeedback');
    Route::resource('/products', 'ProductsController')->only(['index']);
});






// // Content Editor routes
// Route::namespace('App\Http\Controllers\ContentEditor')->prefix('contenteditor')->name('contenteditor.')->middleware('can:contenteditor-access')->group(function(){
   
//     Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
//     // CMS Management
//     Route::resource('cms', 'CMSController');
//     // Header, Banner, Footer Settings
//     Route::get('/header/edit', 'HeaderController@edit')->name('header.edit');
//     Route::post('/header/update', 'HeaderController@update')->name('header.update');
//     Route::get('/banner/edit', 'BannerController@edit')->name('banner.edit');
//     Route::post('/banner/update', 'BannerController@update')->name('banner.update');
//     Route::get('/footer/edit', 'FooterController@edit')->name('footer.edit');
//     Route::post('/footer/update', 'FooterController@update')->name('footer.update');
//     // Database Backup (no download)
//     Route::get('/database-backup', 'DatabaseBackupController@show')->name('database_backup');
//     Route::post('/database-backup', 'DatabaseBackupController@update')->name('database_backup.update');
//     Route::post('/database-backup/perform', 'DatabaseBackupController@performBackup')->name('database_backup.perform');
// });



require __DIR__.'/auth.php';
