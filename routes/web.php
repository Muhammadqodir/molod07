<?php

use App\Http\Controllers\Admin\AdministratorsController;
use App\Http\Controllers\Admin\ManageEventsController;
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
    Route::get('admin/events/list', [ManageEventsController::class, 'show'])->name('admin.events.index')->defaults('status', 'active');
    Route::get('admin/events/requests', [ManageEventsController::class, 'show'])->name('admin.events.requests')->defaults('status', 'pending');
    Route::get('admin/events/archive', [ManageEventsController::class, 'show'])->name('admin.events.archive')->defaults('status', 'archived');
    Route::get('admin/events/create', [ManageEventsController::class, 'create'])->name('admin.events.create');
    Route::post('admin/events/create', [ManageEventsController::class, 'store'])->name('admin.events.store');
    Route::get('admin/events/preview/{id}', [ManageEventsController::class, 'preview'])->name('admin.events.preview');

    //Support
    Route::get('admin/support', [SupportController::class, 'show'])->name('admin.support');



    Route::get('/admin/get-user/{id}', function ($id) {
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
    })->name('admin.get.user');

    Route::get('/admin/get-partner/{id}', function ($id) {
        $partner = User::where('role', 'partner')->find($id);
        if (!$partner) {
            return response()->json(['error' => 'Партнер не найден'], 404);
        }
        return response()->json([
            'name' => $partner->getProfile()->name,
            'l_name' => $partner->getProfile()->l_name,
            'phone' => $partner->getProfile()->phone,
            'email' => $partner->email,
        ]);
    })->name('admin.get.partner');
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
