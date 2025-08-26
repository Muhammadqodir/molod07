<?php

use App\Http\Controllers\Admin\AdministratorsController;
use App\Http\Controllers\Admin\ManageEventsController;
use App\Http\Controllers\Admin\ManageGrantsController;
use App\Http\Controllers\Admin\ManageNewsController;
use App\Http\Controllers\Admin\PartnersController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\YouthController;
use App\Http\Controllers\Auth\RegisterPartnerController;
use App\Http\Controllers\Auth\RegisterYouthController;
use App\Http\Controllers\Profiles\PartnerProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\Profiles\AdminProfileController;
use App\Http\Controllers\Profiles\YouthProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('pages.main');
})->name("main");

Route::middleware('guest')->group(function () {
    Route::get('/register/youth', [RegisterYouthController::class, 'show'])->name('youth.reg');
    Route::post('/register/youth', [RegisterYouthController::class, 'register'])->name("youth.reg.post");

    Route::get('/register/partner', [RegisterPartnerController::class, 'show'])->name('partner.reg');
    Route::post('/register/partner', [RegisterPartnerController::class, 'register'])->name("partner.reg.post");
});


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'view'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

//Youth routes
Route::middleware(['auth', 'role:youth'])->group(function () {
    Route::get('/profile/youth', [YouthProfileController::class, 'show'])->name('youth.profile');
    Route::post('/profile/youth/post', [YouthProfileController::class, 'updateProfile'])->name('youth.profile.post');
});


//Partner routes
Route::middleware(['auth', 'role:partner'])->group(function () {
    Route::get('/profile/partner', [PartnerProfileController::class, 'show'])->name('partner.profile');
    Route::post('/profile/partner/post', [PartnerProfileController::class, 'updateProfile'])->name('partner.profile.post');
});

//Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/profile/admin', [AdminProfileController::class, 'show'])->name('admin.profile');
    Route::post('/profile/admin/post', [AdminProfileController::class, 'updateProfile'])->name('admin.profile.post');

    //Administrators
    Route::get('admin/manage/administrators', [AdministratorsController::class, 'show'])->name('admin.manage.administrators');
    Route::get('admin/manage/administrators/create', [AdministratorsController::class, 'createShow'])->name('admin.manage.administrators.create');
    Route::post('admin/manage/administrators/create/post', [AdministratorsController::class, 'createAdminPost'])->name('admin.manage.administrators.create.post');
    Route::post('admin/manage/administrators/remove', [AdministratorsController::class, 'remove'])->name('admin.manage.administrators.remove');
    Route::post('admin/manage/administrators/block', [AdministratorsController::class, 'block'])->name('admin.manage.administrators.block');
    Route::post('admin/manage/administrators/unblock', [AdministratorsController::class, 'unblock'])->name('admin.manage.administrators.unblock');

    //Youth
    Route::get('admin/manage/youth', [YouthController::class, 'show'])->name('admin.manage.youth');
    Route::post('admin/manage/youth/remove', [YouthController::class, 'remove'])->name('admin.manage.youth.remove');
    Route::post('admin/manage/youth/block', [YouthController::class, 'block'])->name('admin.manage.youth.block');
    Route::post('admin/manage/youth/unblock', [YouthController::class, 'unblock'])->name('admin.manage.youth.unblock');

    //Partners
    Route::get('admin/manage/partners', [PartnersController::class, 'show'])->name('admin.manage.partners');
    Route::post('admin/manage/partners/remove', [PartnersController::class, 'remove'])->name('admin.manage.partners.remove');
    Route::post('admin/manage/partners/block', [PartnersController::class, 'block'])->name('admin.manage.partners.block');
    Route::post('admin/manage/partners/unblock', [PartnersController::class, 'unblock'])->name('admin.manage.partners.unblock');

    //Events
    Route::get('admin/events/list', [ManageEventsController::class, 'show'])->name('admin.events.index')->defaults('status', 'approved');
    Route::get('admin/events/requests', [ManageEventsController::class, 'show'])->name('admin.events.requests')->defaults('status', 'pending');
    Route::get('admin/events/archive', [ManageEventsController::class, 'show'])->name('admin.events.archive')->defaults('status', 'archived');
    Route::get('admin/events/create', [ManageEventsController::class, 'create'])->name('admin.events.create');
    Route::post('admin/events/create', [ManageEventsController::class, 'store'])->name('admin.events.store');
    Route::get('admin/events/preview/{id}', [ManageEventsController::class, 'preview'])->name('admin.events.preview');
    Route::post('admin/events/approve/{id}', [ManageEventsController::class, 'approve'])->name('admin.events.approve');
    Route::post('admin/events/reject/{id}', [ManageEventsController::class, 'reject'])->name('admin.events.reject');
    Route::post('admin/events/action/archive/{id}', [ManageEventsController::class, 'archive'])->name('admin.events.action.archive');

    //News
    Route::get('admin/news/list', [ManageNewsController::class, 'show'])->name('admin.news.index')->defaults('status', 'published');
    Route::get('admin/news/requests', [ManageNewsController::class, 'show'])->name('admin.news.requests')->defaults('status', 'pending');
    Route::get('admin/news/archive', [ManageNewsController::class, 'show'])->name('admin.news.archive')->defaults('status', 'archived');
    Route::get('admin/news/create', [ManageNewsController::class, 'create'])->name('admin.news.create');
    Route::post('admin/news/create', [ManageNewsController::class, 'store'])->name('admin.news.store');
    Route::get('admin/news/preview/{id}', [ManageNewsController::class, 'preview'])->name('admin.news.preview');
    Route::post('admin/news/approve/{id}', [ManageNewsController::class, 'approve'])->name('admin.news.approve');
    Route::post('admin/news/reject/{id}', [ManageNewsController::class, 'reject'])->name('admin.news.reject');
    Route::post('admin/news/action/archive/{id}', [ManageNewsController::class, 'archive'])->name('admin.news.action.archive');

    //Grants
    Route::get('admin/grants/list', [ManageGrantsController::class, 'show'])->name('admin.grants.index')->defaults('status', 'approved');
    Route::get('admin/grants/requests', [ManageGrantsController::class, 'show'])->name('admin.grants.requests')->defaults('status', 'pending');
    Route::get('admin/grants/archive', [ManageGrantsController::class, 'show'])->name('admin.grants.archive')->defaults('status', 'archived');
    Route::get('admin/grants/create', [ManageGrantsController::class, 'create'])->name('admin.grants.create');
    Route::post('admin/grants/create', [ManageGrantsController::class, 'store'])->name('admin.grants.store');
    Route::get('admin/grants/preview/{id}', [ManageGrantsController::class, 'preview'])->name('admin.grants.preview');
    Route::post('admin/grants/approve/{id}', [ManageGrantsController::class, 'approve'])->name('admin.grants.approve');
    Route::post('admin/grants/reject/{id}', [ManageGrantsController::class, 'reject'])->name('admin.grants.reject');
    Route::post('admin/grants/action/archive/{id}', [ManageGrantsController::class, 'archive'])->name('admin.grants.action.archive');

    //Support
    Route::get('admin/support', [SupportController::class, 'show'])->name('admin.support');

    Route::get('/admin/get-user/{id}', function ($id) {
        $id = ltrim($id, '0');
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Пользователь не найден'], 404);
        }
        return response()->json([
            'name' => $user->getProfile()->name,
            'l_name' => $user->getProfile()->l_name,
            'phone' => $user->getProfile()->phone,
            'email' => $user->email,
        ]);
    })->middleware(['auth', 'role:admin'])->name('admin.get.user');

    Route::get('/admin/get-partner/{id}', function ($id) {
        $id = ltrim($id, '0');
        $partner = User::where('role', 'partner')->find($id);
        if (!$partner) {
            return response()->json(['error' => 'Партнер не найден'], 404);
        }
        return response()->json([
            'name' => $partner->getFullName(),
            'email' => $partner->email,
        ]);
    })->middleware(['auth', 'role:admin'])->name('admin.get.partner');
});

//Profile redirect
Route::middleware('auth')->get('/profile', function () {
    $user = Auth::user();
    return match ($user->role) {
        'youth' => redirect()->route('youth.profile'),
        'admin' => redirect()->route('admin.profile'),
        'partner' => redirect()->route('partner.profile'),
        default => abort(403),
    };
})->name('profile');
