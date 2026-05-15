<?php

use App\Http\Controllers\Auth;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Common;
use App\Http\Controllers\Common\FeedController;
use App\Http\Controllers\Ngo;
use App\Http\Controllers\People;
use App\Http\Controllers\Website;
use Illuminate\Support\Facades\Route;

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
// For authentication routes
require __DIR__ . '/auth.php';

Route::get('/', function () {
    if (!auth()->check()) {
        return app(App\Http\Controllers\GuestFeedController::class)->index();
    }

    if (in_array(auth()->user()->role_id, [1, 2])) {
        return redirect()->route('common.feed');
    }

    return redirect()->route('admin.dashboard');
})->name('root');
Route::get('user/register', [Admin\UserRegisterController::class, 'showuser'])->name('admin.user.register');


Route::middleware(['auth', 'check.verified'])->group(function () {

    Route::get('/switch-to-ngo/{ngo_id}', [Auth\SettingController::class, 'switchToNgo'])->middleware('role:2')->name('switch.to.ngo');
    Route::get('/switch-back', [Auth\SettingController::class, 'switchBack'])->name('switch.back');

    // Admin routes 
    Route::middleware('role:0')->prefix('admin')->group(function () {

        Route::get('/', [Admin\DashboardController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/dashboard', [Admin\DashboardController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/suspend-ngo/{id}', [Admin\NgoController::class, 'suspend'])->name('admin.ngos.suspend');

        Route::get('/ngos/{id}', [Admin\NgoController::class, 'show'])->name('admin.ngos.show');
        Route::get('/ngo/all', [Admin\NgoController::class, 'getAll'])->name('admin.ngo.all');
        Route::get('/ngo/details/{id}', [Admin\NgoController::class, 'getDetails'])->name('admin.ngo.details');
        Route::get('/ngo/search', [Admin\NgoController::class, 'search'])->name('admin.ngo.search');
        Route::post('/ngos/{id}/verify', [Admin\NgoController::class, 'verifyNgo'])->name('admin.ngos.verify');
        Route::post('/ngos/{id}/reject', [Admin\NgoController::class, 'rejectNgo'])->name('admin.ngos.reject');

        Route::get('/ngos', [Admin\NgoController::class, 'showNgos'])->name('admin.ngos');

        // Routes related to logs
        Route::get('/log', [Admin\LogController::class, 'showLog'])->name('admin.log');

        Route::get('/user/{id}', [Admin\UserController::class, 'show'])->name('admin.user.show');
        Route::get('/user/all', [Admin\UserController::class, 'getAll'])->name('admin.user.all');
        Route::get('/user/details/{id}', [Admin\UserController::class, 'getDetails'])->name('admin.user.details');
        Route::get('/user/search', [Admin\UserController::class, 'search'])->name('admin.user.search');
        Route::get('/admin/user', [Admin\UserController::class, 'index'])->name('admin.users');
        Route::get('/users', [Admin\UserController::class, 'showuser'])->name('admin.users');
        Route::post('/users', [Admin\UserController::class, 'store'])->name('admin.user.store');
        Route::get('/users/{id}', [Admin\UserController::class, 'show'])->name('admin.user.show');
        Route::delete('/users/{id}', [Admin\UserController::class, 'destroy'])->name('admin.user.delete');

        Route::get('/user', [Admin\UserController::class, 'showuser'])->name('admin.user');

        // Routes related to logs
        Route::get('/log', [Admin\LogController::class, 'showLog'])->name('admin.log');
        Route::post('/suspend-user/{id}', [Admin\UserController::class, 'suspend'])->name('admin.users.suspend');
    });

    // NGO routes
    Route::middleware('role:1')->prefix('ngo')->group(function () {

        Route::get('/profile', [Ngo\NgoController::class, 'show'])->name('ngo.profile');
        Route::get('/profile/edit', [Ngo\NgoController::class, 'edit'])->name('ngo.profile.edit');
        Route::put('/profile', [Ngo\NgoController::class, 'update'])->name('ngo.profile.update');

        Route::get('/followers', [Ngo\NgoController::class, 'showFollowers'])->name('ngo.followers');

        Route::get('/events', [Ngo\EventController::class, 'events'])->name('ngo.events');
        Route::get('/events/create', [Ngo\EventController::class, 'createEvent'])->name('ngo.events.create');
        Route::post('/events', [Ngo\EventController::class, 'storeEvent'])->name('ngo.events.store');
        Route::get('/event/{id}/details', [Ngo\EventController::class, 'showEventDetails'])->name('ngo.event.details');
        Route::get('/event/{id}/edit', [Ngo\EventController::class, 'editEventDetails'])->name('ngo.event.edit');
        Route::put('/event/{id}/update', [Ngo\EventController::class, 'updateEventDetails'])->name('ngo.event.update');
        Route::delete('/event/{id}/delete', [Ngo\EventController::class, 'deleteEvent'])->name('ngo.event.delete');

        //Post Deletion Route
        Route::delete('post/{id}/delete', [Ngo\PostController::class, 'deletePost'])->name('ngo.post.delete');

        Route::get('/volunteers', [Ngo\VolunteerController::class, 'volunteers'])->name('ngo.volunteers');
        Route::post('/volunteers/{eventId}/{userId}/verify', [Ngo\VolunteerController::class, 'verifyVolunteer'])->name('ngo.volunteers.verify');

        // Milestone Routes
        Route::post('/events/{eventId}/milestones', [Ngo\MilestoneController::class, 'store'])->name('ngo.milestones.store');
        Route::patch('/milestones/{milestoneId}/status', [Ngo\MilestoneController::class, 'updateStatus'])->name('ngo.milestones.update');
        Route::delete('/milestones/{milestoneId}', [Ngo\MilestoneController::class, 'destroy'])->name('ngo.milestones.delete');

        Route::get('/donations', [Ngo\DonationController::class, 'donations'])->name('ngo.donations');

        Route::get('/notifications', [Ngo\NotificationController::class, 'notifications'])->name('ngo.notifications');
        Route::post('/notifications/{id}/read', [Ngo\NotificationController::class, 'markAsRead'])->name('ngo.notifications.read');

        Route::get('/dashboard', [Ngo\DashboardController::class, 'index'])->name('dashboard');
    });

    // People routes
    Route::middleware('role:2')->prefix('people')->group(function () {
        // User Profile Routes
        Route::get('/profile', [People\ProfileController::class, 'show'])->name('people.profile');
        Route::get('/profile/edit', [People\ProfileController::class, 'edit'])->name('people.profile.edit');
        Route::put('/profile', [People\ProfileController::class, 'update'])->name('people.profile.update');

        // Newsfeed Routes
        Route::get('/newsfeed', [People\NewsfeedController::class, 'index'])->name('people.newsfeed');

        // Volunteer Opportunities Routes
        Route::get('/volunteer/opportunities', [People\VolunteerController::class, 'index'])->name('people.volunteer.opportunities');
        Route::post('/volunteer/apply', [People\VolunteerController::class, 'apply'])->name('people.volunteer.apply');
        Route::get('/volunteer/{id}/details', [People\VolunteerController::class, 'showEventDetails'])->name('people.volunteer.details');

        // Notifications Routes
        Route::get('/notifications', [People\NotificationController::class, 'index'])->name('people.notifications');
        Route::post('/notifications/{id}/read', [People\NotificationController::class, 'markAsRead'])->name('people.notifications.read');

        // Recommendations Route (AI feature)
        Route::get('/recommendations', [People\RecommendationController::class, 'index'])->name('people.recommendations');

        Route::get('/ngo/register', [People\NgoRegisterController::class, 'showRegistrationForm'])->name('people.ngo.register.form');
        Route::post('/ngo/register', [People\NgoRegisterController::class, 'register'])->name('people.ngo.register');

        // NGO Profile Routes
        Route::get('/ngo/{id}', [People\NgoProfileController::class, 'show'])->name('people.ngo.profile');
        Route::post('/ngo/{id}/favorite', [People\NgoProfileController::class, 'toggleFavorite'])->name('people.ngo.favorite');

        // QR Check-in Route
        Route::get('/event/check-in/{token}', [People\AttendanceController::class, 'checkIn'])->name('people.event.checkin');
    });

    // Shared routes (ngo and people, role_id=1,2)
    Route::middleware('role:1,2')->group(function () {
        Route::get('/feed', [Common\FeedController::class, 'index'])->name('common.feed');
        Route::post('/feed', [Common\FeedController::class, 'create'])->name('common.post.create');
        Route::get('/ngo/profile/{id}', [Common\NgoProfileController::class, 'index'])->name('common.ngo.profile');
        Route::get('/ngo/profile/{id}/feed', [Common\NgoProfileController::class, 'feed'])->name('common.ngo.profile.feed');
        Route::post('/post/like', [Common\FeedController::class, 'like'])->name('common.post.like');
        Route::post('/post/comment', [Common\FeedController::class, 'comment'])->name('common.post.comment');
        Route::post('/post/report', [Common\FeedController::class, 'report'])->name('common.post.report');
        Route::post('/ngo/follow', [Common\FeedController::class, 'follow'])->name('common.ngo.follow');
        Route::post('/feed/location', [Common\FeedController::class, 'updateLocation'])->name('common.feed.location');
        Route::get('/ngos/search', [People\NgoSearchController::class, 'index'])->name('people.ngo.search');

        // Messaging Routes
        Route::get('/messages', [Common\MessagingController::class, 'index'])->name('common.messages.index');
        Route::get('/messages/{userId}', [Common\MessagingController::class, 'show'])->name('common.messages.show');
        Route::get('/messages/api/get-messages/{userId}', [Common\MessagingController::class, 'getMessages'])->name('common.messages.getMessages');
        Route::post('/messages', [Common\MessagingController::class, 'store'])->name('common.messages.store');

        // Community Circle Routes
        Route::get('/ngo/{ngoId}/circle', [Common\CircleController::class, 'index'])->name('common.circles.index');
        Route::get('/circle/thread/{threadId}', [Common\CircleController::class, 'show'])->name('common.circles.show');
        Route::post('/ngo/{ngoId}/circle/thread', [Common\CircleController::class, 'storeThread'])->name('common.circles.storeThread');
        Route::post('/circle/thread/{threadId}/reply', [Common\CircleController::class, 'storeReply'])->name('common.circles.storeReply');


        // routes/web.php
        Route::delete('/posts/{post}/report', [Common\FeedController::class, 'undoReport'])
            ->name('post.undo-report')
            ->middleware('auth');
    });
});

// Route::get('/privacy', [Website\StaticPageController::class, 'privacy'])->name('privacy');
// Route::get('/terms', [Website\StaticPageController::class, 'terms'])->name('terms');
// Route::get('/advertising', [Website\StaticPageController::class, 'advertising'])->name('advertising');
// Route::get('/more', [Website\StaticPageController::class, 'more'])->name('more');
