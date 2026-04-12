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
use App\Http\Controllers\Admin\ConferencePaperController;
use App\Http\Controllers\Admin\PreprintController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Users\DashboardController as UserDashboardController;
use App\Http\Controllers\RMC\DashboardController as RMCDashboardController;
use App\Http\Controllers\RUC\DashboardController as RUCDashboardController;
use App\Http\Controllers\RMC\CallForProposalController;
use App\Http\Controllers\RMC\ProposalSubmissionController;
use App\Http\Controllers\RUC\CallForProposalController as RUCallForProposalController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Users\UserResearchCTRL;
use App\Http\Controllers\Users\UserPreprintController;
use App\Http\Controllers\Users\UserConferencePaperController;
use App\Http\Controllers\Users\UserBookController;
use App\Http\Controllers\Users\UserProposalSubmissionController;





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
})->name('welcome');

// Public routes for calls
Route::get('/calls', [App\Http\Controllers\Public\CallController::class, 'index'])->name('public.calls.index');
Route::get('/calls/{id}', [App\Http\Controllers\Public\CallController::class, 'show'])->name('public.calls.show');
Route::get('/calls/{id}/download-format', [App\Http\Controllers\Public\CallController::class, 'downloadFormat'])->name('public.calls.download-format');
Route::get('/api/public-calls', [App\Http\Controllers\Public\CallController::class, 'getPublicCalls'])->name('api.public-calls');






// redirects to specific dashboard based on the role of the user
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user && $user->roles && count($user->roles) > 0) {
        $role = strtolower($user->roles[0]->name);
        if ($role === 'admin' || $role === 'superadmin') {
            return app(AdminDashboardController::class)->index();
        } elseif ($role === 'rmc') {
            return app(RMCDashboardController::class)->index();
        } elseif ($role === 'ruc') {
            return app(RUCDashboardController::class)->index();
        } elseif ($role === 'peer review coordinator') {
            return redirect()->route('coordinator.dashboard');
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
    Route::get('research/{research}', [ResearchController::class, 'show'])->name('research.show');
    Route::post('research/{research}/visibility', [ResearchController::class, 'updateVisibility'])->name('research.visibility');
    Route::post('research/{id}/restore', [ResearchController::class, 'restore'])->name('research.restore');
    Route::delete('research/{id}/force-delete', [ResearchController::class, 'forceDelete'])->name('research.force-delete');
    Route::resource('research', ResearchController::class)->except(['create', 'show']);

    // Conference Papers Management
    Route::get('conference-papers/{conference_paper}', [ConferencePaperController::class, 'show'])->name('conference-papers.show');
    Route::post('conference-papers/{conference_paper}/visibility', [ConferencePaperController::class, 'updateVisibility'])->name('conference-papers.visibility');
    Route::post('conference-papers/{id}/restore', [ConferencePaperController::class, 'restore'])->name('conference-papers.restore');
    Route::delete('conference-papers/{id}/force-delete', [ConferencePaperController::class, 'forceDelete'])->name('conference-papers.force-delete');
    Route::resource('conference-papers', ConferencePaperController::class)->except(['create', 'show']);

    // Preprints Management
    Route::get('preprints/{preprint}', [PreprintController::class, 'show'])->name('preprints.show');
    Route::post('preprints/{preprint}/visibility', [PreprintController::class, 'updateVisibility'])->name('preprints.visibility');
    Route::post('preprints/{id}/restore', [PreprintController::class, 'restore'])->name('preprints.restore');
    Route::delete('preprints/{id}/force-delete', [PreprintController::class, 'forceDelete'])->name('preprints.force-delete');
    Route::resource('preprints', PreprintController::class)->except(['create', 'show']);

    // Books Management
    Route::get('books/{book}', [BookController::class, 'show'])->name('books.show');
    Route::post('books/{book}/visibility', [BookController::class, 'updateVisibility'])->name('books.visibility');
    Route::post('books/{id}/restore', [BookController::class, 'restore'])->name('books.restore');
    Route::delete('books/{id}/force-delete', [BookController::class, 'forceDelete'])->name('books.force-delete');
    Route::resource('books', BookController::class)->except(['create', 'show']);
    // Partners Management
    Route::post('partners/{id}/visibility', [PartnerController::class, 'updateVisibility'])->name('partners.visibility');
    Route::resource('partners', PartnerController::class);

    // Announcements Management
    Route::post('announcements/{id}/visibility', [AnnouncementController::class, 'updateVisibility'])->name('announcements.visibility');
    Route::resource('announcements', AnnouncementController::class);

    Route::resource('cms', App\Http\Controllers\Admin\CMSController::class);

    // User Management Routes
    Route::resource('users', App\Http\Controllers\Admin\UserManagementController::class);
    Route::post('users/{id}/restore', [App\Http\Controllers\Admin\UserManagementController::class, 'restore'])->name('users.restore');
    Route::resource('roles', App\Http\Controllers\Admin\RoleManagementController::class);

    Route::get('tabs/{tab?}', [App\Http\Controllers\Admin\AdminTabsController::class, 'index'])->name('tabs.index');



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
    Route::post('/header/update', [App\Http\Controllers\Admin\HeaderController::class, 'update'])->name('header.update');

    // Admin Settings Update Maintenance
    Route::prefix('settings')->group(function () {
        Route::post('/update-maintenance', [SettingsController::class, 'updateMaintenance'])->name('settings.updateMaintenance');
    });

});


// RMC (Research Management Committee) routes here
Route::middleware(['auth', 'rmc'])->prefix('rmc')->name('rmc.')->group(function () {
    Route::get('/dashboard', [RMCDashboardController::class, 'index'])->name('dashboard');

    // Calls for Proposals Management
    Route::resource('calls', CallForProposalController::class);
    Route::post('calls/{id}/visibility', [CallForProposalController::class, 'updateVisibility'])->name('calls.visibility');
    Route::get('calls/{id}/download-format', [CallForProposalController::class, 'downloadFormatFile'])->name('calls.download-format');

    // General Submissions Management (all submissions for user's calls)
    Route::get('submissions', [ProposalSubmissionController::class, 'index'])->name('submissions.index');

    // Proposal Submissions Management (for specific calls)
    Route::get('calls/{callId}/submissions', [ProposalSubmissionController::class, 'showCallSubmissions'])->name('submissions.call');
    Route::get('calls/{callId}/submissions/{submissionId}', [ProposalSubmissionController::class, 'show'])->name('submissions.show');
    Route::post('calls/{callId}/submissions/{submissionId}/status', [ProposalSubmissionController::class, 'updateStatus'])->name('submissions.status');
    Route::get('calls/{callId}/submissions/{submissionId}/download', [ProposalSubmissionController::class, 'downloadDocument'])->name('submissions.download');
});


// Peer Review Coordinator routes here
Route::middleware(['auth', 'coordinator'])->prefix('coordinator')->name('coordinator.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Coordinator\PeerReviewCoordinatorController::class, 'index'])->name('dashboard');
    Route::get('/submissions', [App\Http\Controllers\Coordinator\PeerReviewCoordinatorController::class, 'submissions'])->name('submissions.index');
    Route::get('/submissions/{id}', [App\Http\Controllers\Coordinator\PeerReviewCoordinatorController::class, 'show'])->name('submissions.show');
    Route::get('/submissions/{id}/download', [App\Http\Controllers\Coordinator\PeerReviewCoordinatorController::class, 'download'])->name('submissions.download');
    Route::post('/submissions/assign', [App\Http\Controllers\Coordinator\PeerReviewCoordinatorController::class, 'assignReviewer'])->name('submissions.assign');
    Route::delete('/submissions/{submissionId}/unassign/{reviewId}', [App\Http\Controllers\Coordinator\PeerReviewCoordinatorController::class, 'unassignReviewer'])->name('submissions.unassign');
});

Route::middleware(['auth', 'reviewer'])->prefix('reviewer')->name('reviewer.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Reviewer\PeerReviewController::class, 'dashboard'])->name('dashboard');
    Route::get('/reviews', [App\Http\Controllers\Reviewer\PeerReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{id}/accept', [App\Http\Controllers\Reviewer\PeerReviewController::class, 'accept'])->name('reviews.accept');
    Route::post('/reviews/{id}/decline', [App\Http\Controllers\Reviewer\PeerReviewController::class, 'decline'])->name('reviews.decline');
});


// RUC (Research Utilization Committee) routes here
Route::middleware(['auth', 'ruc'])->prefix('ruc')->name('ruc.')->group(function () {
    Route::get('/dashboard', [RUCDashboardController::class, 'index'])->name('dashboard');

    // Review Calls Management
    Route::get('calls', [RUCallForProposalController::class, 'index'])->name('calls.index');
    Route::get('calls/{id}', [RUCallForProposalController::class, 'show'])->name('calls.show');
    Route::post('calls/{id}/status', [RUCallForProposalController::class, 'updateStatus'])->name('calls.status');
    Route::get('calls/{id}/download-format', [RUCallForProposalController::class, 'downloadFormatFile'])->name('calls.download-format');

    // Specialized views for RUC
    Route::get('review-calls', [RUCallForProposalController::class, 'getForReview'])->name('calls.review');
    Route::get('published-calls', [RUCallForProposalController::class, 'getPublished'])->name('calls.published');
});


// users routes here

Route::namespace('App\Http\Controllers\Users')->prefix('users')->name('users.')->middleware('can:user-access')->group(function(){
    // add routes here for users
    Route::resource('/feedback','CTRLFeedbacks',['except' => ['update','edit','destroy']]);
    Route::get('/myfeedbacks','CTRLFeedbacks@myfeedback')->name('myfeedback');
    Route::resource('/products', 'ProductsController')->only(['index']);
    
    // Research Management Routes
    Route::get('/research', [UserResearchCTRL::class, 'index'])->name('research.index');
    Route::get('/research/{research}', [UserResearchCTRL::class, 'show'])->name('research.show');
    Route::post('/research/store', [UserResearchCTRL::class, 'store'])->name('research.store');
    Route::get('/research/{research}/edit', [UserResearchCTRL::class, 'edit'])->name('research.edit');
    Route::put('/research/{research}', [UserResearchCTRL::class, 'update'])->name('research.update');
    Route::delete('/research/{research}', [UserResearchCTRL::class, 'destroy'])->name('research.destroy');
    Route::post('/research/{research}/visibility', [UserResearchCTRL::class, 'updateVisibility'])->name('research.visibility');
    Route::post('/research/{id}/restore', [UserResearchCTRL::class, 'restore'])->name('research.restore');
    Route::delete('/research/{id}/force-delete', [UserResearchCTRL::class, 'forceDelete'])->name('research.force-delete');
    
    // Preprint Management Routes
    Route::get('/preprints', [UserPreprintController::class, 'index'])->name('preprints.index');
    Route::get('/preprints/{preprint}', [UserPreprintController::class, 'show'])->name('preprints.show');
    Route::post('/preprints/store', [UserPreprintController::class, 'store'])->name('preprints.store');
    Route::get('/preprints/{preprint}/edit', [UserPreprintController::class, 'edit'])->name('preprints.edit');
    Route::put('/preprints/{preprint}', [UserPreprintController::class, 'update'])->name('preprints.update');
    Route::delete('/preprints/{preprint}', [UserPreprintController::class, 'destroy'])->name('preprints.destroy');
    Route::post('/preprints/{preprint}/visibility', [UserPreprintController::class, 'updateVisibility'])->name('preprints.visibility');
    Route::post('/preprints/{id}/restore', [UserPreprintController::class, 'restore'])->name('preprints.restore');
    Route::delete('/preprints/{id}/force-delete', [UserPreprintController::class, 'forceDelete'])->name('preprints.force-delete');
    
    // Conference Paper Management Routes
    Route::get('/conference-papers', [UserConferencePaperController::class, 'index'])->name('conference-papers.index');
    Route::get('/conference-papers/{conference_paper}', [UserConferencePaperController::class, 'show'])->name('conference-papers.show');
    Route::post('/conference-papers/store',[UserConferencePaperController::class, 'store'])->name('conference-papers.store');
    Route::get('/conference-papers/{conference_paper}/edit', [UserConferencePaperController::class, 'edit'])->name('conference-papers.edit');
    Route::put('/conference-papers/{conference_paper}', [UserConferencePaperController::class, 'update'])->name('conference-papers.update');
    Route::delete('/conference-papers/{conference_paper}', [UserConferencePaperController::class, 'destroy'])->name('conference-papers.destroy');
    Route::post('/conference-papers/{conference_paper}/visibility', [UserConferencePaperController::class, 'updateVisibility'])->name('conference-papers.visibility');
    Route::post('/conference-papers/{id}/restore', [UserConferencePaperController::class, 'restore'])->name('conference-papers.restore');
    Route::delete('/conference-papers/{id}/force-delete', [UserConferencePaperController::class, 'forceDelete'])->name('conference-papers.force-delete');
    
    // Book Management Routes
    Route::get('/books', [UserBookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [UserBookController::class, 'show'])->name('books.show');
    Route::post('/books/store',[UserBookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}/edit', [UserBookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{book}', [UserBookController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}', [UserBookController::class, 'destroy'])->name('books.destroy');
    Route::post('/books/{book}/visibility', [UserBookController::class, 'updateVisibility'])->name('books.visibility');
    Route::post('/books/{id}/restore', [UserBookController::class, 'restore'])->name('books.restore');
    Route::delete('/books/{id}/force-delete', [UserBookController::class, 'forceDelete'])->name('books.force-delete');

    // Proposal Submission Routes
    Route::get('/proposal-submissions', [UserProposalSubmissionController::class, 'index'])->name('proposal-submissions.index');
    Route::get('/proposal-submissions/create', [UserProposalSubmissionController::class, 'create'])->name('proposal-submissions.create');
    Route::post('/proposal-submissions', [UserProposalSubmissionController::class, 'store'])->name('proposal-submissions.store');
    Route::get('/proposal-submissions/{proposalSubmission}', [UserProposalSubmissionController::class, 'show'])->name('proposal-submissions.show');
    Route::get('/proposal-submissions/{proposalSubmission}/download', [UserProposalSubmissionController::class, 'download'])->name('proposal-submissions.download');
    
    // File download routes
    Route::get('/download/research/{id}', function($id) {
        $research = \App\Models\Research::findOrFail($id);
        if (auth()->id() === $research->user_id) {
            return response()->download(storage_path('app/public/' . $research->file_path), $research->file_original_name);
        }
        abort(403);
    })->name('download.research');
    
    Route::get('/download/preprint/{id}', function($id) {
        $preprint = \App\Models\Preprint::findOrFail($id);
        if (auth()->id() === $preprint->user_id) {
            return response()->download(storage_path('app/public/' . $preprint->file_path), $preprint->file_original_name);
        }
        abort(403);
    })->name('download.preprint');
    
    Route::get('/download/conference-paper/{id}', function($id) {
        $paper = \App\Models\ConferencePaper::findOrFail($id);
        if (auth()->id() === $paper->user_id) {
            return response()->download(storage_path('app/public/' . $paper->file_path), $paper->file_original_name);
        }
        abort(403);
    })->name('download.conference-paper');
    
    Route::get('/download/book/{id}', function($id) {
        $book = \App\Models\Book::findOrFail($id);
        if (auth()->id() === $book->user_id) {
            return response()->download(storage_path('app/public/' . $book->file_path), $book->file_original_name);
        }
        abort(403);
    })->name('download.book');
    
    // Dashboard data feeds for research outputs
    Route::get('/research/list', function () {
        $userId = Auth::id();
        return \App\Models\Research::query()
            ->where('user_id', $userId)
            ->latest('published_at')
            ->take(50)
            ->get(['id','title','authors','published_at','abstract','category','keywords','file_path','file_original_name','file_size'])
            ->map(function($r){
                return [
                    'id' => $r->id,
                    'title' => $r->title,
                    'authors' => $r->authors,
                    'published_at' => optional($r->published_at)->toDateString(),
                    'abstract' => $r->abstract,
                    'category' => $r->category,
                    'keywords' => $r->keywords,
                    'file' => $r->file_original_name,
                    'file_path' => $r->file_path,
                    'file_size' => $r->file_size,
                    'type' => 'Research',
                ];
            });
    })->name('research.list');

    Route::get('/preprints/list', function () {
        $userId = Auth::id();
        return \App\Models\Preprint::query()
            ->where('user_id', $userId)
            ->latest('published_at')
            ->take(50)
            ->get(['id','title','authors','published_at','abstract','repository_name','keywords','file_path','file_original_name','file_size'])
            ->map(function($p){
                return [
                    'id' => $p->id,
                    'title' => $p->title,
                    'authors' => $p->authors,
                    'published_at' => optional($p->published_at)->toDateString(),
                    'abstract' => $p->abstract,
                    'repository_name' => $p->repository_name,
                    'keywords' => $p->keywords,
                    'file' => $p->file_original_name,
                    'file_path' => $p->file_path,
                    'file_size' => $p->file_size,
                    'type' => 'Preprint',
                ];
            });
    })->name('preprints.list');

    Route::get('/conference-papers/list', function () {
        $userId = Auth::id();
        return \App\Models\ConferencePaper::query()
            ->where('user_id', $userId)
            ->latest('published_at')
            ->take(50)
            ->get(['id','title','authors','published_at','abstract','conference_name','conference_location','publisher','keywords','file_path','file_original_name','file_size'])
            ->map(function($c){
                return [
                    'id' => $c->id,
                    'title' => $c->title,
                    'authors' => $c->authors,
                    'published_at' => optional($c->published_at)->toDateString(),
                    'abstract' => $c->abstract,
                    'conference_name' => $c->conference_name,
                    'conference_location' => $c->conference_location,
                    'publisher' => $c->publisher,
                    'keywords' => $c->keywords,
                    'file' => $c->file_original_name,
                    'file_path' => $c->file_path,
                    'file_size' => $c->file_size,
                    'type' => 'Conference Paper',
                ];
            });
    })->name('conference-papers.list');

    Route::get('/books/list', function () {
        $userId = Auth::id();
        return \App\Models\Book::query()
            ->where('user_id', $userId)
            ->latest('published_at')
            ->take(50)
            ->get(['id','title','authors','editors','published_at','publisher','isbn','edition','doi','keywords','file_path','file_original_name','file_size'])
            ->map(function($b){
                return [
                    'id' => $b->id,
                    'title' => $b->title,
                    'authors' => $b->authors,
                    'editors' => $b->editors,
                    'published_at' => optional($b->published_at)->toDateString(),
                    'publisher' => $b->publisher,
                    'isbn' => $b->isbn,
                    'edition' => $b->edition,
                    'doi' => $b->doi,
                    'keywords' => $b->keywords,
                    'file' => $b->file_original_name,
                    'file_path' => $b->file_path,
                    'file_size' => $b->file_size,
                    'type' => 'Book',
                ];
            });
    })->name('books.list');

    // Activity feed across research outputs (research, preprints, conference papers, books)
    Route::get('/activity/list', function () {
        $userId = Auth::id();

        $events = [];

        $pushEvent = function (string $type, ?string $title, string $action, $timestamp) use (&$events) {
            if ($timestamp) {
                $events[] = [
                    'type' => $type,
                    'title' => $title ?? 'Untitled',
                    'action' => $action,
                    'timestamp' => $timestamp->toIso8601String(),
                ];
            }
        };

        // Research
        \App\Models\Research::withTrashed()
            ->where('user_id', $userId)
            ->latest('updated_at')
            ->take(200)
            ->get(['id','title','created_at','updated_at','deleted_at'])
            ->each(function ($r) use ($pushEvent) {
                $pushEvent('Research', $r->title, 'created', $r->created_at);
                if ($r->updated_at && $r->updated_at->ne($r->created_at)) {
                    $pushEvent('Research', $r->title, 'updated', $r->updated_at);
                }
                if ($r->deleted_at) {
                    $pushEvent('Research', $r->title, 'deleted', $r->deleted_at);
                }
            });

        // Preprints
        \App\Models\Preprint::withTrashed()
            ->where('user_id', $userId)
            ->latest('updated_at')
            ->take(200)
            ->get(['id','title','created_at','updated_at','deleted_at'])
            ->each(function ($p) use ($pushEvent) {
                $pushEvent('Preprint', $p->title, 'created', $p->created_at);
                if ($p->updated_at && $p->updated_at->ne($p->created_at)) {
                    $pushEvent('Preprint', $p->title, 'updated', $p->updated_at);
                }
                if ($p->deleted_at) {
                    $pushEvent('Preprint', $p->title, 'deleted', $p->deleted_at);
                }
            });

        // Conference Papers
        \App\Models\ConferencePaper::withTrashed()
            ->where('user_id', $userId)
            ->latest('updated_at')
            ->take(200)
            ->get(['id','title','created_at','updated_at','deleted_at'])
            ->each(function ($c) use ($pushEvent) {
                $pushEvent('Conference Paper', $c->title, 'created', $c->created_at);
                if ($c->updated_at && $c->updated_at->ne($c->created_at)) {
                    $pushEvent('Conference Paper', $c->title, 'updated', $c->updated_at);
                }
                if ($c->deleted_at) {
                    $pushEvent('Conference Paper', $c->title, 'deleted', $c->deleted_at);
                }
            });

        // Books
        \App\Models\Book::withTrashed()
            ->where('user_id', $userId)
            ->latest('updated_at')
            ->take(200)
            ->get(['id','title','created_at','updated_at','deleted_at'])
            ->each(function ($b) use ($pushEvent) {
                $pushEvent('Book', $b->title, 'created', $b->created_at);
                if ($b->updated_at && $b->updated_at->ne($b->created_at)) {
                    $pushEvent('Book', $b->title, 'updated', $b->updated_at);
                }
                if ($b->deleted_at) {
                    $pushEvent('Book', $b->title, 'deleted', $b->deleted_at);
                }
            });

        usort($events, function ($a, $b) {
            return strcmp($b['timestamp'], $a['timestamp']);
        });

        return array_slice($events, 0, 50);
    })->name('activity.list');
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
